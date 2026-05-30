<?php

class SetItem {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Přidání kusu vybavení do sady
    public function addItem(int $setId, int $equipmentId): bool {
        $sql  = "INSERT IGNORE INTO set_items (set_id, equipment_id) VALUES (:set_id, :equipment_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':set_id' => $setId, ':equipment_id' => $equipmentId]);
    }

    // Odebrání kusu vybavení ze sady
    public function removeItem(int $setId, int $equipmentId): bool {
        $sql  = "DELETE FROM set_items WHERE set_id = :set_id AND equipment_id = :equipment_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':set_id' => $setId, ':equipment_id' => $equipmentId]);
    }

    // Získání všech položek sady (s detaily vybavení a kategorií)
    public function getItemsForSet(int $setId): array {
        $sql  = "SELECT e.*, c.name AS category_name
                 FROM set_items si
                 JOIN equipment e  ON si.equipment_id = e.id
                 LEFT JOIN categories c ON e.category_id = c.id
                 WHERE si.set_id = :set_id
                 ORDER BY e.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':set_id' => $setId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Smazání všech položek sady (při přestavbě obsahu)
    public function clearSet(int $setId): bool {
        $sql  = "DELETE FROM set_items WHERE set_id = :set_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':set_id' => $setId]);
    }

    // Získání sad, do kterých vybavení patří
    public function getSetsForItem(int $equipmentId): array {
        $sql = "SELECT set_id FROM set_items WHERE equipment_id = :equipment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':equipment_id' => $equipmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Smazání vazeb vybavení na všechny sady
    public function clearItemSets(int $equipmentId): bool {
        $sql = "DELETE FROM set_items WHERE equipment_id = :equipment_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':equipment_id' => $equipmentId]);
    }
}
