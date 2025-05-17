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
        // Проверяем существование таблицы и при необходимости создаем её заново
        if (!Schema::hasTable('certificate_folder')) {
            Schema::create('certificate_folder', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('certificate_id');
                $table->unsignedBigInteger('certificate_folder_id');
                $table->timestamps();

                $table->foreign('certificate_id')
                    ->references('id')
                    ->on('certificates')
                    ->onDelete('cascade');
                    
                $table->foreign('certificate_folder_id')
                    ->references('id')
                    ->on('certificate_folders')
                    ->onDelete('cascade');
                    
                // Уникальный индекс для предотвращения дублирования
                $table->unique(['certificate_id', 'certificate_folder_id']);
            });
        } else {
            // Если таблица существует, проверяем и добавляем индексы при необходимости
            Schema::table('certificate_folder', function (Blueprint $table) {
                // Проверяем наличие ключей и индексов
                if (!Schema::hasColumn('certificate_folder', 'id')) {
                    $table->id();
                }
                
                // Проверим и создадим внешние ключи
                if (!Schema::hasColumn('certificate_folder', 'certificate_id')) {
                    $table->unsignedBigInteger('certificate_id');
                    $table->foreign('certificate_id')
                        ->references('id')
                        ->on('certificates')
                        ->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('certificate_folder', 'certificate_folder_id')) {
                    $table->unsignedBigInteger('certificate_folder_id');
                    $table->foreign('certificate_folder_id')
                        ->references('id')
                        ->on('certificate_folders')
                        ->onDelete('cascade');
                }
                
                // Добавляем уникальный индекс, если его еще нет
                $table->unique(['certificate_id', 'certificate_folder_id'], 'cert_folder_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // В down ничего не делаем, чтобы не удалить данные случайно
    }
};
