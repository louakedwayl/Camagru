<?php

declare(strict_types= 1);

require_once __DIR__ . '/../config/Database.php';

class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function usernameExists(string $username): bool
    {
            $query = "SELECT 1 FROM users WHERE username = :username LIMIT 1";
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();
            return (bool) $statement->fetchColumn();
    }

    public function create(string $username, string $fullName, string $email, string $password): bool
    {
        try
        {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Code de validation aléatoire
            $validationCode = bin2hex(random_bytes(16));
            
            // Expiration du code : 24h
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        }
        catch(PDOException $e)
        {
            error_log("Erreur création utilisateur : " . $e->getMessage());
            return false;
        }
    }
}
