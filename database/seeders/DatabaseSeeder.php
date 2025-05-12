<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CertificateTemplateSeeder::class,
            // Можно добавить больше сидеров по мере необходимости
        ]);
    }
}
