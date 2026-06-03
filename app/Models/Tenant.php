<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'type',
    'description',
    'address',
    'phone',
    'logo_path',
    'status',
    'mdr_fee_percentage',
    'platform_fee_type',
    'platform_fee_fixed',
    'halal_certificate_number',
    'halal_certificate_expires_at',
    'receipt_header',
    'receipt_footer',
    'operating_hours',
    'instagram_handle',
    'whatsapp_number',
    'website_url',
])]
class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'halal_certificate_expires_at' => 'date',
            'operating_hours' => 'array',
            'mdr_fee_percentage' => 'decimal:2',
            'platform_fee_fixed' => 'decimal:2',
        ];
    }

    /**
     * Get all users associated with the tenant.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function promos(): HasMany
    {
        return $this->hasMany(Promo::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
