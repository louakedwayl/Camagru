<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Mailer.php';

class UserController
{
    private PDO $pdo;
    private UserModel $userModel;
    private PostModel $postModel;


    public function __construct() 
    {
        $this->pdo = Database::getConnection();
        $this->userModel = new UserModel();
        $this->postModel = new PostModel();

    }


public function searchUsers(): void
{
    header('Content-Type: application/json');
    
    $query = $_GET['q'] ?? '';
    
    if (empty($query)) {
        echo json_encode([]);
        return;
    }
    
    $users = $this->userModel->searchByUsername($query);
    echo json_encode($users);
}






/**
 * Updates user profile information.
 * 
 * This method validates and updates user data including fullname, username, 
 * email, password (optional), and notification preferences. It performs 
 * availability checks for username and email if they've changed.
 * 
 * @return void Outputs JSON response and terminates execution.
 * 
 * @method POST
 * @response 200 {bool} success True if profile updated successfully.
 * @response 400 {string} message Validation error or missing fields.
 * @response 401 {string} message User not authenticated.
 * @response 405 {string} message Method not allowed.
 * @response 409 {string} message Username or email already taken.
 * @response 500 {string} message Database error.
 */
public function updateProfile(): void
{
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }

    $userId = (int)$_SESSION['user_id'];
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $notifications = isset($_POST['notifications']) ? (int)$_POST['notifications'] : 0;

