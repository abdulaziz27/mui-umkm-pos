<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'tenant_id', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    /**
     * Get the tenant that owns the user.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool { return $this->role === 'superadmin'; }
    public function isTenantOwner(): bool { return $this->role === 'tenant_owner'; }
    public function isCashier(): bool { return $this->role === 'cashier'; }
    
    public function canAccessMenu(): bool { return $this->isTenantOwner(); }
    public function canAccessPricing(): bool { return $this->isTenantOwner(); }
    public function canAccessInventory(): bool { return $this->isTenantOwner(); }
    public function canAccessAdmin(): bool { return $this->isTenantOwner(); }
    public function canAccessReports(): bool { return $this->isTenantOwner(); }
    public function canAuthorize(): bool { return $this->isSuperAdmin() || $this->isTenantOwner(); }
}
