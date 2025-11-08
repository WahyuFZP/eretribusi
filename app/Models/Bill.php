<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /** @use HasFactory<\Database\Factories\BillFactory> */
    use HasFactory;

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
