<?php
// config/setup.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/UserModel.php';


const CAPTIONS = [
    1 => "Where tradition meets the heat of the moment. Captured the raw energy of the kitchen tonight—sharp blades, rising smoke, and unmatched focus. This is where the real magic happens.",
    2 => "Witnessing centuries of tradition in every swing. Pelota is more than just a sport; it's a masterclass in precision and power. Seeing the players move with such grace and speed against the massive concrete fronton is a reminder of how deep these roots go. Truly an unforgettable experience.",
    3 => "The art of the wood-fired pizza perfected. There is no substitute for the smoky aroma of a traditional brick oven and dough that has been handled with care. From the roaring fire to the fresh ingredients on the table, this is dinner exactly as it was meant to be.",
    4 => "Chasing high scores and neon dreams in a corner of the city that time forgot. There is something timeless about the hum of a pinball machine and the dim glow of a dive bar. It is the perfect escape from the digital world, one silver ball at a time.",
    5 => "High in the Andes, where the earth meets the sky and the locals are as curious as the travelers. Watching these graceful creatures graze against a backdrop of snow-capped mountains is an experience that words can hardly describe. This is the heart of the mountains, untouched and perfectly at peace.",
    6 => "Golden light and the scent of ripe apples filling the air. This is the heart of the harvest season, where every tree tells a story of patience and growth. A quiet moment to appreciate the vibrant colors and the fresh, open landscape.",
    7 => "A masterclass in defensive timing and offensive drive. In a high-stakes match like this, the margin for error is nonexistent. Capturing that split second where balance and power collide, proving once again why this is the world's most captivating game.",
    8 => "A quiet chapter shared in the heart of the hills. There is a timeless quality to an afternoon spent with a loyal companion and a good book, especially with a backdrop as storied as this. Some friendships are written in the landscape, built on silent understanding and the simple joy of being present in the moment.",
    9 => "A true feast for the senses where the options are as endless as the appetite. There is a unique kind of communal energy in a crowded buffet line, where everyone is on their own personal quest for the perfect plate. From savory classics to new discoveries, it is a celebration of variety and plenty.",
    10 => "Behind the scenes where the real magic happens. A dedicated team working in perfect harmony, turning raw ingredients into a series of coordinated masterpieces. The energy of a professional kitchen is unlike anything else, driven by passion, timing, and a relentless pursuit of perfection.",
    11 => "High stakes and higher altitude under the bright lights of the arena. The energy of a packed stadium is concentrated into this single contest at the rim, where timing and verticality meet. It is a battle of inches played out in mid-air, fueled by the roar of the crowd and the relentless spirit of competition."
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
                        ':cap'  => CAPTIONS[$i]
                    ]);
                }
            } 

        }
    }
}
catch (Exception $e) {
}