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
        Schema::create('certificate_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('color')->default('primary');
            $table->timestamps();
        });

        // Создаем связующую таблицу между сертификатами и папками
        Schema::create('certificate_folder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('certificate_folder_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_folder');
        Schema::dropIfExists('certificate_folders');
    }
};
