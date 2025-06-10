<?php

namespace App\Http\Controllers;

use App\Models\GitHubRecord;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psy\Util\Str;

class GitHubRecordController extends Controller
{
    public function fetchGitHubRecords(String $nickname) : ?array
    {
        $client = new Client([
            'base_uri' => 'https://github-contributions-api.jogruber.de/v4/',
            'timeout'  => 5.0,
        ]);

        try {
            $response = $client->request('GET', $nickname, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return $this->decodeJsonResponse($response);
        } catch (GuzzleException $e) {
            // Tu by si mal logovať chybu namiesto len echo.
            // Napr. pomocou Laravel logovacieho systému: Log::error($e->getMessage());
            error_log("Chyba pri získavaní dát z API: " . $e->getMessage());
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
