<?php

declare(strict_types=1);

/*
mysql> SELECT * FROM users;

+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
| id | username | full_name    | email                      | password | validated | avatar_path | validation_code | validation_code_expires_at | reset_code | reset_code_expires_at | created_at          | updated_at          |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
|  1 | Wayl     | Wayl Louaked | louakedwayl@protonmail.com | password |         1 | NULL        | NULL            | NULL                       | NULL       | NULL                  | 2026-01-07 19:27:09 | 2026-01-07 19:27:09 |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
1 row in set (0.00 sec)
*/


// Récupération de l'action
$action = $_GET['action'] ?? 'home';

require 'controller/UserController.php';
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

    case 'check_username':
        $controller->checkUsername();
        break;

    default:
        $controller->index();
        break;
}