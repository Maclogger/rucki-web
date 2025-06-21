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
        $settingPairs = SettingController::getSettingPairs();

        return Inertia::render('AppPage', [
            'can_login' => Route::has('login'),
            'can_register' => Route::has('register'),
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'setting_pairs' => $settingPairs,
            'git_hub_chart_data' => $this->getGitHubGraphData(),
        ]);
    }

    private function getCurrentlyDisplayedYearInGitHubGraph(): int
    {
        $currentlyDisplayedYearInGitHubGraph = Setting::findByKey('gitHubDefaultYearToSelect');
        if ($currentlyDisplayedYearInGitHubGraph == null || $currentlyDisplayedYearInGitHubGraph == -1) {
            $currentlyDisplayedYearInGitHubGraph = Carbon::now()->year;
        }
        return $currentlyDisplayedYearInGitHubGraph;
    }

    private function getGitHubGraphData() : array
    {
        $currentlyDisplayedYearInGitHubGraph = $this->getCurrentlyDisplayedYearInGitHubGraph();
        $gitHubRecords = GitHubRecordController::getRecordsData($currentlyDisplayedYearInGitHubGraph);

        return [
            'year' => $currentlyDisplayedYearInGitHubGraph,
            'initial_git_hub_records' => $gitHubRecords,
            'week_count' => Carbon::createFromDate($currentlyDisplayedYearInGitHubGraph, 12, 28)->weekOfYear,
        ];
    }
}
