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

    private function getCurrentlySelectedYearInGitHubGraph(): int
    {
        $currentlySelectedYearInGitHubGraph = Setting::findByKey('gitHubDefaultYearToSelect');
        if ($currentlySelectedYearInGitHubGraph == null || $currentlySelectedYearInGitHubGraph == -1) {
            $currentlySelectedYearInGitHubGraph = Carbon::now()->year;
        }
        return $currentlySelectedYearInGitHubGraph;
    }

    private function getGitHubGraphData() : array
    {
        $currentlySelectedYearInGitHubGraph = $this->getCurrentlySelectedYearInGitHubGraph();
        $gitHubRecords = GitHubRecordController::getRecordsData($currentlySelectedYearInGitHubGraph);
        $gitHubYearFrom = Setting::findByKey("gitHubYearFrom");

        return [
            'year' => $currentlySelectedYearInGitHubGraph,
            'initial_git_hub_records' => $gitHubRecords,
            'week_count' => Carbon::createFromDate($currentlySelectedYearInGitHubGraph, 12, 28)->weekOfYear,
            'git_hub_year_from' => $gitHubYearFrom,
        ];
    }
}
