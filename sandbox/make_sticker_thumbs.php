<?php
// One-off: génère des miniatures 150px des stickers pour la grille de sélection
$src = __DIR__ . '/../assets/images/stickers/';
$dst = $src . 'thumbs/';
if (!is_dir($dst)) mkdir($dst, 0755, true);

$count = 0;
foreach (glob($src . '*.png') as $file) {
    $name = basename($file);
    $img = imagecreatefrompng($file);
    if (!$img) { fwrite(STDERR, "skip $name\n"); continue; }

    $w = imagesx($img);
    $h = imagesy($img);
    $scale = min(1.0, 150 / max($w, $h));
    $tw = max(1, (int)round($w * $scale));
    $th = max(1, (int)round($h * $scale));

    $thumb = imagecreatetruecolor($tw, $th);
    imagealphablending($thumb, false);
    imagesavealpha($thumb, true);
    $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
    imagefill($thumb, 0, 0, $transparent);
    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $tw, $th, $w, $h);

    imagepng($thumb, $dst . $name, 9);
    imagedestroy($img);
    imagedestroy($thumb);
    $count++;
}
echo "$count thumbs generated in $dst\n";
