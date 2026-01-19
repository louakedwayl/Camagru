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

    public function updateValidationCode(string $email, string $newCode): bool
    {
        try 
        {
            // On écrase le code et on redonne 10 minutes de validité
            $query = "UPDATE users 
                    SET validation_code = :code, 
                        validation_code_expires_at = DATE_ADD(NOW(), INTERVAL 10 MINUTE),
                        updated_at = NOW()
                    WHERE email = :email";
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':code' => $newCode,
                ':email' => $email
            ]);
        } 
        catch (PDOException $e) 
        {
            return false;
        }
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
            return false;
        }
    }

    public function getValidationCode(string $email): ?string
    {
        $query = "SELECT validation_code FROM users WHERE email = :email";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetchColumn();
        return $result ? (string) $result : null;
    }

    public function usernameExists(string $username) : bool
    {
        try
        {
            $query = "SELECT 1 FROM users WHERE username = :username";
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(":username", $username, PDO::PARAM_STR);
            $statement->execute();
            return (bool)$statement->fetchColumn();
        }
        catch(PDOException $e)
        {
            return false;
        }
    }

    public function emailExists(string $email) : bool
    {
        try
        {
            $query = "SELECT 1 FROM users WHERE email = :email";
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            return (bool)$statement->fetchColumn();
        }
        catch(PDOException $e)
        {
            return false;
        }
    }

    public function getUserByEmail(string $email) 
    {
        try
        {
            $query = "SELECT * FROM users WHERE email = :email";

            $statement = $this->pdo->prepare($query);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            return false;
        }
    }

/**
     * Retourne : "success", "invalid" ou "expired"
     */
    public function validateUser(string $email, string $code): string
    {
        try
        {
            // ÉTAPE 1 : On va chercher les infos (L'Enquête)
            // On a besoin du code ET de la date d'expiration stockés en base
            $query = "SELECT id, validation_code, validation_code_expires_at FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':email' => $email]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user)
                return "invalid";

            // ÉTAPE 2 : On vérifie si c'est le BON code
            if ($user['validation_code'] !== $code) {
                return "invalid"; // Code incorrect
            }

            // ÉTAPE 3 : On vérifie l'heure (Le Chrono)
            $expiration = new DateTime($user['validation_code_expires_at']);
            $now = new DateTime();

            if ($now > $expiration) {
                return "expired"; // C'est le bon code, mais trop tard !
            }

            // ÉTAPE 4 : Tout est bon, on valide (L'Action)
            $updateQuery = "UPDATE users 
                            SET validated = 1, 
                                validation_code = NULL, 
                                validation_code_expires_at = NULL,
                                updated_at = NOW()
                            WHERE id = :id";
            
            $updateStmt = $this->pdo->prepare($updateQuery);
            $updateStmt->execute([':id' => $user['id']]);

            return "success";
        }
        catch(PDOException $e)
        {
            return "error"; // Erreur technique
        }
    }
}
