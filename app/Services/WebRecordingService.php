<?php

namespace App\Services;

use App\Models\WrSession;
use App\Models\WrVisitor;
use App\Models\WrWebRecordingEvent;
use Illuminate\Support\Facades\DB;
use Throwable;

class WebRecordingService
{
    /**
     * Stores a batch of rrweb events. The visitor and the session are created
     * by the first batch that references them.
     *
     * @param array<int, mixed> $events
     * @throws Throwable
     */
    public function storeBatch(string $visitorId, string $sessionId, array $events): void
    {
        DB::transaction(function () use ($visitorId, $sessionId, $events) {
            WrVisitor::firstOrCreate(['id_visitor' => $visitorId]);

            WrSession::firstOrCreate([
                'id_session' => $sessionId,
                'id_visitor' => $visitorId,
            ]);

            $now = now();

            // A single bulk insert instead of one query per event.
            // insert() bypasses casts, so the JSON is encoded by hand.
            WrWebRecordingEvent::insert(array_map(fn ($event) => [
                'id_session' => $sessionId,
                'event' => json_encode($event, JSON_THROW_ON_ERROR),
                'created_at' => $now,
                'updated_at' => $now,
            ], $events));
        });
    }

    /**
     * Deletes a session together with all of its events. There is no foreign key
     * between the two tables, so the events have to be removed explicitly.
     *
     * @throws Throwable
     */
    public function deleteSession(string $idSession): void
    {
        DB::transaction(function () use ($idSession) {
            WrWebRecordingEvent::where('id_session', $idSession)->delete();
            WrSession::where('id_session', $idSession)->delete();
        });
    }

    /**
     * Events of a session in the order they were recorded - the replayer cannot handle any other.
     *
     * @return array<int, mixed>
     */
    public function eventsForSession(string $idSession): array
    {
        return WrWebRecordingEvent::query()
            ->where('id_session', $idSession)
            ->orderBy('id')
            ->pluck('event')
            ->all();
    }
}
