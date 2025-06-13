<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SettingController extends Controller
{

    public static function getSettingPairs() : Collection
    {
        return Setting::all();
    }
}
