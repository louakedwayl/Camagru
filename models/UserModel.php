<?php

declare(strict_types= 1);

require_once __DIR__ . '/../config/Database.php';

/*
mysql> SELECT * FROM users;

+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
| id | username | full_name    | email                      | password | validated | avatar_path | validation_code | validation_code_expires_at | reset_code | reset_code_expires_at | created_at          | updated_at          |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
|  1 | Wayl     | Wayl Louaked | louakedwayl@protonmail.com | password |         1 | NULL        | NULL            | NULL                       | NULL       | NULL                  | 2026-01-07 19:27:09 | 2026-01-07 19:27:09 |
+----+----------+--------------+----------------------------+----------+-----------+-------------+-----------------+----------------------------+------------+-----------------------+---------------------+---------------------+
1 row in set (0.00 sec)
*/



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
        return true ;
    }
}
