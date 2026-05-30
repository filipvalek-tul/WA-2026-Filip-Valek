<?php

class Category {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllCategories(): array {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY CASE name WHEN 'Těla' THEN 1 WHEN 'Objektivy' THEN 2 WHEN 'Světla' THEN 3 WHEN 'Doplňky' THEN 4 ELSE 5 END");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
