<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/post.css">
    <link rel="stylesheet" href="assets/css/mobile_navbar.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
    <script defer src="assets/js/report.js"></script>
    <script defer src="assets/js/post.js"></script>
</head>
<body>
    <?php require_once "mobile_navbar.php"; ?>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <main class="post-page">
        <div class="post-container">
            <!-- HEADER MOBILE -->
            <div class="post-header post-header-mobile">
                <img src="<?= htmlspecialchars($post['user_avatar'] ?? 'assets/images/default-avatar.jpeg') ?>" alt="Avatar" class="post-avatar">
                <a href="index.php?action=user_profile&username=<?= htmlspecialchars($post['username']) ?>" class="post-username"><?= htmlspecialchars($post['username']) ?></a>
                <img class="three-dots" src="assets/images/icon/three-dots.svg" alt="Three dots icon" data-post-id="<?php echo $post['id']; ?>">
            </div>
            <!-- IMAGE -->
            <div class="post-image-side">
                <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post">
            </div>
            <!-- DETAILS -->
            <div class="post-details-side">
                <!-- HEADER DESKTOP -->
                <div class="post-header post-header-desktop">
                    <img src="<?= htmlspecialchars($post['user_avatar'] ?? 'assets/images/default-avatar.jpeg') ?>" alt="Avatar" class="post-avatar">
                    <a href="index.php?action=user_profile&username=<?= htmlspecialchars($post['username']) ?>" class="post-username"><?= htmlspecialchars($post['username']) ?></a>
                    <img class="three-dots" src="assets/images/icon/three-dots.svg" alt="Three dots icon" data-post-id="<?php echo $post['id']; ?>">
                </div>
                <!-- CAPTION + COMMENTS -->
                <div class="post-comments-section">
                    <?php if ($post['caption']): ?>
                    <div class="comment-item">
                        <img src="<?= htmlspecialchars($post['user_avatar'] ?? 'assets/images/default-avatar.jpeg') ?>" alt="Avatar" class="comment-avatar">
                        <div class="comment-content">
                            <a href="index.php?action=user_profile&username=<?= htmlspecialchars($post['username']) ?>" class="comment-username"><?= htmlspecialchars($post['username']) ?></a>
                            <span class="comment-text"><?= htmlspecialchars($post['caption']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <img src="<?= htmlspecialchars($comment['avatar_path'] ?? 'assets/images/default-avatar.jpeg') ?>" alt="Avatar" class="comment-avatar">
                        <div class="comment-content">
                            <a href="index.php?action=user_profile&username=<?= htmlspecialchars($comment['username']) ?>" class="comment-username"><?= htmlspecialchars($comment['username']) ?></a>
                            <span class="comment-text"><?= htmlspecialchars($comment['content']) ?></span>
                            <time class="comment-time">
                                <?php
                                    $date = new DateTime($comment['created_at']);
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
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- ACTIONS -->
                <div class="post-actions-bar">
                    <div class="post-actions-icons">
                        <img class="icon-like <?= $hasLiked ? 'liked' : '' ?>"
                            src="assets/images/icon/heart<?= $hasLiked ? '_red' : '' ?>.svg"
                            data-post-id="<?= $post['id'] ?>">
                        <img class="icon-comment-focus" src="assets/images/icon/comment.svg">
                    </div>
                    <span class="likes-count"><?= $post['likes_count'] ?> like<?= $post['likes_count'] > 1 ? 's' : '' ?></span>
                    <time class="post-time">
                        <?php
                            $date = new DateTime($post['created_at']);
                            echo $date->format('d F Y');
                        ?>
                    </time>
                </div>
                <!-- ADD COMMENT -->
                <div class="post-add-comment">
                    <textarea placeholder="Add a comment..." class="comment-input" data-post-id="<?= $post['id'] ?>"></textarea>
                    <button class="comment-submit" disabled>Post</button>
                </div>
            </div>
        </div>
    </main>
    <dialog id="modal-post-options" class="modal-post-options">
        <div class="modal-options-content">
            <button class="option-btn option-report">Report</button>
            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
            <button class="option-btn" id="delete-post">Delete</button>
            <?php endif; ?>
            <button class="option-btn option-action" id="go-to-profile">Go to profile</button>
            <button class="option-btn option-cancel">Cancel</button>
        </div>
    </dialog>
    <?php require_once "modale_report.php" ?>
</body>
</html>