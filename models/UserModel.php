<?php

declare(strict_types= 1);

require_once __DIR__ . '/../config/Database.php';


class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function usernameExists(string $username): bool
    {
        $query = "SELECT 1 FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':username' => $username]);

        return (bool) $stmt->fetchColumn();
    }
}
