<?php

declare(strict_types=1);

require_once 'controllers/UserController.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_setup_done'])) 
{
    require_once 'config/setup.php';
    $_SESSION['admin_setup_done'] = true;
}

$action = $_GET['action'] ?? '';

$controller = new UserController();

// a2enmod rewrite

// Mini routeur
switch ($action) 
{
        // API
    case 'check_username':
        $controller->checkUsername();
        break;
    
    case 'check_email':
        $controller->checkEmail();
        break;

    case 'registration':
        $controller->handleRegistration();
        break;

    case 'login':
        $controller->handleLogin();
        break;

    case 'resend_code':
        $controller->resendCode();
        break;

    case 'verify_url':
        $controller->verifyUrl();
        break;
    
    case 'verify_code':
        $controller->verifyCode();
        break;

    case 'update_password':
        $controller->updatePassword();
        break;

    // Views
    case 'email_signup':
        $controller->email_signup();
        break;

    case 'register':
        $controller->register();
        break;

    case 'password_reset':
        $controller->password_reset();
        break;

    case 'password_reset_confirm':
        $controller->password_reset_confirm();
        break;


    case 'home':
        $controller->home();
        break; 


    case 'send_reset_password' :
        $controller->sendResetPassword();
        break; 

    case 'test':
        include 'views/password_reset_confirm.php';
        break; 

    default:
        $controller->index();
        break;
}