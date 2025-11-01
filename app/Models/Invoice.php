<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'company_id',
        'user_id',
        'invoice_date',
        'due_date',
        'currency',
        'amount',
        'paid_amount',
        'late_fee',
        'status',
        'issued_at',
        'due_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Generate invoice number on creating
    protected static function booted()
    {
        static::creating(function (Invoice $invoice) {
            // if invoice_number already set by caller, skip
            if (!empty($invoice->invoice_number)) {
                return;
            }

            // determine key (entity_code)
            $key = 'GEN';
            if (!empty($invoice->entity_code)) {
                $key = strtoupper($invoice->entity_code);
            } elseif ($invoice->company && !empty($invoice->company->code)) {
                $key = strtoupper($invoice->company->code);
            }

            // do atomic increment on invoice_counters
            $day = now()->toDateString(); // e.g. 2025-10-11

            $next = null;

            DB::transaction(function () use ($day, $key, &$next) {
                // try to get counter row for (day,key) with lock
                $counter = DB::table('invoice_counters')
                    ->where('day', $day)
                    ->where('key', $key)
                    ->lockForUpdate()
                    ->first();

                if ($counter) {
                    $next = $counter->last_number + 1;
                    DB::table('invoice_counters')
                        ->where('id', $counter->id)
                        ->update([
                            'last_number' => $next,
                            'updated_at' => now(),
                        ]);
                } else {
                    $next = 1;
                    DB::table('invoice_counters')->insert([
                        'day' => $day,
                        'key' => $key,
                        'last_number' => $next,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $seq = str_pad((string)$next, 3, '0', STR_PAD_LEFT); // 001
            $invoice->invoice_number = sprintf('INV-%s-%s-%s', $key, now()->format('Ymd'), $seq);
        });
    }

}
