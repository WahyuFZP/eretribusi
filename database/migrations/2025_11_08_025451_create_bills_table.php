<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            // Human-friendly bill number (INV-... or BILL-...)
            $table->string('bill_number')->nullable()->unique();

            // Link to company (who will be billed)
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();

            // Amounts
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);

            // Dates and period
            $table->date('due_date')->nullable();
            $table->string('billing_period')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Status: unpaid, partial, paid, cancelled
            $table->string('status')->default('unpaid');

            // Optional references / documents
            $table->string('reference')->nullable();
            $table->string('document')->nullable();
            $table->text('notes')->nullable();

            // Who created the bill (admin user)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Soft delete and timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
