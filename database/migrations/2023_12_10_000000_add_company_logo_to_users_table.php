<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Добавляем поле для хранения пути к логотипу компании
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавляем поле после company, если поле company существует
            // Иначе просто добавляем в конец таблицы
            if (Schema::hasColumn('users', 'company')) {
                $table->string('company_logo')->nullable()->after('company');
            } else {
                $table->string('company_logo')->nullable();
            }
        });
    }

    /**
     * Откат миграции
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_logo');
        });
    }
};
