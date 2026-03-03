<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payment;
use Illuminate\Support\Str;

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
        'is_recurring',
        'recurring_frequency',
        'recurring_day_of_month',
        'next_billing_date',
        'parent_bill_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'next_billing_date' => 'date',
        'is_recurring' => 'boolean',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
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
    return $this->hasMany(Payment::class);
}

/**
 * Convenience relation for getting the latest payment for this bill.
 */
public function latestPayment()
{
    return $this->hasOne(Payment::class)->latestOfMany();
}

/**
 * Parent bill relationship (for recurring bills)
 */
public function parentBill()
{
    return $this->belongsTo(Bill::class, 'parent_bill_id');
}

/**
 * Child bills relationship (bills generated from this recurring bill)
 */
public function childBills()
{
    return $this->hasMany(Bill::class, 'parent_bill_id');
}

/**
 * Generate next recurring bill
 */
public function generateNextBill()
{
    if (!$this->is_recurring || !$this->next_billing_date) {
        return null;
    }

    // Check if already generated for this period
    $existingBill = static::where('parent_bill_id', $this->id)
        ->where('billing_period', $this->next_billing_date->format('Y-m'))
        ->first();
    
    if ($existingBill) {
        return $existingBill;
    }

    $nextBillData = [
        'bill_number' => 'BILL-' . strtoupper(Str::random(6)) . '-' . date('YmdHis'),
        'company_id' => $this->company_id,
        'amount' => $this->amount,
        'due_date' => $this->next_billing_date,
        'billing_period' => $this->next_billing_date->format('Y-m'),
        'issued_at' => now(),
        'status' => 'unpaid',
        'notes' => 'Auto-generated from recurring bill: ' . $this->bill_number,
        'created_by' => $this->created_by,
        'parent_bill_id' => $this->id,
        'is_recurring' => false, // Child bills are not recurring
    ];

    $nextBill = static::create($nextBillData);

    // Update next billing date for parent bill
    $this->updateNextBillingDate();

    return $nextBill;
}

/**
 * Update next billing date based on frequency
 */
public function updateNextBillingDate()
{
    if (!$this->is_recurring || !$this->next_billing_date) {
        return;
    }

    $nextDate = match ($this->recurring_frequency) {
        'monthly' => $this->next_billing_date->addMonth(),
        'yearly' => $this->next_billing_date->addYear(),
        default => null,
    };

    if ($nextDate) {
        $this->next_billing_date = $nextDate;
        $this->save();
    }
}
}
