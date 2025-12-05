<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'snap_token',
        'bill_id',
        'bill_number',
        'company_id',
        'amount',
        'method',
        'gateway',
        'order_id',
        'transaction_id',
        'reference',
        'status',
        'gateway_response',
        'paid_at',
        'created_by',
        'metadata',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'amount' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
