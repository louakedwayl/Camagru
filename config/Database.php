<?php

declare(strict_types=1);

class Database 
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO 
    {
        if (self::$pdo !== null)
        {
            return self::$pdo;
        }

        // ... (Ton code de chargement .env est très bien, je le garde tel quel) ...
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }

        $DSN = $_ENV['DSN'] ?? '';
        $USER = $_ENV['USER'] ?? '';
        $PASS = $_ENV['PASS'] ?? '';

        // ✅ C'EST ICI QU'IL FAUT AJOUTER LES OPTIONS
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // <--- OBLIGATOIRE POUR ÉVITER LE 0
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Optionnel : retourne des tableaux associatifs propres
            PDO::ATTR_EMULATE_PREPARES => false, // Optionnel : Meilleure sécurité
        ];

        $maxRetries = 3;
        $connected = false;
        
        for ($i = 0; $i < $maxRetries; $i++)
        {
            try 
            {
                // ✅ On ajoute $options en 4ème argument
                self::$pdo = new PDO($DSN, $USER, $PASS, $options);
                $connected = true;
                break;
            }
            catch (PDOException $e)
            {
                // C'est bien de catch ici pour la CONNEXION (retry)
                // Mais grâce à ERRMODE_EXCEPTION, les futures requêtes SQL
                // tomberont aussi dans les catch du UserController.
                error_log("DB Connection retry $i: " . $e->getMessage()); // Mieux que echo <pre>
                sleep(2);
            }
        }

        if (!$connected)
        {
            die("DB connection failed after $maxRetries retries.");
        }

        return self::$pdo;
    }
}