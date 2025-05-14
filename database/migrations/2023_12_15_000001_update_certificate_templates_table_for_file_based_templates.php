<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            // Добавляем новое поле для пути к файлу шаблона
            $table->string('template_path')->nullable()->after('html_template');
        });

        // Устанавливаем template_path для существующих записей
        $templates = DB::table('certificate_templates')->get();
        foreach ($templates as $template) {
            // Определяем путь к файлу шаблона на основе имени шаблона
            $templatePath = 'templates/certificate-' . strtolower(str_replace(' ', '-', $template->name)) . '.html';
            
            // Обновляем запись
            DB::table('certificate_templates')
                ->where('id', $template->id)
                ->update(['template_path' => $templatePath]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn('template_path');
        });
    }
};