    if (empty($fullname) || empty($username) || empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    if (!Validator::validateEmail($email)['valid']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    $usernameValidation = Validator::validateUsername($username);
    if (!$usernameValidation['valid']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid username format']);
        exit;
    }

    try {
        $currentUser = $this->userModel->getUserById($userId);
        
        if ($username !== $currentUser['username']) {
            if ($this->userModel->usernameExists($username)) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Username already taken']);
                exit;
            }
        }

        if ($email !== $currentUser['email']) {
            if ($this->userModel->emailExists($email)) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Email already taken']);
                exit;
            }
        }

        $updateData = [
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'notifications' => $notifications
        ];

        if (!empty($password)) {
            $passwordValidation = Validator::validatePassword($password);
            if (!$passwordValidation['valid']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid password format']);
                exit;
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $updated = $this->userModel->updateProfile($userId, $updateData);
        
        if ($updated) {
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $fullname;
            $_SESSION['email'] = $email;
            
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}



public function uploadAvatar(): void
{
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }
    
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        exit;
    }
    
    $file = $_FILES['avatar'];
    $userId = (int)$_SESSION['user_id'];
    
    // Validation type et taille
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
        exit;
    }
    
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File too large (Max 5MB).']);
        exit;
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
    $uploadDir = __DIR__ . '/../assets/images/avatars/';
    $uploadPath = $uploadDir . $filename;
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        
        // Crop automatique en carré
        list($width, $height) = getimagesize($uploadPath);
        $size = min($width, $height);
        
        // Crée l'image source selon le type
        if ($file['type'] === 'image/jpeg') {
            $source = imagecreatefromjpeg($uploadPath);
        } elseif ($file['type'] === 'image/png') {
            $source = imagecreatefrompng($uploadPath);
        } elseif ($file['type'] === 'image/webp') {
            $source = imagecreatefromwebp($uploadPath);
        }
        
        // Crop au centre en carré
        $thumb = imagecreatetruecolor($size, $size);
        imagecopyresampled(
            $thumb, $source,
            0, 0,
            ($width - $size) / 2, ($height - $size) / 2,
            $size, $size,
            $size, $size
        );
        
        // Sauvegarde en JPEG qualité 90
        imagejpeg($thumb, $uploadPath, 90);
        imagedestroy($source);
        imagedestroy($thumb);
        
        // Continue avec le reste
        $avatarPathForDb = 'assets/images/avatars/' . $filename;
        
        try {
            $oldAvatar = $this->userModel->getUserAvatar($userId);
            
            if ($this->userModel->updateAvatar($userId, $avatarPathForDb)) {
                
                if ($oldAvatar && $oldAvatar !== 'assets/images/default-avatar.jpeg') {
                    $fullOldPath = __DIR__ . '/../' . $oldAvatar;
                    if (file_exists($fullOldPath)) {
                        unlink($fullOldPath);
                    }
                }
                echo json_encode(['success' => true, 'avatar_path' => $avatarPathForDb]);
            } else {
                throw new Exception("Update failed");
            }
        } catch (Exception $e) {
            if (file_exists($uploadPath)) unlink($uploadPath);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    }
    exit;
}


    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000, 
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php');
        exit();
    }

    public function profile() 
    {
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


        if (!isset($_SESSION['user_id']))
        {
            header('Location: index.php?action=login');
            exit();
        }

    $userId = $_SESSION['user_id'];
    $user = $this->userModel->getUserById($userId);
    $userPosts = $this->postModel->getPostsByUserId($userId);

        require 'views/profile.php';
    }


        public function updatePassword() 
        {

        $email = $_POST['email'] ?? '';
        $code = $_POST['code'] ?? '';
        $newPass = $_POST['new_password'] ?? '';


        $user = $this->userModel->getUserByResetCode($email, $code);

        if ($user) {
            $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);

            $updateSuccess = $this->userModel->updateUserPassword($email, $hashedPassword);

            if ($updateSuccess) {
                $this->userModel->clearResetCode($email);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                echo json_encode(['success' => true]);
            } 
            else 
            {
                echo json_encode(['success' => false, 'reason' => 'Database error']);
            }
        } 
        else
        {
            echo json_encode(['success' => false, 'reason' => 'Invalid or expired link']);
        }
    }



    public function password_reset_confirm() 
    {
        $email = $_GET['email'] ?? '';
        $code = $_GET['code'] ?? '';

        if ($this->userModel->verifyResetCode($email, $code))
        {
            require "views/password_reset_confirm.php";
        }
        else
        {
            header('Location: index.php?action=password_reset&error=expired');
        }
    }

    /**
     * Handles the password reset request process.
     * * This method validates the POST request, verifies the user's existence,
     * generates a secure 6-digit reset code, and dispatches it via email.
     * * Output JSON responses:
     * - success: true                 -> Email sent successfully.
     * - success: false, reason: 'throttle' -> A valid code already exists (rate limited).
     * - success: false, reason: 'error'    -> Invalid request, user not found, or mail failure.
     * * @return void Sends a JSON response and terminates script execution.
     * @throws Exception If random_int() fails to find an appropriate source of entropy.
     */
    public function sendResetPassword(): void
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            echo json_encode(['success' => false, 'reason' => 'error']);
            exit;
        }

        $login = isset($_POST['login']) ? trim($_POST['login']) : '';

        if (empty($login))
        {
            echo json_encode(['success' => false, 'reason' => 'error']);
            exit;
        }

        try 
        {
            $user = $this->userModel->getUserByLogin($login);
            
            if (!$user)
            {
                echo json_encode(['success' => false, 'reason' => 'error']);
                exit;
            }

            $resetCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $result = $this->userModel->setResetCode($login, $resetCode);

            if ($result === true)
            {
                $mailSent = Mailer::sendResetLink($user['email'], $user['username'], $resetCode);
                
                if ($mailSent) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'reason' => 'error']);
                }
            } 
            else if 
            ($result === 'limit_reached') 
            {
                echo json_encode(['success' => false, 'reason' => 'throttle']);
            } 
            else 
            {
                echo json_encode(['success' => false, 'reason' => 'error']);
            }
        }
        catch (Exception $e) 
        {
            echo json_encode(['success' => false, 'reason' => 'error']);
        }
        exit;
    }


    // --- VUES  ---

    public function index() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            http_response_code(405);
            echo "Method Not Allowed";
            exit;
        }
         require ("views/index.php"); 
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            http_response_code(405);
            echo "Method Not Allowed";
            exit;
        }
         require ("views/register.php"); 
    }

    public function password_reset()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            http_response_code(405);
            echo "Method Not Allowed";
            exit;
        }
         require ("views/password_reset.php"); 
    }

    
/**
     * Displays the email verification page.
     * * This view is the second step of registration. It requires a temporary 
     * session containing 'user_email'. Access is restricted to GET requests 
     * only; any other method returns a 405 error.
     * * @access public
     * @return void
     * * @method GET
     * @session user_email Required to identify the user being validated.
     * @response 200 Loads the email_signup view.
     * @response 302 Redirects to index.php if no email is found in session.
     * @response 405 Returns "Method Not Allowed" for non-GET requests.
     */
    public function email_signup()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            http_response_code(405);
            echo "Method Not Allowed";
            exit;
        }

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['user_email']))
        {
            header('Location: index.php');
            exit;
        }

        require ("views/email_signup.php");
        exit;
    }

    // --- API ---

