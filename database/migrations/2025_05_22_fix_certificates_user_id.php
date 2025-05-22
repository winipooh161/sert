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
            // Изменяем тип столбца user_id на строку длиной 36 символов (для UUID)
            $table->string('user_id', 36)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // В случае отката миграции можем вернуть прежний тип столбца
            // Обратите внимание, что нужно знать, какой тип был ранее (обычно integer или bigInteger)
            $table->integer('user_id')->change();
        });
    }
};
