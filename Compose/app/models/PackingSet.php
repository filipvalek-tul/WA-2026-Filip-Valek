<?php

class PackingSet {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Vytvoření nové sady
    public function create(int $userId, string $name, ?string $description, ?string $photoPath = null): bool {
        $sql  = "INSERT INTO packing_sets (user_id, name, description, photo_path) VALUES (:user_id, :name, :description, :photo_path)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':user_id' => $userId, ':name' => $name, ':description' => $description, ':photo_path' => $photoPath]);
    }

    // Vrátí ID posledně vloženého záznamu
    public function lastInsertId(): string {
        return $this->db->lastInsertId();
    }

    // Sady konkrétního uživatele (s počtem položek)
    public function getByUser(int $userId): array {
        $sql  = "SELECT ps.*, COUNT(si.equipment_id) AS item_count
                 FROM packing_sets ps
                 LEFT JOIN set_items si ON ps.id = si.set_id
                 WHERE ps.user_id = :user_id
                 GROUP BY ps.id
                 ORDER BY ps.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Jedna sada podle ID
    public function getById(int $id) {
        $sql  = "SELECT * FROM packing_sets WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizace sady
    public function update(int $id, string $name, ?string $description, ?string $photoPath = null): bool {
        $sql  = "UPDATE packing_sets SET name = :name, description = :description, photo_path = :photo_path WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $name, ':description' => $description, ':photo_path' => $photoPath, ':id' => $id]);
    }

    // Smazání sady (položky set_items se smažou kaskádou)
    public function delete(int $id): bool {
        $sql  = "DELETE FROM packing_sets WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
