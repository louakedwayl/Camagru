<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/NotificationModel.php';

class NotificationController
{
    private NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * GET: Fetch notifications for the logged-in user
     */
    public function getNotifications(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $notifications = $this->notificationModel->getNotifications($userId);
        $unreadCount = $this->notificationModel->countUnread($userId);

        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
        exit;
    }

    /**
     * POST: Mark all notifications as read
     */
    public function markAsRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $this->notificationModel->markAllAsRead($userId);

        echo json_encode(['success' => true]);
        exit;
    }
}