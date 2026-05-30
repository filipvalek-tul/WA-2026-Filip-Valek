<?php

class Subcategory {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Metoda pro získání všech subkategorií seřazených podle názvu
    public function getAllSubcategories() {
        $stmt = $this->db->prepare("SELECT * FROM subcategories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
