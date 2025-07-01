<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class GitHubController extends Controller
{
    public function getGitHubChartData(int $year) {

        $gitHubRecords = GitHubRecordController::getRecordsData($year);
        if ($gitHubRecords->isEmpty()) {
            throw new Exception("No github records found for year $year");
        }

        $weekCount = Carbon::createFromDate($year, 12, 28)->weekOfYear;

        return [
            'year' => $year,
            'week_count' => $weekCount,
            'git_hub_records' => $gitHubRecords,
        ];
    }
}
