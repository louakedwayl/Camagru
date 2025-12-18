<?php
// index.php

// Connexion à la DB (PDO)
$dsn = "mysql:host=db;dbname=camagru_db";
$user = "camagru_user";
$pass = "password";

$maxRetries = 10;
$connected = false;

for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $connected = true;
        break;
    } catch (PDOException $e) {
        echo "Waiting for DB... retry $i\n";
        sleep(2);
    }
}

if (!$connected) {
    die("DB connection failed after $maxRetries retries.");
}


// Récupération de l'action
$action = $_GET['action'] ?? 'home';

// Mini routeur
switch ($action) {
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
        require 'views/index.php';
        break;
}
