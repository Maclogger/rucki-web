<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BufferCode extends Model
{
    protected $table = 'buffer_codes';

    protected $fillable = [
        'code',
        'number_of_usages',
        'enabled',
    ];

    protected $casts = [
        'number_of_usages' => 'integer',
        'enabled' => 'boolean',
    ];
}
