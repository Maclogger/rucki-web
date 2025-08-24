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
        Constant::createAndSavePair('rola', "Junior Developer");
        Constant::createAndSavePair('nickname', "Maclogger");
        Constant::createAndSavePair('dateAndTimeFormat', "dd.MM.yyyy HH:mm");
        Constant::createAndSavePair('dateFormat', "dd.MM.yyyy");
        Constant::createAndSavePair('githubYearFrom', 2017);
        Constant::createAndSavePair('githubDefaultYearToSelect', 2025); // if value == -1 then current year will be used
    }
}
