<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrCode extends Model
{
    protected $table = 'qr_codes';

    protected $fillable = [
        'uuid',
        'question_index',
        'content',
    ];

    protected $casts = [
        'uuid' => 'string',
        'question_index' => 'integer',
        'content' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}

