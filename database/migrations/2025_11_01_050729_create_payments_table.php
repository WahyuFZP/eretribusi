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
            // link to bills by id (preferred) and keep human-readable bill_number for gateway/order mapping
            // Do NOT add a foreign key constraint here because the `bills` table may be created by a later migration.
            // We'll store bill_id (nullable) and bill_number for lookup; a later migration can add FK if desired.
            $table->unsignedBigInteger('bill_id')->nullable()->index();
            $table->string('bill_number')->nullable()->index();
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

            // note: bill_id is a foreign key constrained above via foreignId()->constrained()
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
