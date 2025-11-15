<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure existing companies without code get a generated code
        $companies = DB::table('companies')->whereNull('code')->get();
        foreach ($companies as $company) {
            // generate unique code
            $attempt = 0;
            do {
                $candidate = 'CMP-' . strtoupper(bin2hex(random_bytes(3)));
                $exists = DB::table('companies')->where('code', $candidate)->exists();
                $attempt++;
            } while ($exists && $attempt < 10);

            if (! $exists) {
                DB::table('companies')->where('id', $company->id)->update(['code' => $candidate]);
            } else {
                DB::table('companies')->where('id', $company->id)->update(['code' => 'CMP-' . strtoupper(uniqid())]);
            }
        }

        // add unique index to code column
        Schema::table('companies', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
    }
};
