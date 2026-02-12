<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/create.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
    <script defer src="assets/js/create.js"></script>
    <script defer src="assets/js/report.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/navbar.php'; ?>

    <main class="create-page">
        <!-- ===== EDITOR CONTAINER ===== -->
        <div class="editor-container">

            <!-- EDITOR HEADER -->
            <div class="editor-header">
                <span class="editor-title">Create new post</span>
                <button class="btn-share" disabled>Share</button>
            </div>

            <!-- EDITOR BODY -->
            <div class="editor-body">

                <!-- LEFT: PREVIEW -->
                <div class="editor-preview">
                    <!-- Webcam -->
                    <video id="webcam" autoplay playsinline></video>
                    <!-- Uploaded image (hidden by default) -->
                    <img id="uploaded-preview" src="" alt="Preview" style="display: none;">
                    <!-- Canvas for sticker overlay preview -->
                    <canvas id="sticker-canvas"></canvas>
                    <!-- Sticker resize controls -->
                    <div class="sticker-controls" id="sticker-controls" style="display: none;">
                        <button class="sticker-btn" id="sticker-plus">+</button>
                        <button class="sticker-btn" id="sticker-minus">−</button>
                        <button class="sticker-btn sticker-btn-delete" id="sticker-delete">×</button>
                    </div>
                    <!-- No source placeholder -->
                    <div class="preview-placeholder" id="preview-placeholder">
                        <img src="assets/images/icon/camera.svg" class="placeholder-icon">
                        <span class="placeholder-text">Take a photo or upload an image</span>
                    </div>
                    <!-- Source toggle -->
                    <div class="source-toggle">
                        <button class="btn-source active" id="btn-webcam">
                            <img src="assets/images/icon/camera_white.svg" alt="Webcam">
                            <span>Camera</span>
                        </button>
                        <label class="btn-source" id="btn-upload">
                            <img src="assets/images/icon/create.svg" alt="Upload">
                            <span>Upload</span>
                            <input type="file" id="file-input" accept="image/*" style="display: none;">
                        </label>
                    </div>
                </div>

                <!-- RIGHT: PANEL -->
                <div class="editor-panel">

                    <!-- Stickers grid -->
                    <div class="stickers-section">
                        <h3 class="panel-title">Stickers</h3>
                        <div class="stickers-grid" id="stickers-grid">
                            <!-- Stickers will be loaded here -->
                        </div>
                    </div>

                    <!-- Caption -->
                    <div class="caption-section">
                        <textarea class="caption-input" id="caption-input" placeholder="Write a caption..." maxlength="2200"></textarea>
                        <span class="caption-count"><span id="caption-length">0</span>/2200</span>
                    </div>

                    <!-- Capture button -->
                    <div class="capture-section">
                        <button class="btn-capture" id="btn-capture">
                            <div class="capture-ring">
                                <div class="capture-inner"></div>
                            </div>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ===== PREVIOUS PHOTOS ===== -->
        <?php if (!empty($userPosts)): ?>
        <div class="previous-section">
            <div class="previous-grid" id="previous-grid">
                <?php foreach ($userPosts as $post): ?>
                <div class="previous-item" data-post-id="<?= $post['id'] ?>">
                    <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Previous photo" class="previous-img">
                    <div class="previous-overlay">
                        <span class="gallery-stat">
                            <img src="assets/images/icon/heart_white.svg" class="gallery-stat-icon"> <?= $post['likes_count'] ?? 0 ?>
                        </span>
                        <span class="gallery-stat">
                            <img src="assets/images/icon/comment_white.svg" class="gallery-stat-icon"> <?= $post['comments_count'] ?? 0 ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <?php require_once "modale_report.php" ?>
</body>
</html>