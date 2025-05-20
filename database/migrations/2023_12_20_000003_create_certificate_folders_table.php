<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('color')->default('primary');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Внешний ключ
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Создаем промежуточную таблицу для связи многие-ко-многим между сертификатами и папками
        Schema::create('certificate_certificate_folder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_id');
            $table->unsignedBigInteger('certificate_folder_id');
            $table->timestamps();

            // Внешние ключи
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
            $table->foreign('certificate_folder_id')->references('id')->on('certificate_folders')->onDelete('cascade');
            
            // Уникальный индекс для предотвращения дублирования
            $table->unique(['certificate_id', 'certificate_folder_id'], 'cert_folder_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_certificate_folder');
        Schema::dropIfExists('certificate_folders');
    }
}
