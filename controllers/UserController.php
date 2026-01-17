<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Mailer.php';

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si l'email n'est pas en session, l'utilisateur n'a rien à faire ici
        if (!isset($_SESSION['user_email']))
        {
            header('Location: index.php');
            exit;
        }

        // Si c'est bon, on affiche la vue
        require ("views/email_signup.php");
        exit; // On s'arrête proprement après l'affichage
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Protection : Si pas connecté, retour accueil
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
             // Note: Tu pourras affiner cette condition selon ta logique de connexion
             // Pour l'instant on laisse accessible si besoin de dev
        }
        
        require ("views/dashboard.php");
    }

    public function checkUsername(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405); 
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

        // --- Validations ---
        if (empty($email) || empty($password) || empty($fullname) || empty($username)) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => 'missing_fields']);
            exit;
        }

        $emailValidation = Validator::validateEmail($email);
        if (!$emailValidation['valid']) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $emailValidation['error']]);
            exit;
        }

        $passwordValidation = Validator::validatePassword($password);
        if (!$passwordValidation['valid']) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $passwordValidation['error']]);
            exit;
        }

        $fullnameValidation = Validator::validateFullname($fullname);
        if (!$fullnameValidation['valid']) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'error' => $fullnameValidation['error']]);
            exit;
        }

        $usernameValidation = Validator::validateUsername($username);
        if (!$usernameValidation['valid']) {
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
            $validationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // 1. Création de l'utilisateur
            $newUser = $this->userModel->create($username, $fullname, $email, $password, $validationCode);
            
            if ($newUser)
            {
                // Gestion de la session
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true); // Sécurité anti-vol de session

                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['code'] = $validationCode;
                

                // 2. Envoi du mail
                $code = $this->userModel->getValidationCode($email);
                
                if ($code) {
                    // Appel statique au Mailer
                    $mailSent = Mailer::sendValidationCode($email, $username, $code);
                    
                    if (!$mailSent) {
                        // On log l'erreur pour le debug (Docker logs)
                        error_log("MAIL ERROR: Impossible d'envoyer le code à $email");
                    } else {
                         error_log("MAIL SUCCESS: Code envoyé à $email");
                    }
                }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($_SESSION['user_email']))
        {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'no_pending_validation']);
            exit;
        }

        $code = isset($_POST['code']) ? trim($_POST['code']) : '';

        // Validation format code (6 chiffres)
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
                // Succès : On nettoie la variable temporaire
                unset($_SESSION['user_email']);
                
                // TODO: Ici tu peux décider de connecter l'utilisateur directement
                // $_SESSION['user_id'] = ...
                
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



    public function handleLogin()
    {
        if (empty($_POST['login']) || empty($_POST['password']))
        {
            echo json_encode(["success" => false, "message" => "Champs manquants"]);
            exit;
        }

        $login = $_POST['login'];
        $password = $_POST['password'];

        $user = $this->userModel->getUserByEmail($login);

    echo json_encode([
        "success" => false, // On force l'erreur pour lire le message
        "debug_user_found" => $user ? "OUI" : "NON",
        "debug_password_input" => $password, // Ce que tu as tapé
        "debug_hash_db" => $user['password'], // Ce qu'il y a en base
        "debug_verify_test" => password_verify($password, $user['password']) ? "TRUE" : "FALSE"
    ]);
    exit; 

        if (!$user || !password_verify($password, $user['password']))
        {
            echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
            exit;
        }

        if ($user['validated'] == 0) 
        {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_email'] = $user['email']; 

            echo json_encode([
                "success" => false, 
                "error_code" => "not_validated", 
                "redirect" => "index.php?action=email_signup"
            ]);
            exit;
        }
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        echo json_encode([
            "success" => true,
            "redirect" => "index.php?action=dashboard"
        ]);
        exit;
    }
}