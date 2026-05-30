<?php

class Equipment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Vytvoření nového záznamu vybavení
    public function create(array $data, int $userId): int|false {
        $sql = "INSERT INTO equipment 
                    (user_id, name, model, category_id, quantity, is_used, purchase_price, purchase_date, serial_number, notes, receipt_path, photo_path)
                VALUES 
                    (:user_id, :name, :model, :category_id, :quantity, :is_used, :purchase_price, :purchase_date, :serial_number, :notes, :receipt_path, :photo_path)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([
            ':user_id'        => $userId,
            ':name'           => $data['name'],
            ':model'          => $data['model'] ?: null,
            ':category_id'    => $data['category_id'] ?: null,
            ':quantity'       => isset($data['quantity']) && $data['quantity'] !== '' ? (int)$data['quantity'] : 1,
            ':is_used'        => !empty($data['is_used']) ? 1 : 0,
            ':purchase_price' => $data['purchase_price'] ?: null,
            ':purchase_date'  => $data['purchase_date'] ?: null,
            ':serial_number'  => $data['serial_number'] ?: null,
            ':notes'          => $data['notes'] ?: null,
            ':receipt_path'   => $data['receipt_path'] ?: null,
            ':photo_path'     => $data['photo_path'] ?: null,
        ])) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    // Získání všech kusů vybavení (s názvem kategorie a uživatelským jménem)
    public function getAll(): array {
        $sql = "SELECT e.*, c.name AS category_name, u.username AS owner_name
                FROM equipment e
                LEFT JOIN categories c ON e.category_id = c.id
                LEFT JOIN users u      ON e.user_id      = u.id
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získání vybavení konkrétního uživatele
    public function getByUser(int $userId): array {
        $sql = "SELECT e.*, c.name AS category_name
                FROM equipment e
                LEFT JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = :user_id
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získání jednoho kusu vybavení podle ID
    public function getById(int $id) {
        $sql = "SELECT e.*, c.name AS category_name, u.username AS owner_name
                FROM equipment e
                LEFT JOIN categories c ON e.category_id = c.id
                LEFT JOIN users u      ON e.user_id      = u.id
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizace záznamu
    public function update(int $id, array $data): bool {
        $sql = "UPDATE equipment SET
                    name           = :name,
                    model          = :model,
                    category_id    = :category_id,
                    quantity       = :quantity,
                    is_used        = :is_used,
                    purchase_price = :purchase_price,
                    purchase_date  = :purchase_date,
                    serial_number  = :serial_number,
                    notes          = :notes,
                    receipt_path   = :receipt_path,
                    photo_path     = :photo_path
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':name'           => $data['name'],
            ':model'          => $data['model'] ?: null,
            ':category_id'    => $data['category_id'] ?: null,
            ':quantity'       => isset($data['quantity']) && $data['quantity'] !== '' ? (int)$data['quantity'] : 1,
            ':is_used'        => !empty($data['is_used']) ? 1 : 0,
            ':purchase_price' => $data['purchase_price'] ?: null,
            ':purchase_date'  => $data['purchase_date'] ?: null,
            ':serial_number'  => $data['serial_number'] ?: null,
            ':notes'          => $data['notes'] ?: null,
            ':receipt_path'   => $data['receipt_path'] ?: null,
            ':photo_path'     => $data['photo_path'] ?: null,
        ]);
    }

    // Smazání záznamu
    public function delete(int $id): bool {
        $sql  = "DELETE FROM equipment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Vybavení dostupné pro přidání do sady (vše daného uživatele)
    public function getAvailableForSet(int $userId, int $setId): array {
        $sql = "SELECT e.*, c.name AS category_name
                FROM equipment e
                LEFT JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = :user_id
                  AND e.id NOT IN (SELECT equipment_id FROM set_items WHERE set_id = :set_id)
                ORDER BY e.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':set_id' => $setId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
