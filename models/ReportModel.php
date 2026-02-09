<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class ReportModel
{
    private PDO $pdo;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }

    public function createReport(?int $userId, string $message): bool
    {
        try 
        {
            $stmt = $this->pdo->prepare("
                INSERT INTO reports (user_id, message) 
                VALUES (:user_id, :message)
            ");
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            
            return $stmt->execute();
        } 
        catch (PDOException $e) 
        {
            error_log("Report creation error: " . $e->getMessage());
            return false;
        }
    }
}