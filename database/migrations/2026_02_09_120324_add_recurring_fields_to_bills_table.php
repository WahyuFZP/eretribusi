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
        Schema::table('bills', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->string('recurring_frequency')->nullable()->after('is_recurring'); // monthly, yearly
            $table->integer('recurring_day_of_month')->nullable()->after('recurring_frequency'); // 1-31
            $table->date('next_billing_date')->nullable()->after('recurring_day_of_month');
            $table->foreignId('parent_bill_id')->nullable()->after('next_billing_date')->constrained('bills')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['parent_bill_id']);
            $table->dropColumn([
                'is_recurring',
                'recurring_frequency', 
                'recurring_day_of_month',
                'next_billing_date',
                'parent_bill_id'
            ]);
        });
    }
};
