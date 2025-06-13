<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::createSettingPair('titul', "Bc.");
        Setting::createSettingPair('meno', "Marek");
        Setting::createSettingPair('priezvisko', "Rucki");
        Setting::createSettingPair('nickname', "Maclogger");
        Setting::createSettingPair('dateAndTimeFormat', "dd.MM.yyyy HH:mm");
    }
}
