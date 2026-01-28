<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/home.css"> 
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/hamburger.js"></script>
</head>
<body>
    <div class="overlay"></div>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <main>
        <div class="user-menu">
            <img src="assets/images/default-avatar.jpeg" alt="Avatar" class="avatar-img">
                <div class="UserName">
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <span class="fullname"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                </div>
        </div>
            <div class="gallery-grid">
            <?php foreach ($posts as $post): ?>
                <div class="gallery-top">
                    <img src="<?php echo htmlspecialchars($post['user_avatar'] ?? 'assets/images/default-avatar.jpeg'); ?>" 
                        alt="Avatar" 
                        class="post-user-avatar">
                    <div class="post-user-info">
                        <span class="post-username"><?php echo htmlspecialchars($post['username']); ?></span>
                        <time class="post-date">
                            <?php 
                                $date = new DateTime($post['created_at']);
                                $now = new DateTime();
                                $diff = $now->diff($date);
                                
                                if ($diff->d == 0) {
                                    if ($diff->h == 0) {
                                        echo $diff->i > 0 ? $diff->i . ' min' : "À l'instant";
                                    } else {
                                        echo $diff->h . ' h';
                                    }
                                } elseif ($diff->d < 7) {
                                    echo $diff->d . ' j';
                                } else {
                                    echo $date->format('d/m/Y');
                                }
                            ?>
                        </time>
                    </div>
                </div>
                <div class="post-item">
                    <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post">
                </div>
                <div class="gallery-top"></div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php require_once "modale_report.php" ?>
    <?php require_once "footer.php" ?>
</body>
</html>

