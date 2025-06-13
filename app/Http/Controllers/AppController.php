<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class AppController extends Controller
{
    public function index() {
        $records = GitHubRecordController::getRecordsData();
        $settingPairs = SettingController::getSettingPairs();

        return Inertia::render('InitialScreen', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'initialGitHubRecords' => $records,
            'settingPairs' => $settingPairs,
        ]);
    }
}
