<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'superadmin') {
            $topups = Topup::with('tenant')->latest()->paginate(10);
            return view('topup.admin', compact('topups'));
        } elseif ($user->role === 'tenant_owner') {
            $topups = Topup::where('tenant_id', $user->tenant_id)->latest()->paginate(10);
            return view('topup.index', compact('topups'));
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('topup_proofs', 'public');
        }

        $topup = Topup::create([
            'tenant_id' => $user->tenant_id,
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_proof_path' => $paymentProofPath,
        ]);

        $secretKey = config('xendit.secret_key');
        
        // Coba integrasi Xendit jika dikonfigurasi DAN user tidak upload bukti manual
        if ($secretKey && !$paymentProofPath) {
            $invoiceId = 'TOPUP-' . $topup->id;

            $response = Http::withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $invoiceId,
                    'amount' => $request->amount,
                    'description' => 'Top-Up Saldo Deposit Daulat Umat',
                    'customer' => [
                        'given_names' => $user->name,
                        'email' => $user->email,
                    ],
                    'success_redirect_url' => route('topups.success', ['topup_id' => $topup->id]),
                    'failure_redirect_url' => route('topups.index'),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $topup->update([
                    'xendit_invoice_id' => $data['id'],
                    'payment_url' => $data['invoice_url'],
                ]);
                
                return redirect($data['invoice_url']);
            }

            // Jika gagal buat invoice, kita biarkan topup tetap pending untuk diproses manual
            return redirect()->route('topups.index')->with('success', 'Pengajuan tercatat, namun Xendit sedang bermasalah. Anda dapat melunasi secara manual atau menunggu persetujuan.');
        }

        return redirect()->route('topups.index')->with('success', 'Pengajuan isi saldo berhasil dicatat. Silakan tunggu konfirmasi Admin atau selesaikan transfer manual Anda.');
    }

    public function success(Request $request)
    {
        $topupId = $request->query('topup_id');
        if (!$topupId) {
            return redirect()->route('topups.index')->with('success', 'Proses verifikasi pembayaran.');
        }

        $topup = Topup::where('id', $topupId)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->firstOrFail();

        if ($topup->status === 'approved') {
             return redirect()->route('topups.index')->with('success', 'Pembayaran berhasil, saldo sudah bertambah.');
        }

        $secretKey = config('xendit.secret_key');
        $response = Http::withBasicAuth($secretKey, '')
            ->get('https://api.xendit.co/v2/invoices/' . $topup->xendit_invoice_id);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === 'PAID' || $data['status'] === 'SETTLED') {
                DB::transaction(function () use ($topup) {
                    $topup->update(['status' => 'approved']);
                    $topup->tenant->increment('credit_balance', $topup->amount);
                });
                return redirect()->route('topups.index')->with('success', 'Pembayaran Xendit berhasil. Saldo telah ditambahkan!');
            }
        }

        return redirect()->route('topups.index')->with('success', 'Pembayaran masih tertunda atau dalam proses.');
    }

    public function approve(Topup $topup)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);
        if ($topup->status !== 'pending') return back()->withErrors('Top-up ini sudah diproses sebelumnya.');

        DB::transaction(function () use ($topup) {
            $topup->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            $tenant = $topup->tenant;
            $tenant->increment('credit_balance', $topup->amount);
        });

        return back()->with('success', 'Top-up disetujui, saldo tenant bertambah Rp ' . number_format($topup->amount, 0, ',', '.'));
    }

    public function reject(Topup $topup)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);
        if ($topup->status !== 'pending') return back()->withErrors('Top-up ini sudah diproses sebelumnya.');

        $topup->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Top-up ditolak.');
    }
}
