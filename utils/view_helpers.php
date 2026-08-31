<?php

declare(strict_types=1);

require_once __DIR__ . '/ImageProcessor.php';

/**
 * URL de la miniature d'un post pour les grilles ; retombe sur l'image
 * originale si la miniature n'existe pas (anciens posts, seed, etc.)
 */
function post_thumb(string $imagePath): string
{
    $thumb = ImageProcessor::thumbPath($imagePath);
    if (is_file(__DIR__ . '/../' . $thumb)) {
        return $thumb;
    }
    return $imagePath;
}
