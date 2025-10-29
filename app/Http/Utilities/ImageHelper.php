<?php

namespace App\Http\Utilities;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Generate a thumbnail for an image file
     *
     * @param string $originalPath Path to the original image in storage
     * @param string $thumbnailPath Path where thumbnail will be saved
     * @param int $maxWidth Maximum width of thumbnail
     * @param int $maxHeight Maximum height of thumbnail
     * @param int $quality JPEG quality (1-100)
     * @return bool Success status
     */
    public static function generateThumbnail(
        string $originalPath,
        string $thumbnailPath,
        int $maxWidth = 400,
        int $maxHeight = 400,
        int $quality = 85
    ): bool {
        try {
            // Get the full path to the original file
            $fullOriginalPath = Storage::disk('local')->path($originalPath);

            if (!file_exists($fullOriginalPath)) {
                Log::error("Original file not found for thumbnail generation: $fullOriginalPath");
                return false;
            }

            // Get image info
            $imageInfo = getimagesize($fullOriginalPath);
            if ($imageInfo === false) {
                Log::warning("File is not a valid image: $fullOriginalPath");
                return false;
            }

            [$width, $height, $type] = $imageInfo;

            // Create image resource based on type
            $source = match($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($fullOriginalPath),
                IMAGETYPE_PNG => imagecreatefrompng($fullOriginalPath),
                IMAGETYPE_GIF => imagecreatefromgif($fullOriginalPath),
                IMAGETYPE_WEBP => imagecreatefromwebp($fullOriginalPath),
                default => null,
            };

            if ($source === null || $source === false) {
                Log::warning("Unsupported image type or failed to create image resource: $fullOriginalPath");
                return false;
            }

            // Handle EXIF orientation for images (especially from mobile devices)
            if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($fullOriginalPath);
                if ($exif !== false && !empty($exif['Orientation'])) {
                    $source = self::correctImageOrientation($source, $exif['Orientation']);

                    // After rotation, we may need to swap width and height
                    if (in_array($exif['Orientation'], [5, 6, 7, 8])) {
                        [$width, $height] = [$height, $width];
                    }
                }
            }

            // Calculate new dimensions maintaining aspect ratio
            $ratio = min($maxWidth / $width, $maxHeight / $height);

            // If image is already smaller than max dimensions, use original size
            if ($ratio >= 1) {
                $newWidth = $width;
                $newHeight = $height;
            } else {
                $newWidth = (int) round($width * $ratio);
                $newHeight = (int) round($height * $ratio);
            }

            // Create thumbnail
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

            if ($thumbnail === false) {
                imagedestroy($source);
                Log::error("Failed to create thumbnail resource");
                return false;
            }

            // Preserve transparency for PNG and GIF
            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resample the image
            imagecopyresampled(
                $thumbnail,
                $source,
                0, 0, 0, 0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );

            // Get the full path for thumbnail
            $fullThumbnailPath = Storage::disk('local')->path($thumbnailPath);

            // Ensure directory exists
            $thumbnailDir = dirname($fullThumbnailPath);
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Save thumbnail based on original type
            $saved = match($type) {
                IMAGETYPE_JPEG => imagejpeg($thumbnail, $fullThumbnailPath, $quality),
                IMAGETYPE_PNG => imagepng($thumbnail, $fullThumbnailPath, (int) round((100 - $quality) / 11)),
                IMAGETYPE_GIF => imagegif($thumbnail, $fullThumbnailPath),
                IMAGETYPE_WEBP => imagewebp($thumbnail, $fullThumbnailPath, $quality),
                default => false,
            };

            // Free memory
            imagedestroy($source);
            imagedestroy($thumbnail);

            if (!$saved) {
                Log::error("Failed to save thumbnail: $fullThumbnailPath");
                return false;
            }

            Log::info("Thumbnail generated successfully: $thumbnailPath ({$newWidth}x{$newHeight})");
            return true;

        } catch (Exception $e) {
            Log::error("Exception during thumbnail generation: " . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    /**
     * Check if a file is an image based on mime type
     */
    public static function isImage(string $mimeType): bool
    {
        return str_starts_with(strtolower($mimeType), 'image/');
    }

    /**
     * Correct image orientation based on EXIF data
     *
     * @param \GdImage $image The GD image resource
     * @param int $orientation EXIF orientation value (1-8)
     * @return \GdImage Corrected image resource
     */
    private static function correctImageOrientation($image, int $orientation)
    {
        switch ($orientation) {
            case 2:
                // Flip horizontal
                imageflip($image, IMG_FLIP_HORIZONTAL);
                break;
            case 3:
                // Rotate 180 degrees
                $image = imagerotate($image, 180, 0);
                break;
            case 4:
                // Flip vertical
                imageflip($image, IMG_FLIP_VERTICAL);
                break;
            case 5:
                // Rotate 90 degrees clockwise and flip horizontal
                $image = imagerotate($image, -90, 0);
                imageflip($image, IMG_FLIP_HORIZONTAL);
                break;
            case 6:
                // Rotate 90 degrees clockwise (most common for iPhone)
                $image = imagerotate($image, -90, 0);
                break;
            case 7:
                // Rotate 90 degrees counter-clockwise and flip horizontal
                $image = imagerotate($image, 90, 0);
                imageflip($image, IMG_FLIP_HORIZONTAL);
                break;
            case 8:
                // Rotate 90 degrees counter-clockwise
                $image = imagerotate($image, 90, 0);
                break;
            case 1:
            default:
                // No rotation needed
                break;
        }

        return $image;
    }

    /**
     * Get supported image types
     */
    public static function getSupportedImageTypes(): array
    {
        return ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    }
}

