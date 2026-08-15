<?php

namespace App\Http\Controllers;

use App\Services\WebRecordingService;
use Illuminate\Http\JsonResponse;

/**
 * Reading recordings for the admin pages. Incoming batches are handled by WebRecordingBatchController.
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
}
