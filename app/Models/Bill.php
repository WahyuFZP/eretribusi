<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    /** @use HasFactory<\Database\Factories\BillFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'bill_number',
        'company_id',
        'amount',
        'paid_amount',
        'due_date',
        'billing_period',
        'issued_at',
        'paid_at',
        'status',
        'reference',
        'document',
        'notes',
        'created_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function company()
{
    return $this->belongsTo(\App\Models\Company::class);
}

// helper to get payments (via invoice)
public function payments()
{
    return $this->invoice ? $this->invoice->payments() : collect();
}
}
