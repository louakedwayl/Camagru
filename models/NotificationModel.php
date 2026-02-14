<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class NotificationModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Create a notification (like or comment)
     * Does NOT create if actor == post owner (no self-notif)
     */
    public function createNotification(int $actorId, int $postId, string $type): bool
    {
        // Get post owner
        $stmt = $this->db->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $postOwnerId = (int)$stmt->fetchColumn();

        // Don't notify yourself
        if ($actorId === $postOwnerId) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, actor_id, post_id, type) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$postOwnerId, $actorId, $postId, $type]);
    }

    /**
     * Remove a like notification (when user unlikes)
     */
    public function removeLikeNotification(int $actorId, int $postId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM notifications WHERE actor_id = ? AND post_id = ? AND type = 'like'"
        );
        return $stmt->execute([$actorId, $postId]);
    }

    /**
     * Get all notifications for a user with actor info and post thumbnail
     */
    public function getNotifications(int $userId, int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT n.id, n.type, n.post_id, n.is_read, n.created_at,
                    u.username AS actor_username, 
                    u.avatar_path AS actor_avatar,
                    p.image_path AS post_image,
                    c.content AS comment_content
             FROM notifications n
             JOIN users u ON u.id = n.actor_id
             JOIN posts p ON p.id = n.post_id
             LEFT JOIN comments c ON c.user_id = n.actor_id 
                 AND c.post_id = n.post_id 
                 AND n.type = 'comment'
             ORDER BY n.created_at DESC
             LIMIT ?
        ");
        // For the comment join, get the latest comment by that user on that post
        $stmt = $this->db->prepare(
            "SELECT n.id, n.type, n.post_id, n.is_read, n.created_at,
                    u.username AS actor_username, 
                    u.avatar_path AS actor_avatar,
                    p.image_path AS post_image,
                    (SELECT c.content FROM comments c 
                     WHERE c.user_id = n.actor_id AND c.post_id = n.post_id 
                     ORDER BY c.created_at DESC LIMIT 1) AS comment_content
             FROM notifications n
             JOIN users u ON u.id = n.actor_id
             JOIN posts p ON p.id = n.post_id
             WHERE n.user_id = ?
             ORDER BY n.created_at DESC
             LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count unread notifications
     */
    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE"
        );
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE"
        );
        return $stmt->execute([$userId]);
    }

    /**
     * Get the post owner's email and notification preference
     */
    public function getPostOwnerInfo(int $postId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.email, u.username, u.notifications 
             FROM users u 
             JOIN posts p ON p.user_id = u.id 
             WHERE p.id = ?"
        );
        $stmt->execute([$postId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}