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
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les posts d'un utilisateur spécifique (pour son profil)
     */
    public function getUserPosts(int $userId): array
    {
        $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}