<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WrWebRecordingEvent extends Model
{
    protected $table = 'wr_web_recording_events';

    protected $fillable = [
        'id_session',
        'event',
    ];

    protected $casts = [
        'event' => 'array',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            WrSession::class,
            'id_session',
            'id_session'
        );
    }

}
