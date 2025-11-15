<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Bill;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;
    protected $fillable = [
        // 'code' is intentionally omitted from fillable to prevent mass-assignment by users.
        // Code will be generated automatically in the model boot.
        'user_id',
        'name',
        'address',
        'phone',
        'email',
        'badan_usaha',
        'jenis_usaha',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get invoices for the company.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get bills for the company (if you use a separate Bill model).
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }



    /**
     * Scope a query to search across several company fields.
     */
    public function scopeSearch($query, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('badan_usaha', 'like', "%{$term}%")
              ->orWhere('jenis_usaha', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%");
        });
    }

    /**
     * Auto-generate a unique company code when creating.
     */
    protected static function booted()
    {
        static::creating(function (Company $company) {
            if (empty($company->code)) {
                $company->code = static::generateUniqueCode();
            }
        });

        // Prevent changing code on update
        static::updating(function (Company $company) {
            if ($company->isDirty('code')) {
                // revert to original value to keep code immutable
                $company->code = $company->getOriginal('code');
            }
        });
    }

    protected static function generateUniqueCode(): string
    {
        // Basic generator: 6 uppercase alphanumeric characters prefixed by CMP-
        // Retry a few times to avoid collisions.
        for ($i = 0; $i < 10; $i++) {
            $candidate = 'CMP-' . strtoupper(
                bin2hex(random_bytes(3))
            );
            if (!static::where('code', $candidate)->exists()) {
                return $candidate;
            }
        }

        // Fallback to timestamp-based code
        return 'CMP-' . strtoupper(uniqid());
    }
}
