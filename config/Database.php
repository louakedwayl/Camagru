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

        // Charger les variables d'environnement
        $envFile = __DIR__ . '/../.env';

        if (file_exists($envFile)) 
        {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) 
            {
                if (strpos(trim($line), '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                $_ENV[$key] = $value;
            }
        }

        $DSN = $_ENV['DSN'] ?? '';
        $USER = $_ENV['USER'] ?? '';
        $PASS = $_ENV['PASS'] ?? '';

        $maxRetries = 3;
        $connected = false;
        
        for ($i = 0; $i < $maxRetries; $i++)
        {
            try 
            {
                self::$pdo = new PDO($DSN, $USER, $PASS);
                $connected = true;
                break;
            }
            catch (PDOException $e)
            {
                echo "<pre>";
                echo "Waiting for DB... retry $i\n";
                echo "=== Erreur PDO ===\n";
                echo "Message : " . $e->getMessage() . "\n";
                echo "</pre>";
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