/**
     * Checks username availability.
     * * This method verifies the username format, checks if it already exists 
     * in the database, and returns a JSON response. The process is always 
     * terminated by an exit call to ensure a clean JSON output.
     * * @return void
     * * @response 200 {bool}   available True if the username is free, false if taken.
     * @response 400 {string} error     Returned if the username format is invalid.
     * @response 405 {string} error     Returned if the request method is not POST.
     * @response 500 {string} error     Returned in case of a database exception.
     */
    public function checkUsername(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405); 
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
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

/**
     * Checks email availability.
     *
     * This method validates the email format, checks for its existence in the 
     * database, and outputs a JSON response. It terminates script execution 
     * using `exit` to ensure no additional data is appended to the response.
     *
     * @access public
     * @return void
     * * @method POST
     * * @response 200 {bool}   available True if the email is free to use, false otherwise.
     * @response 400 {string} error     Returned if the email format is invalid.
     * @response 405 {string} error     Returned if the request method is not POST.
     * @response 500 {string} error     Returned in case of a server or database failure.
     */
    public function checkEmail(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405); 
            echo json_encode(['available' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        
        $validation = Validator::validateEmail($email);
        if (!$validation['valid'])
        {
            http_response_code(400);
            echo json_encode(['available' => false, 'error' => $validation['error']]);
            exit;
        }
        
        try
        {
            $exist = $this->userModel->emailExists($email);
            echo json_encode(['available' => !$exist]);
        } 
        catch (PDOException $e)
        {
            http_response_code(500);
            echo json_encode(['available' => false, 'error' => 'database_error']);
        }
        exit;
    }

    /**
     * Resends a validation code to the user's email.
     * * This API endpoint generates a new 6-digit code, updates the database, 
     * and sends it via email. It enforces a 60-second cooldown between 
     * requests using sessions to prevent abuse.
     * * @return void
     * * @method POST
     * @response 200 {bool} success  True if the code was sent.
     * @response 400 {string} error  Missing or expired session.
     * @response 405 {string} error  Method not allowed (must be POST).
     * @response 429 {string} error  Too many requests (cooldown active).
     * @response 500 {string} error  Database or Mail server failure.
     */
    public function resendCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }
        
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_email'])) 
        {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Session expired']);
            exit;
        }

        if (isset($_SESSION['last_resend_time']) && (time() - $_SESSION['last_resend_time'] < 60)) 
        {
            http_response_code(429);
            echo json_encode(['success' => false, 'error' => 'Please wait before retrying.']);
            exit;
        }

        try 
        {
            $email = $_SESSION['user_email'];
            $username = $_SESSION['username'] ?? 'User'; 

            $newCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $updated = $this->userModel->updateValidationCode($email, $newCode);
            if (!$updated) throw new Exception("DB Error");

            $mailSent = Mailer::sendValidationCode($email, $username, $newCode);
            
            if ($mailSent) 
            {
                $_SESSION['last_resend_time'] = time();
                $_SESSION['code'] = $newCode;
                echo json_encode(['success' => true]);
            } 
            else
            {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Mail server error']);
            }
        }
        catch (Exception $e)
        {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Server error']);
        }
        exit;
    }

