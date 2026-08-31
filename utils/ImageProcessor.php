<?php

declare(strict_types=1);

/**
 * Traitement des images de posts : redimensionnement, encodage WebP, miniatures
 */
class ImageProcessor
{
    public const FULL_MAX_DIM = 1280;
    public const FULL_QUALITY = 82;
    public const THUMB_MAX_DIM = 400;
    public const THUMB_QUALITY = 80;

    /**
     * Chemin de la miniature correspondant à une image de post
     * (convention : public/uploads/posts/thumbs/<nom>.webp)
     */
    public static function thumbPath(string $imagePath): string
    {
        $name = pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
        return dirname($imagePath) . '/thumbs/' . $name;
    }

    /**
     * Redimensionne (max $maxDim, jamais agrandi) et sauve en WebP, aplati sur fond blanc
     */
    public static function saveWebp($gdImage, string $savePath, int $maxDim, int $quality): bool
    {
        $srcW = imagesx($gdImage);
        $srcH = imagesy($gdImage);
        $scale = min(1.0, $maxDim / max($srcW, $srcH));
        $dstW = max(1, (int)round($srcW * $scale));
        $dstH = max(1, (int)round($srcH * $scale));

        $out = imagecreatetruecolor($dstW, $dstH);
        $white = imagecolorallocate($out, 255, 255, 255);
        imagefill($out, 0, 0, $white);
        imagecopyresampled($out, $gdImage, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        $dir = dirname($savePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ok = imagewebp($out, $savePath, $quality);
        imagedestroy($out);
        return $ok;
    }

    /**
     * Génère la miniature d'un fichier image de post existant
     * @return string|null le chemin du fichier thumb créé, ou null en cas d'échec
     */
    public static function makeThumbFromFile(string $srcFile): ?string
    {
        $img = self::loadImage($srcFile);
        if (!$img) {
            return null;
        }

        $thumbFile = self::thumbPath($srcFile);
        $ok = self::saveWebp($img, $thumbFile, self::THUMB_MAX_DIM, self::THUMB_QUALITY);
        imagedestroy($img);
        return $ok ? $thumbFile : null;
    }

    /**
     * Charge un fichier image (png/jpeg/webp) en ressource GD
     */
    public static function loadImage(string $file)
    {
        if (!is_file($file)) {
            return false;
        }
        $img = @imagecreatefrompng($file);
        if (!$img) $img = @imagecreatefromjpeg($file);
        if (!$img && function_exists('imagecreatefromwebp')) $img = @imagecreatefromwebp($file);
        return $img;
    }
}
