<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebRecordingBatchRequest;
use App\Models\WrSession;
use App\Models\WrVisitor;
use App\Models\WrWebRecordingEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WebRecordingsController extends Controller
{

    public function newBatchReceived(WebRecordingBatchRequest $request)
    {
        $validated = $request->validated();
        $sessionId = $validated['sessionId'];
        $visitorId = $validated['visitorId'];
        $batchOfEvents = $validated['events'];

        try {
            $this->newBatchReceivedImpl($visitorId, $sessionId, $batchOfEvents);
        } catch (Throwable $e) {
            Log::error("Error processing new batch of events: " . $e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * @throws Throwable
     */
    private function newBatchReceivedImpl(string $visitorId, string $sessionId, array $batchOfEvents)
    {
        DB::beginTransaction();

        $visitor = WrVisitor::firstOrCreate([
            'id_visitor' => $visitorId,
        ]);

        $session = WrSession::firstOrCreate([
            'id_session' => $sessionId,
            'id_visitor' => $visitor->id_visitor,
        ]);

        foreach ($batchOfEvents as $event) {
            WrWebRecordingEvent::create([
                'id_session' => $session->id_session,
                'event' => $event,
            ]);
        }

        DB::commit();
    }


}