/**
     * Processes the user registration request.
     * * This API endpoint validates input data, checks for existing 
     * credentials (email/username), creates a new user with a hashed 
     * password, initializes a secure session, and triggers the 
     * validation email.
     * * @return void
     * * @method POST
     * @response 200 {bool}   valid   True if registration is successful.
     * @response 400 {string} error   Missing fields or invalid email format.
     * @response 405 {string} error   Method not allowed (GET instead of POST).
     * @response 409 {string} error   Email or username already exists.
     * @response 500 {string} error   Database failure or user creation error.
     */
    public function handleRegistration(): void
    {
        header('Content-Type: application/json');
 
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            echo json_encode(['valid' => false, 'error' => 'method_not_allowed']);
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        if (empty($email) || empty($password) || empty($fullname) || empty($username))
        {
            echo json_encode(['valid' => false, 'error' => 'missing_fields']);
            exit;
        }
        if (!Validator::validateEmail($email)['valid']) 
        {
            echo json_encode(['valid' => false, 'error' => 'Invalid email']);
            exit;
        }

        try
        {
            if ($this->userModel->emailExists($email))
            {
                http_response_code(409);
                echo json_encode(['valid' => false, 'error' => 'email_taken']);
                exit;
            }
            if ($this->userModel->usernameExists($username))
            {
                http_response_code(409);
                echo json_encode(['valid' => false, 'error' => 'username_taken']);
                exit;
            }
            
            $validationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $userExist = $this->userModel->create($username, $fullname, $email, $password, $validationCode);
            
            if ($userExist)
            {
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true);

                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = $fullname;
                $_SESSION['code'] = $validationCode;
                $_SESSION['last_resend_time'] = time();

                Mailer::sendValidationCode($email, $username, $validationCode);
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
            http_response_code(500);
            echo json_encode(['valid' => false, 'error' => 'database_error']);
        }
        exit;
    }

/**
     * Verifies the 6-digit security code and activates the user session.
     * * This API endpoint validates the submitted code format, checks it 
     * against the database (including expiration), and performs the 
     * login transition by upgrading the session from 'pending' to 'authenticated'.
     * * @return void
     * * @method POST
     * @response 200 {bool} success True if code is valid and user is logged in.
     * @response 200 {bool} success False with error 'expired', 'invalid', or 'no_pending_validation'.
     * @response 405 {string} error Method not allowed.
     */
    public function verifyCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            echo json_encode(['success' => false, 'error' => 'method_not_allowed']); 
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();


        if (!isset($_SESSION['user_email']))
        {
            echo json_encode(['success' => false, 'error' => 'no_pending_validation']);
            exit;
        }

        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        if (empty($code) || !preg_match('/^\d{6}$/', $code))
        {
            echo json_encode(['success' => false, 'error' => 'invalid_code_format']);
            exit;
        }

        try
        {
            $status = $this->userModel->validateUser($_SESSION['user_email'], $code);

            if ($status === 'success')
            {
                $user = $this->userModel->getUserByLogin($_SESSION['user_email']);
                
                session_regenerate_id(true);

                unset($_SESSION['user_email']); 
                unset($_SESSION['code']);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                
                echo json_encode(['success' => true]);
            } 
            else if ($status === 'expired')
            {
                echo json_encode(['success' => false, 'error' => 'expired']);
            }
            else
            {
                echo json_encode(['success' => false, 'error' => 'invalid']);
            }
        }
        catch (Exception $e)
        {
            echo json_encode(['success' => false, 'error' => 'server_error']);
        }
        exit;
    }

/**
     * Verifies the user via a URL magic link.
     * * This method handles the automatic validation and login process when 
     * a user clicks a link in their email. It validates the email/code 
     * pair, upgrades the session, and redirects the user to the home 
     * or back to the signup page if the link is expired.
     * * @return void
     * * @method GET
     * @query string email The user's email address from the URL.
     * @query string code  The unique validation token from the URL.
     * @response 302 Redirects to home on success.
     * @response 302 Redirects to email_signup on expiration.
     * @response 302 Redirects to index with error on failure.
     */
    public function verifyUrl(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = $_GET['email'] ?? '';
        $code  = $_GET['code'] ?? '';

        if (empty($email) || empty($code))
        {
            header('Location: index.php?error=invalid_link'); 
            exit;
        }

        try
        {
            $status = $this->userModel->validateUser($email, $code);

            if ($status === 'success')
            {
                session_regenerate_id(true); 

                $user = $this->userModel->getUserByLogin($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                
                unset($_SESSION['user_email']);
                unset($_SESSION['code']);
                
                header('Location: index.php?action=home');
                exit;
            } 
            else if ($status === 'expired')
            {
                $_SESSION['user_email'] = $email;
                header('Location: index.php?action=email_signup&error=timeout');
                exit;
            } 
            else
            {
                header('Location: index.php?action=email_signup&error=invalid');
                exit;
            }
        }
        catch (Exception $e)
        {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            header('Location: /login');
            exit;
        }

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
        echo json_encode(["success" => true, "redirect" => "index.php?action=home"]);
        exit;
    }
}