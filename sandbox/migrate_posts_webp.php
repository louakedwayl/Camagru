<?php
// One-off : convertit les posts existants en WebP 1280px + génère leurs miniatures,
// met à jour image_path en DB et supprime les anciens fichiers
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/ImageProcessor.php';

$root = realpath(__DIR__ . '/..');
$pdo = Database::getConnection();

$posts = $pdo->query("SELECT id, image_path FROM posts")->fetchAll(PDO::FETCH_ASSOC);
$converted = 0;
$thumbsOnly = 0;
$errors = 0;

foreach ($posts as $post) {
    $relPath = $post['image_path'];
    $absPath = $root . '/' . $relPath;

    if (!is_file($absPath)) {
        fwrite(STDERR, "MISSING: post {$post['id']} -> $relPath\n");
        $errors++;
        continue;
    }

    $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));

    if ($ext === 'webp') {
        // Déjà en WebP : ne génère que la miniature si absente
        if (!is_file($root . '/' . ImageProcessor::thumbPath($relPath))) {
            ImageProcessor::makeThumbFromFile($absPath) ? $thumbsOnly++ : $errors++;
        }
        continue;
    }

    $img = ImageProcessor::loadImage($absPath);
    if (!$img) {
        fwrite(STDERR, "UNREADABLE: post {$post['id']} -> $relPath\n");
        $errors++;
        continue;
    }

    $newRelPath = preg_replace('/\.[^.]+$/', '.webp', $relPath);
    $newAbsPath = $root . '/' . $newRelPath;

    $okFull = ImageProcessor::saveWebp($img, $newAbsPath, ImageProcessor::FULL_MAX_DIM, ImageProcessor::FULL_QUALITY);
    $okThumb = ImageProcessor::saveWebp($img, $root . '/' . ImageProcessor::thumbPath($newRelPath), ImageProcessor::THUMB_MAX_DIM, ImageProcessor::THUMB_QUALITY);
    imagedestroy($img);

    if (!$okFull || !$okThumb) {
        fwrite(STDERR, "ENCODE FAIL: post {$post['id']} -> $relPath\n");
        $errors++;
        continue;
    }

    $pdo->prepare("UPDATE posts SET image_path = ? WHERE id = ?")->execute([$newRelPath, $post['id']]);
    unlink($absPath);
    $converted++;
}

echo "converted: $converted, thumbs only: $thumbsOnly, errors: $errors\n";
