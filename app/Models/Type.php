<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property String $type_name
 */
class Type extends Model
{
    protected $primaryKey = 'type_name';
    protected $fillable = [
        "type_name",
    ];


    public static function getTypeFromValue($value): string
    {
        $type = gettype($value);
        switch ($type) {
            case 'integer':
            {
                return "int";
            }
            case 'boolean':
            {
                return "bool";
            }
            case 'double':
            {
                return "float";
            }
            case 'string':
            default:
            {
                return "string";
            }
        }
    }
}
