<?php
// index.php

// Connexion à la DB (PDO)
$dsn = "mysql:host=db;dbname=camagru_db";
$user = "camagru_user";
$pass = "password";
try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
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
