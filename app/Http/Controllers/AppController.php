<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class AppController extends Controller
{
    public function index()
    {
        $constantPairs = ConstantController::getConstantPairs();

        return Inertia::render('AppPage', [
            'can_login' => Route::has('login'),
            'can_register' => Route::has('register'),
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'constant_pairs' => $constantPairs,
            'github_store' => new GithubController()->getInitialGithubStoreData(),
        ]);
    }
}
