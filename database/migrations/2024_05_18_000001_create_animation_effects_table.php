<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполнение миграций.
     */
    public function up(): void
    {
        Schema::create('animation_effects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('confetti');
            $table->text('description')->nullable();
            $table->json('particles');
            $table->string('direction')->default('center');
            $table->string('speed')->default('normal');
            $table->string('color')->nullable();
            $table->integer('size_min')->default(16);
            $table->integer('size_max')->default(32);
            $table->integer('quantity')->default(50);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Отмена миграций.
     */
    public function down(): void
    {
        Schema::dropIfExists('animation_effects');
    }
};
