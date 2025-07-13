<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    /**
     * @throws Exception
     */
    public function getGithubChartData(int $year) {

        $githubRecords = GithubRecordController::getRecordsData($year);
        if ($githubRecords->isEmpty()) {
            throw new Exception("No github records found for year $year");
        }

        $weekCount = Carbon::createFromDate($year, 12, 28)->weekOfYear;

        return [
            'year' => $year,
            'week_count' => $weekCount,
            'github_records' => $githubRecords,
        ];
    }


    private function getSelectedYear(): int
    {
        $selectedYear = Constant::findByKey('githubDefaultYearToSelect');
        if ($selectedYear == null || $selectedYear == -1) {
            $selectedYear = Carbon::now()->year;
        }
        return $selectedYear;
    }

    private function getLastUpdate(): Carbon {
        return Carbon::now();
    }


    public static function getInitialGithubStoreData(): array {
        return [];
    }

}

