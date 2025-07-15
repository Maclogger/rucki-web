<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ConstantController extends Controller
{
    public static function getConstantPairs() : Collection
    {
        return Constant::all();
    }
}
