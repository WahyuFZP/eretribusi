<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceCounter extends Model
{
    use HasFactory;

    protected $table = 'invoice_counters';

    protected $fillable = [
        'day',
        'key',
        'last_number',
    ];

    protected $casts = [
        'day' => 'date',
        'last_number' => 'integer',
    ];
}
