<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;
    protected $fillable = [
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

    public function scopeSearch($query, $terms)
    {
        if ($terms) {
            $query->where(function ($query) use ($terms) {
                $query->where('name', 'like', "%{$terms}%")
                    ->orWhere('address', 'like', "%{$terms}%")
                    ->orWhere('phone', 'like', "%{$terms}%")
                    ->orWhere('email', 'like', "%{$terms}%")
                    ->orWhere('badan_usaha', 'like', "%{$terms}%")
                    ->orWhere('jenis_usaha', 'like', "%{$terms}%");
            });
        }
    }
}
