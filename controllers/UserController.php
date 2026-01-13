<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../utils/Validator.php';

class UserController
{
    private PDO $pdo;
    private UserModel $userModel;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
        $this->userModel = new UserModel;
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

    public function dashboard()
    {
        require ("views/dashboard.php");
    }

    public function checkUsername(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405); // Méthode de requête non autorisée. 
            header('Content-Type: application/json');
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $username = isset($input['username']) ? trim($input['username']) : '';
        $validation = Validator::validateUsername($username);
        if (!$validation['valid']) 
        {
            http_response_code(400);
            echo json_encode(['available' => false, 'error' => $validation['error']]);
            exit;
        }
        try
        {
            $exist = $this->userModel->usernameExists($username);
            echo json_encode(['available' => !$exist]);
        }
        catch (PDOException $e)
        {
            http_response_code(500);
            echo json_encode(['available' => false, 'error' => 'database_error']);
        }
        exit;
    }
}