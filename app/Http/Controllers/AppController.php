<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class AppController extends Controller
{
    public function index()
    {
        $gitHubRecords = GitHubRecordController::getRecordsData(Carbon::now()->year);
        $settingPairs = SettingController::getSettingPairs();

        return Inertia::render('InitialScreen', [
            'can_login' => Route::has('login'),
            'can_register' => Route::has('register'),
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'setting_pairs' => $settingPairs,
            'git_hub_chart_data' => [
                'year' => Carbon::now()->year,
                'initial_git_hub_records' => $gitHubRecords,
                'week_count' => Carbon::createFromDate(Carbon::now()->year, 12, 28)->weekOfYear,
            ],
        ]);
    }
}
