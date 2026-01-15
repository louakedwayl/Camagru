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
        session_start(); // On démarre la session
        // On vérifie si l'email est là (mis par validateForm juste avant)
        if (!isset($_SESSION['user_email']))
        {
            // Si pas d'email en session, on renvoie à l'accueil
            header('Location: index.php');
            exit;
        }
        // Si c'est bon, on affiche la vue
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

    public function handleRegistration(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['valid' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        header('Content-Type: application/json');

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        if (empty($email) || empty($password) || empty($fullname) || empty($username))
        {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => 'missing_fields']);
            exit;
        }

        $emailValidation = Validator::validateEmail($email);
        if (!$emailValidation['valid'])
        {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $emailValidation['error']]);
            exit;
        }

        $passwordValidation = Validator::validatePassword($password);
        if (!$passwordValidation['valid'])
        {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $passwordValidation['error']]);
            exit;
        }

        $fullnameValidation = Validator::validateFullname($fullname);
        if (!$fullnameValidation['valid'])
        {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $fullnameValidation['error']]);
            exit;
        }

        $usernameValidation = Validator::validateUsername($username);
        if (!$usernameValidation['valid'])
        {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $usernameValidation['error']]);
            exit;
        }

        try
        {
            if ($this->userModel->usernameExists($username))
            {
                http_response_code(409);
                echo json_encode(['valid' => false, 'error' => 'username_taken']);
                exit;
            }

            $newUser = $this->userModel->create($username, $fullname, $email, $password);
            if ($newUser)
            {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();
                
                session_regenerate_id(true);

                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                echo json_encode(['valid' => true]);
            }
            else 
            {
                http_response_code(500);
                echo json_encode(['valid' => false, 'error' => 'creation_failed']);
            }
        }
        catch (PDOException $e)
        {
            error_log("Error validating form: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['valid' => false, 'error' => 'database_error']);
        }
        exit;
    }


  public function verifyCode(): void
    {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_email']))
        {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'no_pending_validation']);
            exit;
        }

        $code = isset($_POST['code']) ? trim($_POST['code']) : '';

        if (empty($code) || !preg_match('/^\d{6}$/', $code))
        {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'invalid_code_format']);
            exit;
        }

        try
        {
            $validated = $this->userModel->validateUser($_SESSION['user_email'], $code);

            if ($validated)
            {
                unset($_SESSION['user_email']);
                echo json_encode(['success' => true]);
            }
            else
            {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'invalid_or_expired_code']);
            }
        }
        catch (Exception $e)
        {
            error_log("Error verifying code: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'server_error']);
        }
        exit;
    }




    // Méthode pour envoyer l'email de validation (à implémenter)
    private function sendValidationEmail(string $email, string $code): bool
    {
        // TODO: Implémenter l'envoi d'email avec PHPMailer ou similar
        // Pour le moment, log le code pour le développement
        error_log("Code de validation pour $email : $code");
        return true;
    }
}


