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
        Schema::table('certificates', function (Blueprint $table) {
            // Создаем составной индекс для номера телефона и статуса
            // Это позволит иметь несколько записей с одним телефоном,
            // но только если они имеют разные статусы (например, один активный, остальные отмененные)
            $table->index(['recipient_phone', 'status'], 'certificates_recipient_phone_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('certificates_recipient_phone_status_index');
        });
    }
};
