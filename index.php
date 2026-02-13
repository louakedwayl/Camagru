<?php

declare(strict_types=1);

require_once 'controllers/UserController.php';
require_once 'controllers/PostController.php';
require_once 'controllers/ReportController.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_setup_done'])) 
{
    require_once 'config/setup.php';
    $_SESSION['admin_setup_done'] = true;
}

$action = $_GET['action'] ?? '';


// TO DO LIST //

/*
    faire kon puisse clicker sur les image de profile partout en visitoor et pas (seulemntn dans home ia besoin)
    Terminer 

    faire les likes et les comments 

    faire les notif et le mail

    enlever le carret violet dans create

    Norm 

    relire tt le code 

    Push

*/





$user_controller = new UserController();
$post_controller = new PostController();
$report_controller = new ReportController();


// a2enmod rewrite

// Mini routeur
switch ($action) 
{
        // API
    case 'check_username':
        $user_controller->checkUsername();
        break;
    
    case 'check_email':
        $user_controller->checkEmail();
        break;

    case 'registration':
        $user_controller->handleRegistration();
        break;

    case 'login':
        $user_controller->handleLogin();
        break;

    case 'resend_code':
        $user_controller->resendCode();
        break;

    case 'verify_url':
        $user_controller->verifyUrl();
        break;
    
    case 'verify_code':
        $user_controller->verifyCode();
        break;

    case 'update_password':
        $user_controller->updatePassword();
        break;

    case 'logout':
        $user_controller->logout();
        break;

    case 'upload_avatar':
        $user_controller->uploadAvatar();
        break;

    case 'update_profile':
        $user_controller->updateProfile();
        break;

    case 'search_users':
        $user_controller->searchUsers();
        break;

    case 'user_profile':
        $user_controller->userProfile();
        break;

    case 'submit_report':
        $report_controller->submitReport();
        break;

    case 'post':
        $post_controller->showPost();
        break;

    case 'get_stickers':
        $post_controller->getStickers();
        break;
    case 'capture':
        $post_controller->capture();
        break;
    case 'delete_post':
        $post_controller->deletePostAction();
        break;

    case 'toggle_like':
        $post_controller->toggleLike();  // ✅
        break;

    case 'add_comment':
        $post_controller->addCommentAction();
        break;

    // Views
    case 'email_signup':
        $user_controller->email_signup();
        break;

    case 'register':
        $user_controller->register();
        break;

    case 'password_reset':
        $user_controller->password_reset();
        break;

    case 'password_reset_confirm':
        $user_controller->password_reset_confirm();
        break;


    case 'home':
        $post_controller->home();
        break; 

    case 'create':
        $post_controller->create();
        break;

    case 'profile':
        $user_controller->profile();
        break;

    case 'explore':
        $post_controller->explore();
        break;

    case 'send_reset_password' :
        $user_controller->sendResetPassword();
        break; 

    case 'test':
        include 'views/password_reset_confirm.php';
        break; 

    case 'visitor_home':
        $post_controller->homeVisitor();
        break;
    case 'visitor_explore':
        $post_controller->exploreVisitor();
        break;
    case 'visitor_post':
        $post_controller->showPostVisitor();
        break;
    case 'visitor_profile':
        $user_controller->visitorProfile();
        break;
    default:
        $user_controller->index();
        break;
}