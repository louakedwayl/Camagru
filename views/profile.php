<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/modale_edit_profile.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
    <script defer src="assets/js/profile.js"></script>
</head>
<body>
    <div class="overlay"></div>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <main>
        <header>
                <form id="avatar-form" enctype="multipart/form-data">
                <label for="avatar-input" class="avatar-label">
                <img src="assets/images/default-avatar.jpeg" class="profile-avatar" id="current-avatar">
                <div class="avatar-overlay">
                </div>
                <img src="assets/images/icon/camera_white.svg" class="camera-white-icon">

                </label>
                <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                </form>
                <div class="user-info">
                    <span class="fullname"><?php echo $user['full_name']?></span>
                    <span class="username"><?php echo $user['username']?></span>
                </div>
        </header>
        <div class="user-actions">
            <button class="btn-edit-profile">Edit Profile</button>
            <button class="btn-public-view" title="Public View">Public View</button>
        </div>
        <div class="separator-line"></div>
        <?php if (empty($userPosts)) { ?>
            <div id = "gallery-empty-state">
                <img src="assets/images/icon/camera.svg" class="camera-icon">
                <span id="share-photo">Share Photos</span>
                <span id="text-share-photo">When you share photos, they will appear on your profile.</span>
                <a href="#" id="create-link">Share you first photo</a>
            </div>
        <?php } else { ?>
            <div class = "gallery">
                <?php foreach ($userPosts as $post){ ?>
                    <div class="gallery-item">
                        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post image" class="gallery-img">
                    </div>
                <?php } ?>
            </div>
        <?php }?>
        <?php require_once "footer.php" ?>
    </main>
    <?php require_once "modale_report.php" ?>
    <?php require_once "modale_edit_profile.php" ?>
</body>
</html>