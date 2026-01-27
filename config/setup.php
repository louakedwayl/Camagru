<?php
// config/setup.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

try 
{
    $pdo = Database::getConnection();
    $userModel = new UserModel();

    $adminUser  = getenv('ADMIN_USER_NAME') ?: 'Wayl';
    $adminPass  = getenv('ADMIN_USER_PASS') ?: 'password123';
    $adminEmail = getenv('ADMIN_USER_EMAIL') ?: 'louakedwayl@protonmail.com';

    if (!$userModel->usernameExists($adminUser))
    {
        $created = $userModel->create($adminUser, 'Wayl Louaked', $adminEmail, $adminPass, '000000');
        if ($created) 
        {
            $pdo->prepare("UPDATE users SET validated = 1, validation_code = NULL WHERE username = ?")
                ->execute([$adminUser]);
        }
    } 

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$adminUser]);
    $userId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $postCount = $stmt->fetchColumn();

    if ($postCount == 0) {
        
        for ($i = 1; $i <= 11; $i++) {
            $ext = 'jpg';
            $sourcePath = __DIR__ . "/../assets/images/placeholders/demo$i.$ext";
            
            if (!file_exists($sourcePath)) {
                $ext = 'png'; // tentative en png
                $sourcePath = __DIR__ . "/../assets/images/placeholders/demo$i.$ext";
            }

            if (file_exists($sourcePath)) {
                $newFileName = "demo_post_" . $i . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                $destPath = __DIR__ . '/../public/uploads/posts/' . $newFileName;

                if (copy($sourcePath, $destPath)) {
                    $sql = "INSERT INTO posts (user_id, image_path, caption) VALUES (:uid, :path, :cap)";
                    $pdo->prepare($sql)->execute([
                        ':uid'  => $userId,
                        ':path' => 'public/uploads/posts/' . $newFileName,
                        ':cap'  => "Photo de démo n°$i"
                    ]);
                }
            } 

        }
    }
}
catch (Exception $e) {
}