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

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        require __DIR__ . '/../views/create.php';
    }

    public function explore(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $posts = $this->postModel->getAllPosts();
        require __DIR__ . '/../views/explore.php';
    }

}
