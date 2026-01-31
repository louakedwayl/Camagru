<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
    <script defer src="assets/js/profile.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <main>
        <header>
                <form id="avatar-form" enctype="multipart/form-data">
                <label for="avatar-input" class="avatar-label">
                <img src="assets/images/default-avatar.jpeg" class="profile-avatar" id="current-avatar">
                <div class="avatar-overlay">
                </div>
                </label>
                <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                </form>
                <div class="user-info">
                    <span class="fullname"><?php echo $user['full_name']?></span>
                    <span class="username"><?php echo $user['username']?></span>
                </div>
        </header>
        <div class="user-actions">
                <a href="#" class="btn-profile">Edit Profile</a>
                <a href="#" class="btn-icon" title="Public View">Public View</a>
        </div>
        <div class="overlay"></div>
    </main>
    <?php require_once "modale_report.php" ?>
</body>
</html>