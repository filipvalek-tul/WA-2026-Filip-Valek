<?php

class Comment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Přidání komentáře
    public function addComment(int $equipmentId, int $userId, string $content): bool {
        $sql  = "INSERT INTO comments (equipment_id, user_id, content) VALUES (:equipment_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':equipment_id' => $equipmentId,
            ':user_id'      => $userId,
            ':content'      => $content,
        ]);
    }

    // Získání komentářů pro konkrétní vybavení (s uživatelským jménem)
    public function getByEquipment(int $equipmentId): array {
        $sql  = "SELECT c.*, u.username AS author_name
                 FROM comments c
                 JOIN users u ON c.user_id = u.id
                 WHERE c.equipment_id = :equipment_id
                 ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':equipment_id' => $equipmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získání jednoho komentáře podle ID
    public function getById(int $id) {
        $sql  = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizace komentáře
    public function update(int $id, string $content): bool {
        $sql  = "UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':content' => $content, ':id' => $id]);
    }

    // Smazání komentáře
    public function delete(int $id): bool {
        $sql  = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
