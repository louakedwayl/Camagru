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


// recheck les fichier 
// metre un listener sur input
// verifier en backend
// cree le model 
// inscrire dans la db  en unregistred
// passer a lecran de code

// generer un code 
// envoyer le code par mail 
// apres code acces au dashboard

// envoyer le form de lindex 
// si pas inscript message derror 
// si inscrit et registred dashboard 
// si inscrit et pas registred -> page de verif demail

// mettre 10 photos de base dans le compte Wayl

// dashboard faire l'effet de changement dicone black white
// coder de le front de profile 
// faire les fetch

// faire le search

// Récupération de l'action

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
    
    case 'user_validation':
        $controller->user_validation();
        break;

    case 'create_user':
        $controller->checkUsername();
        break;

    default:
        $controller->index();
        break;
}