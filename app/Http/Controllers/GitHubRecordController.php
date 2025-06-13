<?php

namespace App\Http\Controllers;

use App\Models\GitHubRecord;
use App\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class GitHubRecordController extends Controller
{
    public static function getRecordsData() : Collection
    {
        return GitHubRecord::all();
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $nickname = Setting::find("nickname")->value;

        $data = $this->fetchGitHubRecords($nickname);
        //Log::info($data);

        if ($data == null) return;


        try {
            DB::beginTransaction();
            GitHubRecord::truncate(); // deletes all the rows

            foreach ($data["contributions"] as $oneContributionData) {
/*                Log::info($oneContributionData["date"]);
                Log::info($oneContributionData["count"]);*/
                //Log::info($oneContributionData["level"]); do not know what does it mean ... not important for me, was available in API

                $dayContribution = new GitHubRecord(
                    [
                        "date" => $oneContributionData["date"],
                        "contributions_count" => $oneContributionData["count"]
                    ]
                );

                $dayContribution->save();
            }
/*            Log::info("Commit");*/
            DB::commit();
        } catch (\Throwable $e) {
/*            Log::error($e);
            Log::info("Rollback");*/
            DB::rollBack();
        }
    }

    public function fetchGitHubRecords(string $nickname): ?array
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
            Log::error("Chyba pri získavaní dát z API: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Dekóduje JSON odpoveď z HTTP response.
     *
     * @param ResponseInterface $response HTTP Response objekt
     * @return array|null Dekódované dáta alebo null
     */
    protected function decodeJsonResponse(ResponseInterface $response): ?array
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if ($statusCode >= 200 && $statusCode < 300) {
            $data = json_decode($body, true); // true zabezpečí, že dostaneš asociatívne pole
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            } else {
                error_log("Chyba pri dekódovaní JSON dát: " . json_last_error_msg());
                return null;
            }
        } else {
            error_log("API vrátilo chybový stav: " . $statusCode . " - " . $body);
            return null;
        }
    }
}
