<?php

namespace Database\Seeders;

use App\Models\Constant;
use Illuminate\Database\Seeder;

class ConstantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Constant::truncate();
        Constant::createAndSavePair('titul', "Bc.");
        Constant::createAndSavePair('meno', "Marek");
        Constant::createAndSavePair('priezvisko', "Rucki");
        Constant::createAndSavePair('rola', "Software Developer");
        Constant::createAndSavePair('nickname', "Maclogger");
        Constant::createAndSavePair('dateAndTimeFormat', "dd.MM.yyyy HH:mm");
        Constant::createAndSavePair('dateFormat', "dd.MM.yyyy");
        Constant::createAndSavePair('githubYearFrom', 2017);
        Constant::createAndSavePair('githubDefaultYearToSelect', 2025); // if value == -1 then current year will be used
        Constant::createAndSavePair('bufferCodeLength', 4);
        Constant::createAndSavePair('galleryPollingIntervalSeconds', 2);
        Constant::createAndSavePair('galleryPollingPageSize', 5);

        Constant::createAndSavePair('phoneNumber', "+421 918 024 666");

        Constant::createAndSavePair('mail', "marek@rucki.sk");

        Constant::createAndSavePair('currentLocation', "Žilina");
        Constant::createAndSavePair('currentLocationLink', "https://maps.app.goo.gl/dxj9sqUpHG44Vqro6");

        Constant::createAndSavePair('githubUserName', "Marek Rucki");
        Constant::createAndSavePair('gitHubLink', "https://github.com/Maclogger");
    }
}
