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
        Schema::table('certificate_templates', function (Blueprint $table) {
            // Добавляем внешний ключ на категорию
            $table->foreignId('category_id')->nullable()
                ->after('id')
                ->constrained('template_categories')
                ->nullOnDelete();
                
            // Добавляем сортировку внутри категории
            $table->integer('sort_order')->default(0)
                ->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('sort_order');
        });
    }
};
