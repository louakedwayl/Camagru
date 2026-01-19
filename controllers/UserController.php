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

    // --- VUES (Pages HTML) ---

    public function index() { require ("views/index.php"); }
    public function register() { require ("views/register.php"); }
    public function password_reset() { require ("views/password_reset.php"); }

    public function email_signup()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // On affiche cette page SEULEMENT si l'utilisateur est en attente de validation
        if (!isset($_SESSION['user_email'])) {
            header('Location: index.php');
            exit;
        }
        require ("views/email_signup.php");
        exit;
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // On affiche cette page SEULEMENT si l'utilisateur est connecté (ID présent)
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        require ("views/dashboard.php");
    }

    // --- API & LOGIQUE ---

    public function checkUsername(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); 
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        // CORRECTION ICI : On utilise $_POST pour être cohérent avec le reste
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        
        $validation = Validator::validateUsername($username);
        if (!$validation['valid']) {
            http_response_code(400); // Bad Request
            echo json_encode(['available' => false, 'error' => $validation['error']]);
            exit;
        }

        try {
            $exist = $this->userModel->usernameExists($username);
            echo json_encode(['available' => !$exist]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['available' => false, 'error' => 'database_error']);
        }
        exit;
    }

    public function checkEmail(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); 
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        // CORRECTION ICI : On utilise $_POST
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        
        $validation = Validator::validateEmail($email);
        if (!$validation['valid']) {
            http_response_code(400);
            echo json_encode(['available' => false, 'error' => $validation['error']]);
            exit;
        }
        
        try {
            $exist = $this->userModel->emailExists($email);
            echo json_encode(['available' => !$exist]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['available' => false, 'error' => 'database_error']);
        }
        exit;
    }

    // 🔄 RENVOYER LE CODE (AJAX)
    public function resendCode(): void
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }

        // Vérif : L'utilisateur doit être en attente (user_email)
        if (!isset($_SESSION['user_email'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Session expired']);
            exit;
        }

        // Vérif : Timer (60 secondes)
        if (isset($_SESSION['last_resend_time']) && (time() - $_SESSION['last_resend_time'] < 60)) {
            http_response_code(429);
            echo json_encode(['success' => false, 'error' => 'Please wait before retrying.']);
            exit;
        }

        try {
            $email = $_SESSION['user_email'];
            $username = $_SESSION['username'] ?? 'User'; 

            // Génération nouveau code
            $newCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Update DB (Code + Expiration)
            $updated = $this->userModel->updateValidationCode($email, $newCode);
            if (!$updated) throw new Exception("DB Error");

            // Envoi Mail
            $mailSent = Mailer::sendValidationCode($email, $username, $newCode);
            
            if ($mailSent) {
                $_SESSION['last_resend_time'] = time();
                $_SESSION['code'] = $newCode;
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Mail server error']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Server error']);
        }
        exit;
    }

    // 📝 INSCRIPTION (Formulaire)
    public function handleRegistration(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['valid' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        header('Content-Type: application/json');

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        // Validations simplifiées
        if (empty($email) || empty($password) || empty($fullname) || empty($username)) {
            echo json_encode(['valid' => false, 'error' => 'missing_fields']); exit;
        }
        if (!Validator::validateEmail($email)['valid']) {
             echo json_encode(['valid' => false, 'error' => 'Invalid email']); exit; 
        }

        try {
            if ($this->userModel->emailExists($email)) {
                http_response_code(409);
                echo json_encode(['valid' => false, 'error' => 'email_taken']); exit;
            }
            if ($this->userModel->usernameExists($username)) {
                http_response_code(409);
                echo json_encode(['valid' => false, 'error' => 'username_taken']); exit;
            }
            
            $validationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $userExist = $this->userModel->create($username, $fullname, $email, $password, $validationCode);
            
            if ($userExist) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true);

                // Session "EN ATTENTE"
                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = $fullname;
                $_SESSION['code'] = $validationCode;
                $_SESSION['last_resend_time'] = time();

                Mailer::sendValidationCode($email, $username, $validationCode);
                echo json_encode(['valid' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['valid' => false, 'error' => 'creation_failed']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['valid' => false, 'error' => 'database_error']);
        }
        exit;
    }

    // ✅ VALIDATION PAR CODE (Formulaire JS - POST - JSON)
    public function verifyCode(): void
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'method_not_allowed']); exit;
        }

        if (!isset($_SESSION['user_email'])) {
            echo json_encode(['success' => false, 'error' => 'no_pending_validation']); exit;
        }

        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        if (empty($code) || !preg_match('/^\d{6}$/', $code)) {
            echo json_encode(['success' => false, 'error' => 'invalid_code_format']); exit;
        }

        try {
            $status = $this->userModel->validateUser($_SESSION['user_email'], $code);

            if ($status === 'success') {
                $user = $this->userModel->getUserByLogin($_SESSION['user_email']);
                
                session_regenerate_id(true);

                // TRANSITION : On nettoie l'attente et on connecte
                unset($_SESSION['user_email']); 
                unset($_SESSION['code']);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email']; // Clé 'email' pour le dashboard
                
                echo json_encode(['success' => true]);
            } 
            else if ($status === 'expired') {
                echo json_encode(['success' => false, 'error' => 'expired']);
            } else {
                echo json_encode(['success' => false, 'error' => 'invalid']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'server_error']);
        }
        exit;
    }

    // 🔗 VALIDATION PAR LIEN EMAIL (Navigateur - GET - REDIRECTION)
    public function verifyUrl(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = $_GET['email'] ?? '';
        $code  = $_GET['code'] ?? '';

        if (empty($email) || empty($code)) {
            header('Location: index.php?error=invalid_link'); 
            exit;
        }

        try {
            $status = $this->userModel->validateUser($email, $code);

            if ($status === 'success') {
                $user = $this->userModel->getUserByLogin($email);
                

                session_regenerate_id(true); 
                // CONNEXION DIRECTE
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                
                // Nettoyage si besoin
                unset($_SESSION['user_email']);
                unset($_SESSION['code']);
                
                header('Location: index.php?action=dashboard');
                exit;
            } 
            else if ($status === 'expired') {
                // On met 'user_email' pour permettre le "Resend Code"
                $_SESSION['user_email'] = $email;
                header('Location: index.php?action=email_signup&error=expired');
                exit;
            } 
            else {
                header('Location: index.php?error=invalid_token');
                exit;
            }
        } catch (Exception $e) {
            header('Location: index.php?error=server');
            exit;
        }
    }


    /**
     * Handles user authentication.
     * * This method verifies login credentials (email/username and password),
     * secures the session against session fixation attacks, and manages
     * redirection based on the account's validation status.
     * * @return void Outputs a JSON response and terminates execution via exit.
     * * @response bool   success  Indicates if the authentication was successful.
     * @response string message  Error message in case of failure.
     * @response string redirect Destination URL on success.
     */
    public function handleLogin()
    {
        header('Content-Type: application/json');
        
        if (empty($_POST['login']) || empty($_POST['password']))
        {
            echo json_encode(["success" => false, "message" => "Missing fields"]);
            exit;
        }

        $login = $_POST['login'];
        $password = $_POST['password'];

        $user = $this->userModel->getUserByLogin($login);
        if (!$user || !password_verify($password, $user['password']))
        {
            echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE)
            session_start();
        session_regenerate_id(true); 

        if ($user['validated'] == 0) 
        {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['code'] = $user['validation_code'];
            
            echo json_encode(["success" => true, "redirect" => "index.php?action=email_signup"]);
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        echo json_encode(["success" => true, "redirect" => "index.php?action=dashboard"]);
        exit;
    }
}