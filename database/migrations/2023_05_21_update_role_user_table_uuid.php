<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateRoleUserTableUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Изменяем структуру таблицы role_user для поддержки UUID в поле user_id
        DB::statement('ALTER TABLE role_user MODIFY user_id VARCHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        
        // Добавляем настройку автоинкремента для поля id, если его нет
        DB::statement('ALTER TABLE role_user MODIFY id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // В случае отката возвращаем поле user_id к целочисленному типу
        DB::statement('ALTER TABLE role_user MODIFY user_id BIGINT UNSIGNED NOT NULL');
    }
}
