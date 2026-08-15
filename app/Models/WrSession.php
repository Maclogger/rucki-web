<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WrSession extends Model
{
    protected $table = 'wr_sessions';

    protected $primaryKey = 'id_session';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_session',
        'id_visitor',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(
            WrVisitor::class,
            'id_visitor',
            'id_visitor'
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(
            WrWebRecordingEvent::class,
            'id_session',
            'id_session'
        );
    }
}
