<?php
// config/setup.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

try 
{
    $pdo = Database::getConnection();
    $userModel = new UserModel();

    $adminUser  = getenv('ADMIN_USER_NAME');
    $adminPass  = getenv('ADMIN_USER_PASS');
    $adminEmail = getenv('ADMIN_USER_EMAIL');

    echo "--- Setup Camagru (Admin Configuration) ---\n";

    if (!$userModel->usernameExists($adminUser))
    {
        $success = $userModel->create(
            $adminUser, 
            'Wayl Louaked', 
            $adminEmail, 
            $adminPass, 
            '000000'
        );
        
        if ($success) 
        {
            $sql = "UPDATE users SET validated = 1, validation_code = NULL WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':username' => $adminUser]);
            echo "[OK] Administrateur '$adminUser' créé avec succès.\n";
        }
    } 
    else 
    {
        echo "[INFO] L'utilisateur admin '$adminUser' existe déjà.\n";
    }
}
catch (Exception $e)
{
    echo "[ERREUR] " . $e->getMessage() . "\n";
}