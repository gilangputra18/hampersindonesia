<?php

namespace App\Helpers;

class ImageOptimizer
{
    /**
     * Create square thumbnail (e.g. 160x160) from a source image
     */
    public static function createThumbnail(string $sourcePath, string $targetPath, int $size = 160, int $quality = 82): bool
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $dir = dirname($targetPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $info = @getimagesize($sourcePath);
        if (!$info) {
            return false;
        }

        $mime = $info['mime'] ?? '';
        $srcW = $info[0];
        $srcH = $info[1];

        $srcImg = null;
        if ($mime === 'image/jpeg' || str_ends_with(strtolower($sourcePath), '.jpg') || str_ends_with(strtolower($sourcePath), '.jpeg')) {
            $srcImg = @imagecreatefromjpeg($sourcePath);
        } elseif ($mime === 'image/png' || str_ends_with(strtolower($sourcePath), '.png')) {
            $srcImg = @imagecreatefrompng($sourcePath);
        } elseif ($mime === 'image/webp' || str_ends_with(strtolower($sourcePath), '.webp')) {
            if (function_exists('imagecreatefromwebp')) {
                $srcImg = @imagecreatefromwebp($sourcePath);
            }
        }

        if (!$srcImg) {
            return false;
        }

        // Center crop calculation for perfect square thumbnail
        $cropSize = min($srcW, $srcH);
        $srcX = (int) round(($srcW - $cropSize) / 2);
        $srcY = (int) round(($srcH - $cropSize) / 2);

        $thumb = imagecreatetruecolor($size, $size);

        // Preserve transparency for PNG / WebP
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $size, $size, $transparent);
        }

        imagecopyresampled($thumb, $srcImg, 0, 0, $srcX, $srcY, $size, $size, $cropSize, $cropSize);

        $success = false;
        if (str_ends_with(strtolower($targetPath), '.png')) {
            $success = imagepng($thumb, $targetPath, 8);
        } elseif (str_ends_with(strtolower($targetPath), '.webp') && function_exists('imagewebp')) {
            $success = imagewebp($thumb, $targetPath, $quality);
        } else {
            $success = imagejpeg($thumb, $targetPath, $quality);
        }

        imagedestroy($thumb);
        imagedestroy($srcImg);

        return $success;
    }

    /**
     * Optimize high-res image down to web-friendly max dimension and compression
     */
    public static function optimizeImage(string $filePath, int $maxDimension = 900, int $quality = 82): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $info = @getimagesize($filePath);
        if (!$info) {
            return false;
        }

        $mime = $info['mime'] ?? '';
        $srcW = $info[0];
        $srcH = $info[1];

        // Skip if already small enough in file size
        if ($srcW <= $maxDimension && $srcH <= $maxDimension && filesize($filePath) < 150 * 1024) {
            return true;
        }

        $srcImg = null;
        if ($mime === 'image/jpeg' || str_ends_with(strtolower($filePath), '.jpg') || str_ends_with(strtolower($filePath), '.jpeg')) {
            $srcImg = @imagecreatefromjpeg($filePath);
        } elseif ($mime === 'image/png' || str_ends_with(strtolower($filePath), '.png')) {
            $srcImg = @imagecreatefrompng($filePath);
        } elseif ($mime === 'image/webp' || str_ends_with(strtolower($filePath), '.webp')) {
            if (function_exists('imagecreatefromwebp')) {
                $srcImg = @imagecreatefromwebp($filePath);
            }
        }

        if (!$srcImg) {
            return false;
        }

        // Calculate proportional scale
        $ratio = min($maxDimension / $srcW, $maxDimension / $srcH, 1.0);
        $newW = (int) round($srcW * $ratio);
        $newH = (int) round($srcH * $ratio);

        $dst = imagecreatetruecolor($newW, $newH);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $srcImg, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        $tempPath = $filePath . '.tmp';
        $success = false;

        if (str_ends_with(strtolower($filePath), '.png')) {
            $success = imagepng($dst, $tempPath, 8);
        } elseif (str_ends_with(strtolower($filePath), '.webp') && function_exists('imagewebp')) {
            $success = imagewebp($dst, $tempPath, $quality);
        } else {
            $success = imagejpeg($dst, $tempPath, $quality);
        }

        imagedestroy($dst);
        imagedestroy($srcImg);

        if ($success && file_exists($tempPath)) {
            // Only replace if optimized version is smaller or successfully written
            if (filesize($tempPath) < filesize($filePath) || $srcW > $maxDimension) {
                @rename($tempPath, $filePath);
            } else {
                @unlink($tempPath);
            }
            return true;
        }

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        return false;
    }

    /**
     * Process an image: optimize original + create thumbnail in public/images/thumbs/
     */
    public static function process(string $filePath): void
    {
        if (!file_exists($filePath)) {
            return;
        }

        $baseName = basename($filePath);
        $thumbPath = public_path('images/thumbs/' . $baseName);

        // 1. Generate thumbnail first before modifying original (or after)
        self::createThumbnail($filePath, $thumbPath, 160, 82);

        // 2. Optimize original
        self::optimizeImage($filePath, 900, 82);
    }
}
