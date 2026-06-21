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
        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();
        $isPng = in_array($mime, ['image/png', 'image/webp', 'image/gif'], true);

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

        $hasAlpha = $isPng && $this->hasTransparency($image, imagesx($image), imagesy($image));

        [$width, $height] = [imagesx($image), imagesy($image)];

        // Downscale if larger than maxDimension
        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            if ($hasAlpha) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
            }
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
            $width = $newWidth;
            $height = $newHeight;
        }

        $filename = Str::random(32) . '.' . ($hasAlpha ? 'png' : 'jpg');
        $relativePath = trim($directory, '/') . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $targetBytes = $targetKb * 1024;

        if ($hasAlpha) {
            // Preserve transparency — PNG only, never flatten to JPEG
            imagesavealpha($image, true);
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
        if (!imageistruecolor($image)) {
            $transparentIndex = imagecolortransparent($image);
            if ($transparentIndex >= 0) {
                return true;
            }
        }

        // Scan a grid of sample points across the full image rather than just
        // corners/center — logos often have opaque marks but transparent margins
        // that a sparse corner-only sample can miss entirely.
        $stepsX = min(20, $width);
        $stepsY = min(20, $height);
        for ($i = 0; $i < $stepsX; $i++) {
            for ($j = 0; $j < $stepsY; $j++) {
                $x = (int) (($i + 0.5) * $width / $stepsX);
                $y = (int) (($j + 0.5) * $height / $stepsY);
                $rgba = imagecolorat($image, min($x, $width - 1), min($y, $height - 1));
                $alpha = ($rgba & 0x7F000000) >> 24;
                if ($alpha > 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
