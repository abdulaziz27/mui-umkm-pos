<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Produk');

        // Headers
        $headers = ['Nama Produk (Wajib)', 'SKU', 'Kategori', 'Tipe (physical/service)', 'Harga Jual (Wajib)', 'Stok Awal', 'Deskripsi'];

        $sheet->fromArray([$headers], null, 'A1');

        // Styling the Header Row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF16A34A'], // Tailwind green-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add a sample row to guide the user
        $sampleRow = [
            'Kopi Susu Gula Aren',
            'KS-001',
            'Minuman',
            'physical',
            '18000',
            '50',
            'Kopi espresso dengan campuran susu segar dan sirup gula aren',
        ];
        $sheet->fromArray([$sampleRow], null, 'A2');

        // Add a second sample row (Service)
        $sampleRow2 = [
            'Jasa Sablon Gelas',
            'JS-001',
            'Jasa Cetak',
            'service',
            '2500',
            '',
            'Jasa sablon per gelas plastik cup',
        ];
        $sheet->fromArray([$sampleRow2], null, 'A3');

        $sheet->getStyle('A2:G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Output to browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_import_produk.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.urlencode($fileName).'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120', // Max 5MB
        ]);

        $file = $request->file('excel_file');
        $filePath = $file->getRealPath();
        $tenant = auth()->user()->tenant;

        DB::beginTransaction();

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true); // Associative column keys A, B, C...

            if (count($rows) < 2) {
                return back()->with('error', 'File Excel kosong atau hanya berisi header.');
            }

            // Remove header row
            $header = array_shift($rows);

            $rowCount = 1; // Start at 1 (header was row 1, data starts row 2)
            $successCount = 0;
            $errors = [];

            foreach ($rows as $row) {
                $rowCount++;

                // If row is completely empty, skip
                if (empty(array_filter($row))) {
                    continue;
                }

                $name = trim($row['A'] ?? '');
                $sku = trim($row['B'] ?? '');
                $categoryName = trim($row['C'] ?? '');
                $type = strtolower(trim($row['D'] ?? 'physical'));
                $price = trim($row['E'] ?? '');
                $stock = trim($row['F'] ?? '0');
                $description = trim($row['G'] ?? '');

                // Validation
                if (empty($name)) {
                    $errors[] = "Baris {$rowCount}: Nama produk tidak boleh kosong.";

                    continue;
                }

                if ($price === '' || ! is_numeric($price) || $price < 0) {
                    $errors[] = "Baris {$rowCount}: Harga Jual '{$price}' harus berupa angka positif.";

                    continue;
                }

                if (! in_array($type, ['physical', 'service'])) {
                    $type = 'physical';
                }

                // Find or create Category
                $categoryId = null;
                if (! empty($categoryName)) {
                    $category = $tenant->categories()->firstOrCreate(
                        ['name' => $categoryName],
                        ['is_active' => true]
                    );
                    $categoryId = $category->id;
                }

                // Create Product
                $tenant->products()->create([
                    'category_id' => $categoryId,
                    'type' => $type,
                    'name' => $name,
                    'sku' => $sku ?: null,
                    'description' => $description ?: null,
                    'price' => (float) $price,
                    'stock_quantity' => $type === 'physical' ? (int) $stock : null,
                    'minimum_stock' => $type === 'physical' ? 10 : null,
                    'is_active' => true,
                ]);

                $successCount++;
            }

            if (! empty($errors)) {
                DB::rollBack();

                return back()->with('error_list', $errors)->with('error', 'Gagal mengimpor produk. Silakan perbaiki kesalahan berikut.');
            }

            DB::commit();

            return redirect()->route('menu.products.index')->with('success', "Berhasil mengimpor {$successCount} produk dari Excel.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat membaca file Excel: '.$e->getMessage());
        }
    }

    public function index()
    {
        $products = auth()->user()->tenant->products()->with('category')->latest()->paginate(10);

        return view('menu.products.index', compact('products'));
    }

    public function create()
    {
        $categories = auth()->user()->tenant->categories()->where('is_active', true)->get();

        return view('menu.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($validated['type'] === 'service') {
            $validated['stock_quantity'] = null;
            $validated['minimum_stock'] = null;
        } else {
            $validated['minimum_stock'] = $validated['minimum_stock'] ?? 10;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        auth()->user()->tenant->products()->create($validated);

        return redirect()->route('menu.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        $categories = auth()->user()->tenant->categories()->where('is_active', true)->get();

        return view('menu.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($validated['type'] === 'service') {
            $validated['stock_quantity'] = null;
            $validated['minimum_stock'] = null;
        } else {
            // Default minimum stock if not provided
            $validated['minimum_stock'] = $validated['minimum_stock'] ?? 10;
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('menu.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('menu.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
