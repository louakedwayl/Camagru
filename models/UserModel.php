<?php

declare(strict_types= 1);

use BcMath\Number;

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

    public function create(string $username, string $fullName, string $email, string $password, string $validationCode): bool
    {
        try
        {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            // Code de validation aléatoire (6 chiffres)
            
            // Expiration du code : 10 minutes
            $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            $query = "INSERT INTO users 
                      (username, full_name, email, password, validated, validation_code, validation_code_expires_at, created_at, updated_at) 
                      VALUES 
                      (:username, :full_name, :email, :password, 0, :validation_code, :expires_at, NOW(), NOW())";
            
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':full_name', $fullName, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $statement->bindParam(':validation_code', $validationCode, PDO::PARAM_STR);
            $statement->bindParam(':expires_at', $expiresAt, PDO::PARAM_STR);
            
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            error_log("Erreur création utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function getValidationCode(string $email): ?string
    {
        $query = "SELECT validation_code FROM users WHERE email = :email LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetchColumn();
        return $result ? (string) $result : null;
    }

    public function validateUser(string $email, string $code): bool
    {
        try
        {
            $query = "UPDATE users 
                      SET validated = 1, 
                          validation_code = NULL, 
                          validation_code_expires_at = NULL,
                          updated_at = NOW()
                      WHERE email = :email 
                      AND validation_code = :code 
                      AND validation_code_expires_at > NOW()";
            
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':code', $code, PDO::PARAM_STR);
            
            $statement->execute();
            return $statement->rowCount() > 0;
        }
        catch(PDOException $e)
        {
            error_log("Erreur validation utilisateur : " . $e->getMessage());
            return false;
        }
    }
}
