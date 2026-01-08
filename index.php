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

// Mini routeur
switch ($action) 
{
    case 'email_signup':
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->email_signup();
        break;

    case 'register':
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->register();
        break;

    case 'password_reset':
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->password_reset();
        break;

    default:
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->index();
        break;
}
