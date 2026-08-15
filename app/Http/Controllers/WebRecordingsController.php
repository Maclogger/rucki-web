<?php

namespace App\Http\Controllers;

use App\Services\WebRecordingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

/**
 * Reading and managing recordings for the admin pages.
 * Incoming batches are handled by WebRecordingBatchController.
 */
class WebRecordingsController extends Controller
{
    public function __construct(private readonly WebRecordingService $webRecordings) {}

    public function fetchEvents(string $idSession): JsonResponse
    {
        return response()->json([
            'events' => $this->webRecordings->eventsForSession($idSession),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function deleteSession(string $idSession): Response
    {
        $this->webRecordings->deleteSession($idSession);

        return response()->noContent();
    }
}
