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
    <script defer src="assets/js/report.js"></script>
</head>
<body>
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
                                    echo $diff->i > 0 ? $diff->i . 'min' : "Now";
                                } else {
                                    echo $diff->h . 'h';
                                }
                            } elseif ($diff->d < 7) {
                                echo $diff->d . 'j';
                            } else {
                                echo $date->format('d/m/Y');
                            }
                        ?>
                    </time>
                </div>
                <img class="three-dots" src="assets/images/icon/three-dots.svg" alt="Three dots icon" data-post-id="<?php echo $post['id']; ?>">
            </div>
            <div class="post-item">
                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post">
            </div>
            <div class="gallery-bottom">
                <div class="post-actions">
                    <img class="icon-like <?php echo $post['has_liked'] ? 'liked' : ''; ?>"
                        src="assets/images/icon/heart<?php echo $post['has_liked'] ? '_red' : ''; ?>.svg"
                        data-post-id="<?php echo $post['id']; ?>">
                    <span class="likes-count" data-post-id="<?php echo $post['id']; ?>"><?php echo $post['likes_count'] > 0 ? $post['likes_count'] : ''; ?></span>
                    <img class="icon-comment" src="assets/images/icon/comment.svg" data-post-id="<?php echo $post['id']; ?>">
                    <span class="comments-count"><?php echo $post['comments_count'] > 0 ? $post['comments_count'] : ''; ?></span>
                </div>
            <?php if (!empty($post['caption'])): ?>
            <div class="caption-container">
                <span class="post-caption-username"><?php echo htmlspecialchars($post['username']); ?></span>
                <span class="post-caption" data-full-text="<?php echo htmlspecialchars($post['caption']); ?>">
                    <?php
                        $caption = htmlspecialchars($post['caption']);
                        echo strlen($caption) > 100 ? substr($caption, 0, 100) : $caption;
                    ?>
                </span>
                <?php if (strlen($post['caption']) > 100): ?>
                <a href="#" class="more-link">more</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <dialog id="modal-post-options" class="modal-post-options">
        <div class="modal-options-content">
            <button class="option-btn option-report">Report</button>
            <button class="option-btn option-action">Go to post</button>
            <button class="option-btn option-action">Go to profile</button>
            <button class="option-btn option-cancel">Cancel</button>
        </div>
    </dialog>
    <?php require_once "modale_report.php" ?>
    <?php require_once "footer.php"; ?>
</body>
</html>