<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/PostModel.php';

class PostController
{

    private PostModel $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

public function toggleLike(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'error' => 'Not logged in']);
        exit;
    }

    $postId = (int)($_POST['post_id'] ?? 0);
    
    if ($postId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
        exit;
    }

    $userId = (int)$_SESSION['user_id'];
    $hasLiked = $this->postModel->hasUserLiked($userId, $postId);

    if ($hasLiked) {
        $this->postModel->removeLike($userId, $postId);
    } else {
        $this->postModel->addLike($userId, $postId);
    }

    $post = $this->postModel->getPostById($postId);

    echo json_encode([
        'success' => true,
        'liked' => !$hasLiked,
        'likes_count' => (int)$post['likes_count']
    ]);
    exit;
}



    public function getStickers(): void
{
    $stickers = [];
    $dir = __DIR__ . '/../assets/images/stickers/';
    
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
                $stickers[] = $file;
            }
        }
    }
    
    sort($stickers);
    header('Content-Type: application/json');
    echo json_encode($stickers);
    exit;
}



public function homeVisitor(): void
{
    $posts = $this->postModel->getAllPosts() ?? [];
    require __DIR__ . '/../views/visitor_home.php';
}

public function exploreVisitor(): void
{
    $posts = $this->postModel->getAllPosts();
    require __DIR__ . '/../views/visitor_explore.php';
}

public function showPostVisitor(): void
{
    $postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($postId <= 0) {
        header('Location: index.php?action=visitor_explore');
        exit;
    }
    $post = $this->postModel->getPostById($postId);
    if (!$post) {
        header('Location: index.php?action=visitor_explore');
        exit;
    }
    $comments = $this->postModel->getComments($postId);
    $hasLiked = false;
    require __DIR__ . '/../views/visitor_post.php';
}


public function capture(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'error' => 'Not logged in']);
        exit;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'No image received']);
        exit;
    }

    $tmpPath = $_FILES['image']['tmp_name'];
    $baseImage = imagecreatefrompng($tmpPath);
    
    if (!$baseImage) {
        $baseImage = imagecreatefromjpeg($tmpPath);
    }
    
    if (!$baseImage) {
        echo json_encode(['success' => false, 'error' => 'Invalid image']);
        exit;
    }

    imagealphablending($baseImage, true);

    // Apply stickers with positions
    $stickers = json_decode($_POST['stickers'] ?? '[]', true);
    $stickersDir = __DIR__ . '/../assets/images/stickers/';

    foreach ($stickers as $stickerData) {
        $stickerFile = basename($stickerData['name']);
        $stickerPath = $stickersDir . $stickerFile;
        
        if (!file_exists($stickerPath)) continue;

        $stickerImg = imagecreatefrompng($stickerPath);
        if (!$stickerImg) continue;

        $sW = imagesx($stickerImg);
        $sH = imagesy($stickerImg);

        $dstX = (int)$stickerData['x'];
        $dstY = (int)$stickerData['y'];
        $dstW = (int)$stickerData['width'];
        $dstH = (int)$stickerData['height'];

        imagecopyresampled($baseImage, $stickerImg, $dstX, $dstY, 0, 0, $dstW, $dstH, $sW, $sH);
        imagedestroy($stickerImg);
    }

    $filename = uniqid('post_') . '.png';
    $savePath = __DIR__ . '/../public/uploads/posts/' . $filename;
    $dbPath = 'public/uploads/posts/' . $filename;

    imagesavealpha($baseImage, true);
    imagepng($baseImage, $savePath);
    imagedestroy($baseImage);

    $caption = trim($_POST['caption'] ?? '');
    $userId = (int)$_SESSION['user_id'];

    $success = $this->postModel->createPost($userId, $dbPath, $caption ?: null);

    echo json_encode(['success' => $success]);
    exit;
}

public function deletePostAction(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'error' => 'Not logged in']);
        exit;
    }

    $postId = (int)($_POST['post_id'] ?? 0);
    
    if ($postId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
        exit;
    }

    // Get post to delete the file
    $post = $this->postModel->getPostById($postId);
    
    if ($post && $post['user_id'] == $_SESSION['user_id']) {
        // Delete the image file
        $filePath = __DIR__ . '/../' . $post['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $success = $this->postModel->deletePost($postId, (int)$_SESSION['user_id']);
    echo json_encode(['success' => $success]);
    exit;
}


    public function home(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $postModel = new PostModel();
        $posts = $postModel->getAllPosts((int)$_SESSION['user_id']);

        require __DIR__ . '/../views/home.php';
    }

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $userPosts = $this->postModel->getPostsByUserId((int)$_SESSION['user_id']);
        require __DIR__ . '/../views/create.php';
    }

    public function explore(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $posts = $this->postModel->getAllPosts();
        require __DIR__ . '/../views/explore.php';
    }

    public function showPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($postId <= 0) {
            header('Location: index.php?action=home');
            exit;
        }

        $post = $this->postModel->getPostById($postId);
        
        if (!$post) {
            header('Location: index.php?action=home');
            exit;
        }

        $comments = $this->postModel->getComments($postId);
        $hasLiked = $this->postModel->hasUserLiked((int)$_SESSION['user_id'], $postId);

        require __DIR__ . '/../views/post.php';
    }



}
