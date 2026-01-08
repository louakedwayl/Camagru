<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';


class UserController
{
    private PDO $pdo;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }

    public function index()
    {
        require ("views/index.php");
    }

    public function register()
    {
        require ("views/register.php");
    }

    public function password_reset()
    {
        require ("views/password_reset.php");
    }

    public function email_signup()
    {
        require ("views/email_signup.php");
    }
}