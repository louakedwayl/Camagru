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

    public function dashboard()
    {
        require ("views/dashboard.php");
    }


    public function checkUsername(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $username = isset($input['username']) ? trim($input['username']) : '';
        
        require_once __DIR__ . '/../utils/Validator.php';
        $validation = Validator::validateUsername($username);
        
        if (!$validation['valid']) 
        {
            echo json_encode(['available' => false, 'error' => $validation['error']]);
            exit;
        }
        try 
        {
            $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
   
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['available' => $result['count'] === 0]);
            
        }
        catch (PDOException $e)
        {
            echo json_encode(['available' => false, 'error' => 'database_error']);
        }
        
        exit;
    }
}