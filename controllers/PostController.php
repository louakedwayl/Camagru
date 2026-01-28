<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/PostModel.php';

class PostController
{
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
        $posts = $postModel->getAllPosts() ?? [];

        require __DIR__ . '/../views/home.php';
    }
}
