<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Nezabudni importovať Carbon

/**
 * @property String $type_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Constant> $constants
 * @property-read int|null $constants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    protected $primaryKey = 'type_name';
    protected $fillable = [
        "type_name",
    ];


    public function constants(): HasMany
    {
        return $this->hasMany(Constant::class, 'type_name', 'type_name');
    }

    public static function getTypeFromValue(mixed $value): string
    {
        if ($value instanceof Carbon) {
            return "carbon";
        }

        $type = gettype($value);
        switch ($type) {
            case 'integer': {
                    return "int";
                }
            case 'boolean': {
                    return "bool";
                }
            case 'double': {
                    return "float";
                }
            case 'string':
            default: {
                    return "string";
                }
        }
    }
}
