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

/**
     * Updates the validation code for a specific user.
     * * This method generates a new security code, resets its expiration 
     * timer to 10 minutes from now, and updates the 'updated_at' timestamp.
     *
     * @param string $email   The email of the user to update.
     * @param string $newCode The new 6-digit validation code.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateValidationCode(string $email, string $newCode): bool
    {
        try 
        {
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


/**
     * Creates a new user record in the database.
     * * This method hashes the password using BCRYPT, sets a 10-minute 
     * expiration for the validation code, and performs an INSERT 
     * operation. Returns true if the user was successfully created.
     * * @param string $username       The unique username.
     * @param string $fullName       The user's real name.
     * @param string $email          The unique email address.
     * @param string $password       The plain text password (will be hashed).
     * @param string $validationCode The 6-digit security code for verification.
     * * @return bool True on success, false on failure.
     */
    public function create(string $username, string $fullName, string $email, string $password, string $validationCode): bool
    {
        try
        {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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

    /**
     * Retrieves the validation code for a specific user.
     * * This method searches for the security code associated with the 
     * provided email. It returns the code as a string or null if 
     * no record is found.
     *
     * @param string $email The user's email address.
     * @return string|null The validation code if found, null otherwise.
     */
    public function getValidationCode(string $email): ?string
    {
        $query = "SELECT validation_code FROM users WHERE email = :email";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        
        $result = $statement->fetchColumn();
        return $result ? (string) $result : null;
    }

/**
     * Checks if a username already exists in the database.
     * * This method executes a SQL query to find if a specific 
     * username is already taken. It returns a boolean and handles 
     * potential database errors by returning false.
     *
     * @param string $username The username to search for.
     * @return bool True if the username exists, false otherwise (or on error).
     */
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

/**
     * Checks if an email address already exists in the database.
     * * Performs an search to verify if the given email is registered.
     * Uses a prepared statement to prevent SQL injection.
     *
     * @param string $email The email address to check.
     * @return bool True if the email is found, false if not (or on database error).
     */
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

/**
     * Retrieves a user by their email or username.
     * * This method performs a search in the database to find a user 
     * matching the provided login identifier. It returns the user 
     * data as an associative array or false if no match is found 
     * or if a database error occurs.
     *
     * @param string $login The email or username entered by the user.
     * @return array|bool The user record (array) on success, or false on failure.
     */
    public function getUserByLogin(string $login) 
    {
        try
        {
            $query = "SELECT * FROM users WHERE email = :p_email OR username = :p_username";

            $statement = $this->pdo->prepare($query);            
            $statement->bindParam(":p_email", $login, PDO::PARAM_STR);
            $statement->bindParam(":p_username", $login, PDO::PARAM_STR);
            
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            return false;
        }
    }

/**
     * Validates a user account by verifying the security code.
     * * This method checks if the user exists, compares the provided code, 
     * verifies if the code has expired, and activates the account if 
     * all conditions are met. Returns a status string indicating the result.
     *
     * @param string $email The user's email address.
     * @param string $code  The 6-digit validation code to verify.
     * @return string Status result: "success", "invalid", "expired", or "error".
     */
    public function validateUser(string $email, string $code): string
    {
        try
        {
            $query = "SELECT id, validation_code, validation_code_expires_at FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':email' => $email]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user)
                return "invalid";

            if ($user['validation_code'] !== $code)
                return "invalid";

            $expiration = new DateTime($user['validation_code_expires_at']);
            $now = new DateTime();
            if ($now > $expiration)
             {
               return "expired";
            }
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
            return "error";
        }
    }
}
