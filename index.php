<?php

declare(strict_types=1);

require_once 'controllers/UserController.php';

/*
mysql> SELECT * FROM users;

+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
| id | username | full_name    | email                      | password | validated | avatar_path | validation_code | validation_code_expires_at | reset_code | reset_code_expires_at | created_at          | updated_at          |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
|  1 | Wayl     | Wayl Louaked | louakedwayl@protonmail.com | password |         1 | NULL        | NULL            | NULL                       | NULL       | NULL                  | 2026-01-07 19:27:09 | 2026-01-07 19:27:09 |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
1 row in set (0.00 sec)
*/


//revoir bien le code  d hier 

//index login et email input erreur deja pris



// mettre 10 photos de base dans le compte Wayl


$action = $_GET['action'] ?? '';


$controller = new UserController();

// Mini routeur
switch ($action) 
{
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

    case 'check_username':
        $controller->checkUsername();
        break;
    
    case 'validate_form':
        $controller->handleRegistration();
        break;

    case 'login':
        $controller->handleLogin();
        break;

    case 'verify_code':
        $controller->verifyCode();
        break;

    default:
        $controller->index();
        break;
}