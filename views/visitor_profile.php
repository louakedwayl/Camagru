<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Camagru</title>
<link rel="stylesheet" href="assets/css/profile.css">
<link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
<script defer src="assets/js/hamburger.js"></script>
<script defer src="assets/js/visitor_profile.js"></script>
</head>
<body>
<?php require_once __DIR__ . '/visitor_navbar.php'; ?>
<main>
<header>
<div class="avatar-label">
<img src="<?= htmlspecialchars($user['avatar_path'] ?? 'assets/images/default-avatar.jpeg') ?>" 
class="profile-avatar" 
alt="<?= htmlspecialchars($user['username']) ?>">
</div>
<div class="user-info">
<span class="username"><?= htmlspecialchars($user['username']) ?></span>
<span class="fullname"><?= htmlspecialchars($user['full_name']) ?></span>
</div>
</header>
<div class="separator-line"></div>
<?php if (empty($userPosts)) { ?>
<div id="gallery-empty-state">
<img src="assets/images/icon/camera.svg" class="camera-icon">
<span id="share-photo">No Posts Yet</span>
<span id="text-share-photo">When <?= htmlspecialchars($user['username']) ?> shares photos, they will appear here.</span>
</div>
<?php } else { ?>
<div class="gallery">
<?php foreach ($userPosts as $post) { ?>
<div class="gallery-item" data-post-id="<?= $post['id'] ?>">
<img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post image" class="gallery-img">
<div class="gallery-overlay">
<span class="gallery-stat">
<img src="assets/images/icon/heart_white.svg" class="gallery-stat-icon"> <?= $post['likes_count'] ?? 0 ?>
</span>
<span class="gallery-stat">
<img src="assets/images/icon/comment_white.svg" class="gallery-stat-icon"> <?= $post['comments_count'] ?? 0 ?>
</span>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php require_once "footer.php"; ?>
</main>
<?php require_once "modale_report.php"; ?>
</body>
</html>