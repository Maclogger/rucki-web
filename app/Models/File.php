<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'file_name',
        'original_name',
        'mime_type',
        'size',
    ];

    protected $appends = [
        'readable_size',
    ];


    /**
     * Get the user that owns the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->attributes['size'];
        $decimals = 2;

        if ($bytes === null || $bytes === 0) {
            return '0 B';
        }

        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3); // Prevod na string pre strlen()

        // Zabezpečenie, aby factor neprekročil index poľa $size
        $factor = min($factor, count($size) - 1);

        return sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . ' ' . $size[$factor];
    }
}
