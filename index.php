<?php

declare(strict_types=1);

require_once 'controllers/UserController.php';

//tester bien le formulaire dincscirptin

// mettre 10 photos de base dans le compte Wayl

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

    case 'dashboard':
        $controller->dashboard();
        break; 

    default:
        $controller->index();
        break;
}