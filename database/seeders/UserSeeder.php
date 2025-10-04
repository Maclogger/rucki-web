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
        $this->createAccountBasedOnEnvVariables("BUFFER_CODE_ACCOUNT_USERNAME", "BUFFER_CODE_ACCOUNT_PASSWORD");
        $this->createAccountBasedOnEnvVariables("WEB_USERNAME", "WEB_PASSWORD");
    }

    private function createAccountBasedOnEnvVariables(string $envUsernameKey, string $envPasswordKey): void
    {
        $username = env($envUsernameKey);
        $password = env($envPasswordKey);

        if ($username == null || $password == null) {
            Log::info("Account was not created, .env variables [$envUsernameKey, $envPasswordKey] could not be found.");
            return;
        }

        $this->createUser($username, $password);
    }

    private function createUser($username, $password): void
    {
        $user = new User([
            "username" => $username,
            "password" => $password,
        ]);
        $user->save();
    }
}
