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

// 2  bind mount 
// 3 metre dans .env ,


// Charger les variables d'environnement
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorer les commentaires
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'"); // Enlever espaces et guillemets
        $_ENV[$key] = $value;
    }
}

// Définir les variables depuis .env
$DSN = $_ENV['DSN'] ?? '';
$USER = $_ENV['USER'] ?? '';
$PASS = $_ENV['PASS'] ?? '';


$maxRetries = 3;
$connected = false;

for ($i = 0; $i < $maxRetries; $i++) 
{
    try 
    {
        $pdo = new PDO($DSN, $USER, $PASS);
        $connected = true;
        break;
    } 
    catch (PDOException $e) 
    {
        echo "Waiting for DB... retry $i\n";
        echo "=== Erreur PDO ===\n";
        echo "Message : " . $e->getMessage() . "\n";
        echo "Code : " . $e->getCode() . "\n";
        echo "Fichier : " . $e->getFile() . "\n";
        echo "Ligne : " . $e->getLine() . "\n";
        echo "Trace : \n" . $e->getTraceAsString() . "\n";
        sleep(2);
    }
}

if (!$connected) {
    die("DB connection failed after $maxRetries retries.");
}


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
