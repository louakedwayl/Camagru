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

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::ATTR_EMULATE_PREPARES => false, 
        ];

        $maxRetries = 3;
        $connected = false;
        
        for ($i = 0; $i < $maxRetries; $i++)
        {
            try 
            {
                self::$pdo = new PDO($DSN, $USER, $PASS, $options);
                $connected = true;
                break;
            }
            catch (PDOException $e)
            {
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