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
        'code',
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
}
