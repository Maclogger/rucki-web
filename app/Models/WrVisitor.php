<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WrVisitor extends Model
{
    protected $table = 'wr_visitors';

    protected $primaryKey = 'id_visitor';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_visitor',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(
            WrSession::class,
            'id_visitor',
            'id_visitor'
        );
    }
}
