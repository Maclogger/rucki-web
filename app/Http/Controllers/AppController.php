<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class AppController extends Controller
{
    public function getPublicStoreData()
    {
        $constantPairs = ConstantController::getConstantPairs();
        return [
            'can_login' => Route::has('login'),
            'can_register' => Route::has('register'),
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'constant_pairs' => $constantPairs,
        ];
    }
}
