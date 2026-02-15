<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/ReportModel.php';
require_once __DIR__ . '/../config/Database.php';

class ReportController
{
    private PDO $pdo;
    private ReportModel $reportModel;

    public function __construct() 
    {
        $this->pdo = Database::getConnection();
        $this->reportModel = new ReportModel();
    }

    public function submitReport(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['message']) || empty(trim($input['message']))) 
        {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Message is required']);
            exit;
        }

        $message = trim($input['message']);
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

        try 
        {
            $created = $this->reportModel->createReport($userId, $message);
            
            if ($created) 
            {
                echo json_encode(['success' => true, 'message' => 'Report submitted successfully']);
            } 
            else 
            {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to submit report']);
            }
        } 
        catch (Exception $e) 
        {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
}