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
        Schema::create('animation_effects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('particles'); // Массив эмоджи или символов для анимации
            $table->string('direction')->default('center'); // center, top, random, etc.
            $table->string('type')->default('emoji'); // emoji, confetti, snow, etc.
            $table->string('speed')->default('normal'); // slow, normal, fast
            $table->string('color')->nullable(); // Для эффектов с цветом (не emoji)
            $table->integer('size_min')->default(16); // Минимальный размер частиц
            $table->integer('size_max')->default(32); // Максимальный размер частиц
            $table->integer('quantity')->default(50); // Количество частиц в эффекте
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Добавляем базовые эффекты
        DB::table('animation_effects')->insert([
            [
                'name' => 'Конфетти',
                'slug' => 'confetti',
                'description' => 'Красочные смайлики разлетаются от краев экрана к центру',
                'particles' => json_encode(['🎉', '🎊', '🎁', '🎈', '✨', '🌟', '🎇']),
                'direction' => 'center',
                'type' => 'emoji',
                'speed' => 'normal',
                'size_min' => 16,
                'size_max' => 32,
                'quantity' => 70,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Снежинки',
                'slug' => 'snow',
                'description' => 'Мягко падающие снежинки создают зимнюю атмосферу',
                'particles' => json_encode(['❄️', '❄', '❅', '❆', '☃️']),
                'direction' => 'bottom',
                'type' => 'snow',
                'speed' => 'slow',
                'size_min' => 14,
                'size_max' => 28,
                'quantity' => 60,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animation_effects');
    }
};
