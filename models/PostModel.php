<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class PostModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère tous les posts pour l'affichage de la galerie
     * On peut joindre la table users pour avoir le nom du créateur si besoin
     */
    public function getAllPosts(): array
    {
        try {
            $sql = "
                SELECT 
                    p.id,
                    p.image_path,
                    p.caption,
                    p.created_at,
                    p.user_id,
                    u.username,
                    u.avatar_path as user_avatar,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT c.id) as comments_count
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN likes l ON p.id = l.post_id
                LEFT JOIN comments c ON p.id = c.post_id
                GROUP BY p.id, p.image_path, p.caption, p.created_at, p.user_id, u.username, u.avatar_path
                ORDER BY p.id ASC
            ";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

   /**
     * Récupère les posts d'un utilisateur spécifique (pour son profil)
     * @param int $userId
     * @return array
     */
    public function getPostsByUserId(int $userId): array
    {
        try {
            $sql = "
                SELECT 
                    p.*,
                    u.username,
                    u.avatar_path as user_avatar,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT c.id) as comments_count
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN likes l ON p.id = l.post_id
                LEFT JOIN comments c ON p.id = c.post_id
                WHERE p.user_id = :user_id
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } 
        catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupère un post spécifique avec toutes ses infos
     * @param int $postId
     * @return array|null
     */
    public function getPostById(int $postId): ?array
    {
        try {
            $sql = "
                SELECT 
                    p.*,
                    u.username,
                    u.avatar_path as user_avatar,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT c.id) as comments_count
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN likes l ON p.id = l.post_id
                LEFT JOIN comments c ON p.id = c.post_id
                WHERE p.id = :post_id
                GROUP BY p.id
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':post_id' => $postId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ?: null;
            
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Crée un nouveau post
     * @param int $userId
     * @param string $imagePath
     * @param string|null $caption
     * @return bool
     */
    public function createPost(int $userId, string $imagePath, ?string $caption = null): bool
    {
        try {
            $sql = "INSERT INTO posts (user_id, image_path, caption) VALUES (:user_id, :image_path, :caption)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':image_path' => $imagePath,
                ':caption' => $caption
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un post
     * @param int $postId
     * @param int $userId Pour vérifier que c'est bien le propriétaire
     * @return bool
     */
    public function deletePost(int $postId, int $userId): bool
    {
        try {
            $sql = "DELETE FROM posts WHERE id = :post_id AND user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':post_id' => $postId,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur a liké un post
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public function hasUserLiked(int $userId, int $postId): bool
    {
        try {
            $sql = "SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':post_id' => $postId
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Ajoute un like
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public function addLike(int $userId, int $postId): bool
    {
        try {
            $sql = "INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':post_id' => $postId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Retire un like
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public function removeLike(int $userId, int $postId): bool
    {
        try {
            $sql = "DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':post_id' => $postId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère les commentaires d'un post
     * @param int $postId
     * @return array
     */
    public function getComments(int $postId): array
    {
        try {
            $sql = "
                SELECT 
                    c.id,
                    c.content,
                    c.created_at,
                    c.user_id,
                    u.username,
                    u.avatar_path
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :post_id
                ORDER BY c.created_at ASC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':post_id' => $postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Ajoute un commentaire
     * @param int $userId
     * @param int $postId
     * @param string $content
     * @return bool
     */
    public function addComment(int $userId, int $postId, string $content): bool
    {
        try {
            $sql = "INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':post_id' => $postId,
                ':content' => trim($content)
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Compte le nombre total de posts
     * @return int
     */
    public function getTotalPosts(): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM posts";
            $stmt = $this->db->query($sql);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
}