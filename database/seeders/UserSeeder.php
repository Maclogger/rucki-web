<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $username = env("WEB_USERNAME");
        $password = env("WEB_PASSWORD");

        if ($username == null || $password == null) {
            Log::info("User was not created, env variables could not be find.");
            return;
        }

        $username = new User([
            "username" => $username,
            "password" => $password,
        ]);

        $username->save();
    }
}
