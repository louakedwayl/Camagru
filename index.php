<?php
declare(strict_types=1);

$DSN = "mysql:host=database;dbname=camagru_db";
$USER = "camagru_user";
$PASS = "vDieYHfb70cjHl8U";

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
    case 'register':
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->register();
        break;

    case 'login':
        require 'controller/UserController.php';
        $controller = new UserController();
        $controller->login();
        break;

    default:
        require 'views/passwordreset.php';
        break;
}
