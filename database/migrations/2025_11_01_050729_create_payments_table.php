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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('method')->nullable();
            $table->string('gateway')->nullable();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->nullable();

            $table->json('gateway_response')->nullable();

            $table->timestamp('paid_at')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();

            $table->json('metadata')->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            // MySQL allows multiple NULLs in unique index; we still create unique on gateway+transaction_id
            $table->unique(['gateway', 'transaction_id'], 'payments_gateway_tx_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
