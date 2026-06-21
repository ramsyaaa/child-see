<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Store an uploaded image, compressing it down to a target file size (KB) if needed.
     * Returns the stored relative path (disk: public).
     */
    public function storeCompressed(UploadedFile $file, string $directory, int $targetKb = 500, int $maxDimension = 1600): string
    {
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $filename = Str::random(32) . '.' . ($extension === 'png' ? 'png' : 'jpg');
        $relativePath = trim($directory, '/') . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();

        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($sourcePath) : null,
            'image/gif' => imagecreatefromgif($sourcePath),
            default => null,
        };

        // Unsupported type or GD failure: fall back to plain store
        if (!$image) {
            return $file->store($directory, 'public');
        }

        [$width, $height] = [imagesx($image), imagesy($image)];

        // Downscale if larger than maxDimension
        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
            $width = $newWidth;
            $height = $newHeight;
        }

        // Always re-encode as JPEG (much better compression ratio than PNG for photos),
        // unless original PNG has transparency we want to preserve — keep PNG path for those.
        $targetBytes = $targetKb * 1024;

        if ($extension === 'png' && $this->hasTransparency($image, $width, $height)) {
            $compressionLevel = 6;
            do {
                imagepng($image, $fullPath, $compressionLevel);
                $compressionLevel++;
            } while (filesize($fullPath) > $targetBytes && $compressionLevel <= 9);
        } else {
            $quality = 85;
            do {
                imagejpeg($image, $fullPath, $quality);
                $quality -= 10;
            } while (filesize($fullPath) > $targetBytes && $quality > 20);
        }

        imagedestroy($image);

        return $relativePath;
    }

    private function hasTransparency($image, int $width, int $height): bool
    {
        // Sample a handful of pixels for an alpha channel rather than scanning the whole image
        $samplePoints = [[0, 0], [$width - 1, 0], [0, $height - 1], [$width - 1, $height - 1], [(int)($width / 2), (int)($height / 2)]];
        foreach ($samplePoints as [$x, $y]) {
            $rgba = imagecolorat($image, $x, $y);
            $alpha = ($rgba & 0x7F000000) >> 24;
            if ($alpha > 0) {
                return true;
            }
        }
        return false;
    }
}
