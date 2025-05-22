<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Изменяем структуру таблицы users для поддержки UUID в поле id
        // Увеличиваем длину поля до 36 символов, чтобы вместить весь UUID
        DB::statement('ALTER TABLE users MODIFY id VARCHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // В случае отката возвращаем поле id к целочисленному типу с автоинкрементом
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
    }
}
