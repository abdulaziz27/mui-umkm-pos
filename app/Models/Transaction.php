<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tenant_id',
    'cashier_id',
    'receipt_number',
    'subtotal',
    'discount_amount',
    'total_amount',
    'platform_fee',
    'payment_method',
    'payment_status',
])]
class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
        ];
    }

    /**
     * Get the tenant that owns the transaction.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the cashier that processed the transaction.
     *
     * @return BelongsTo<User, $this>
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the items for the transaction.
     *
     * @return HasMany<TransactionItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
