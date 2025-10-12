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
        new TypeSeeder()->run();
        new ConstantSeeder()->run();
        new UserSeeder()->run();
    }
}
