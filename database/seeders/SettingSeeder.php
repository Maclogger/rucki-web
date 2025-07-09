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
        Setting::truncate();
        Setting::createSettingPair('titul', "Bc.");
        Setting::createSettingPair('meno', "Marek");
        Setting::createSettingPair('priezvisko', "Rucki");
        Setting::createSettingPair('rola', "Junior Developer");
        Setting::createSettingPair('nickname', "Maclogger");
        Setting::createSettingPair('dateAndTimeFormat', "dd.MM.yyyy HH:mm");
        Setting::createSettingPair('dateFormat', "dd.MM.yyyy");
        Setting::createSettingPair('gitHubYearFrom', 2017);
        Setting::createSettingPair('gitHubDefaultYearToSelect', 2025); // if value == -1 then current year will be used
    }
}
