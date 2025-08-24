<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GithubController extends Controller
{
    public function getGithubChartData(int $year)
    {
        $githubRecords = GithubRecordController::getRecordsData($year);
        $totalContributions = $this->calcTotalContributions($githubRecords);

        return [
            'year' => $year,
            'total_contributions' => $totalContributions,
            'github_records' => $githubRecords,
        ];
    }

    private function calcTotalContributions(Collection $githubRecords): int
    {
        $totalContributions = 0;
        foreach ($githubRecords as $githubRecord) {
            $totalContributions += $githubRecord->contributions_count;
        }
        return $totalContributions;
    }


    private function getSelectedYear(): int
    {
        $selectedYear = Constant::findByKey('githubDefaultYearToSelect');
        if ($selectedYear == null || $selectedYear == -1) {
            $selectedYear = Carbon::now()->year;
        }
        return $selectedYear;
    }

    public function getInitialGithubStoreData(): array
    {
        $lastUpdate = new Carbon(Constant::findByKey('githubLastUpdate'));
        $selectedYear = $this->getSelectedYear();
        $firstYear = Constant::findByKey("gitHubYearFrom");

        $yearsToLoad = [2025];

        $dataByYear = [];
        foreach ($yearsToLoad as $year) {
            $dataByYear[$year] = $this->getGithubChartData($year);
        }

        return [
            'last_update' => $lastUpdate,
            'selected_year' => $selectedYear,
            'first_year' => $firstYear,
            'data_by_year' => $dataByYear
        ];
    }
}
