<?php
// config/setup.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/UserModel.php';


const CAPTIONS = [
    1 => "A frozen stage for raw connection. Blades strike, time slows, and balance becomes instinct. Two bodies in sync, carving stories into the ice.",
    2 => "Beneath the surface, it’s all control and endurance. Precision in every stroke, power in every turn. Competition strips it down to what truly matters.",
    3 => "The art of the wood-fired pizza perfected. There is no substitute for the smoky aroma of a traditional brick oven and dough that has been handled with care. From the roaring fire to the fresh ingredients on the table, this is dinner exactly as it was meant to be.",
    4 => "Witnessing centuries of tradition in every swing. Pelota is more than just a sport: it's a masterclass in precision and power. Seeing the players move with such grace and speed against the massive concrete fronton is a reminder of how deep these roots go. Truly an unforgettable experience.",
    5 => "Golden light and the scent of ripe apples filling the air. This is the heart of the harvest season, where every tree tells a story of patience and growth. A quiet moment to appreciate the vibrant colors and the fresh, open landscape.",
    6 => "Surrounded by motion and sound, they move at their own pace. A mother and her son, grounded in something deeper than the spectacle.",
    7 => "Salt in the air, focus in the eyes. One surfer, endless water, and the quiet power of the Australian coast.",
    8 => "Behind every plate lies years of practice. A Japanese chef, a humble kitchen, and the quiet intensity that turns food into experience.",
    9 => "A quiet chapter shared in the heart of the hills. There is a timeless quality to an afternoon spent with a loyal companion and a good book, especially with a backdrop as storied as this. Some friendships are written in the landscape, built on silent understanding and the simple joy of being present in the moment.",
    10 => "A masterclass in defensive timing and offensive drive. In a high-stakes match like this, the margin for error is nonexistent. Capturing that split second where balance and power collide, proving once again why this is the world's most captivating game.",
    11 => "Behind the scenes where the real magic happens. A dedicated team working in perfect harmony, turning raw ingredients into a series of coordinated masterpieces. The energy of a professional kitchen is unlike anything else, driven by passion, timing, and a relentless pursuit of perfection.",
    12 => "In the arena, control meets chaos. Players rise, defenders react, and the game unfolds like a masterclass in focus and athleticism.",
    13 => "A true feast for the senses where the options are as endless as the appetite. There is a unique kind of communal energy in a crowded buffet line, where everyone is on their own personal quest for the perfect plate. From savory classics to new discoveries, it is a celebration of variety and plenty.",
    14 => "Chasing high scores and neon dreams in a corner of the city that time forgot. There is something timeless about the hum of a pinball machine and the dim glow of a dive bar. It is the perfect escape from the digital world, one silver ball at a time.",
    15 => "Layers of green, carved with care. In the rizières, time slows, and every ripple reflects a cycle older than memory"

];

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
        
        for ($i = 1; $i <= 15; $i++) {
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
                        ':cap'  => CAPTIONS[$i]
                    ]);
                }
            } 

        }
    }
}
catch (Exception $e) {
}