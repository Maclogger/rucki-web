<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @param int $id_user
 */
class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'file_name',
        'original_name',
        'mime_type',
        'size',
    ];


    /**
     * Get the user that owns the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
