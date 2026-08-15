<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebRecordingBatchRequest;
use App\Services\WebRecordingService;
use Illuminate\Http\Response;
use Throwable;

/**
 * Public endpoint receiving batches from the rrweb recorder running in the visitor's browser.
 */
class WebRecordingBatchController extends Controller
{
    public function __construct(private readonly WebRecordingService $webRecordings) {}

    /**
     * @throws Throwable
     */
    public function __invoke(WebRecordingBatchRequest $request): Response
    {
        $validated = $request->validated();

        // Failures are deliberately not caught - the frontend puts the batch back in its
        // queue and retries. Answering 200 on failure would discard the events for good.
        $this->webRecordings->storeBatch(
            $validated['visitorId'],
            $validated['sessionId'],
            $validated['events'],
        );

        return response()->noContent();
    }
}
