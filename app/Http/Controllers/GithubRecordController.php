<?php

namespace App\Http\Controllers;

use App\Models\GithubRecord;
use App\Models\Constant;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class GithubRecordController extends Controller
{
    /**
     * Retrieves Github contribution records for a given year.
     */
    public static function getRecordsData(int $year): Collection
    {
        return GithubRecord::whereYear('date', $year)->get();
    }

    /**
     * Handles the incoming request to fetch and save GitHub contribution data.
     */
    public function __invoke(Request $request)
    {
        $nickname = Constant::findByKey("nickname");

        if (empty($nickname)) {
            Log::error("GithubRecordController: Nickname is not set in constants.");
            return;
        }

        $githubData = $this->fetchGithubRecords($nickname);

        if ($githubData === null || !isset($githubData["contributions"])) {
            Log::warning("GithubRecordController: Failed to retrieve or incomplete contribution data.");
            return;
        }

        $this->saveGithubContributions($githubData["contributions"]);
    }

    /**
     * Saves the fetched Github contribution data into the database within a transaction.
     *
     * @param array $contributions An array of contribution data.
     */
    private function saveGithubContributions(array $contributions): void
    {
        try {
            DB::beginTransaction();

            $this->clearExistingRecords();
            $this->insertContributions($contributions);
            $this->updateLastUpdateTimestamp();

            DB::commit();
            Log::info("Github data successfully updated.");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error saving Github data: " . $e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * Deletes all the Github records from DB
     *
     * @return void
     */
    private function clearExistingRecords(): void {
        GithubRecord::query()->delete();
    }

    /**
     * Inserts all contributions to DB
     *
     * @param array $contributions
     * @return void
     */
    private function insertContributions(array $contributions): void {
        foreach ($contributions as $oneContributionData) {
            $this->createAndSaveContribution($oneContributionData);
        }
    }

    /**
     * Creates and saves a single contribution record if the contribution count is greater than 0.
     *
     * @param array $oneContributionData Data for a single day's contribution.
     */
    private function createAndSaveContribution(array $oneContributionData): void
    {
        $dayContribution = new GithubRecord([
            "date" => $oneContributionData["date"],
            "contributions_count" => $oneContributionData["count"]
        ]);

        if ($dayContribution->contributions_count > 0) {
            $dayContribution->save();
        }
    }

    /**
     * Updates the last update timestamp for Github records.
     */
    private function updateLastUpdateTimestamp(): void
    {
        $constantKey = "githubLastUpdate";
        $lastUpdate = Constant::find($constantKey);

        if ($lastUpdate) {
            $lastUpdate->value = Carbon::now();
        } else {
            $lastUpdate = Constant::createPair($constantKey, Carbon::now());
        }
        $lastUpdate->save();
        Log::info("Github last update timestamp has been updated.");
    }

    /**
     * Fetches Github contribution data from an external API for the given nickname.
     *
     * @param string $nickname The GitHub user's nickname.
     * @return array|null Decoded data or null on error.
     */
    public function fetchGithubRecords(string $nickname): ?array
    {
        $client = new Client([
            'base_uri' => 'https://github-contributions-api.jogruber.de/v4/',
            'timeout' => 5.0,
        ]);

        try {
            $response = $client->request('GET', $nickname, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            return $this->decodeJsonResponse($response);
        } catch (GuzzleException $e) {
            Log::error("Error fetching data from Github API for '$nickname': " . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    /**
     * Decodes the JSON response from an HTTP response.
     *
     * @param ResponseInterface $response The HTTP Response object.
     * @return array|null Decoded data or null.
     */
    protected function decodeJsonResponse(ResponseInterface $response): ?array
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if ($statusCode >= 200 && $statusCode < 300) {
            $data = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            } else {
                Log::error("Error decoding JSON data from Github API: " . json_last_error_msg());
                return null;
            }
        } else {
            Log::error("Github API returned an error status: " . $statusCode . " - " . $body);
            return null;
        }
    }
}
