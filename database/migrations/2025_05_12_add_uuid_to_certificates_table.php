<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
        });

        // Если есть существующие сертификаты, назначим им UUID
        DB::table('certificates')->whereNull('uuid')->chunkById(100, function ($certificates) {
            foreach ($certificates as $certificate) {
                DB::table('certificates')
                    ->where('id', $certificate->id)
                    ->update(['uuid' => Str::uuid()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
