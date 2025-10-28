<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $table = "files";
    protected $fillable = [
        'id_user',
        'file_name',
        'thumbnail_name',
        'original_name',
        'mime_type',
        'size',
    ];

    protected $appends = [
        'readable_size',
        'is_photo',
    ];


    /**
     * Get the user that owns the photo.
     */
    public function user(): BelongsTo
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

    public function getIsPhotoAttribute(): bool
    {
        $mime = $this->attributes['mime_type'] ?? $this->mime_type ?? null;

        if (!$mime) {
            return false;
        }

        return str_starts_with(strtolower($mime), 'image/');
    }
}
