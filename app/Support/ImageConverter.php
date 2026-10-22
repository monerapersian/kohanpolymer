<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ImageConverter
{
    public static function convertToWebp(
        UploadedFile $file,
        string $directory = 'uploads'
    ): string {
        $source = $file->getRealPath();

        if (! $source || ! is_file($source)) {
            throw new RuntimeException('فایل تصویر قابل خواندن نیست.');
        }

        $imageInfo = @getimagesize($source);

        if (! $imageInfo || empty($imageInfo['mime'])) {
            throw new RuntimeException('فرمت تصویر معتبر نیست.');
        }

        $image = match ($imageInfo['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($source),
            'image/png' => @imagecreatefrompng($source),
            'image/gif' => @imagecreatefromgif($source),
            'image/webp' => @imagecreatefromwebp($source),
            'image/bmp', 'image/x-ms-bmp' => @imagecreatefrombmp($source),
            default => false,
        };

        if (! $image) {
            throw new RuntimeException(
                'این فرمت تصویر پشتیبانی نمی‌شود یا فایل خراب است.'
            );
        }

        try {
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);

            ob_start();

            try {
                $success = imagewebp($image, null, 80);
                $contents = ob_get_clean();
            } catch (\Throwable $exception) {
                ob_end_clean();
                throw $exception;
            }

            if (! $success || ! is_string($contents) || $contents === '') {
                throw new RuntimeException('تبدیل تصویر به WebP ناموفق بود.');
            }

            $path = trim($directory, '/').'/'.bin2hex(random_bytes(16)).'.webp';

            if (! Storage::disk('public')->put($path, $contents)) {
                throw new RuntimeException('ذخیره تصویر تبدیل‌شده ناموفق بود.');
            }
            
            return $path;
        } finally {
            imagedestroy($image);
        }
    }
}