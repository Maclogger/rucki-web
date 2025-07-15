<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

/**
 *
 * @property String $key
 * @property mixed $value
 * @property String $type_name
 * @property Type $type
 * @property Carbon $created_at Čas vytvorenia záznamu.
 * @property Carbon $updated_at Čas poslednej aktualizácie záznamu.
 */
class Constant extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;

    protected $table = 'constants';

    protected $fillable = [
        'key',
        'value',
        'type_name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_name', 'type_name');
    }

    public static function createAndSavePair(string $name, mixed $value): void
    {
        $constantPair = self::createPair($name, $value);
        $constantPair->save();
    }

    public static function findByKey(string $key): mixed
    {
        return Constant::find($key)->value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Constant
     */
    public static function createPair(string $name, mixed $value): Constant
    {
        return new Constant([
            'key' => $name,
            'value' => $value,
            'type_name' => Type::getTypeFromValue($value),
        ]);
    }
}
