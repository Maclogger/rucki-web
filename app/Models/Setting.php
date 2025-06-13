<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property String $key
 * @property mixed $value
 * @property Type $type
 * @property Carbon $created_at Čas vytvorenia záznamu.
 * @property Carbon $updated_at Čas poslednej aktualizácie záznamu.
 */
class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;

    protected $fillable = [
        'key',
        'value',
        'type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public static function createSettingPair(string $name, mixed $value): void
    {
        $settingPair = new Setting([
            'key' => $name,
            'value' => $value,
            'type_name' => Type::getTypeFromValue($value),
        ]);

        $settingPair->save();
    }
}
