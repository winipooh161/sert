<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateFolderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
                  
            // Предотвращаем дублирование
            $table->unique(['certificate_id', 'certificate_folder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_folder');
    }
}
