<?php

class Book {
    // Definice, že proměnná $db musí být vždy instancí třídy PDO
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // Metoda nyní přijímá pouze jeden objekt: BookDTO
    public function create(BookDTO $data, int $userId): bool {
        // !!! ZMĚNA: Přidali jsme created_by do INSERT i VALUES
        $sql = "INSERT INTO books (title, author, category, subcategory, year, price, isbn, description, link, images, created_by)
                VALUES (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images, :created_by)";
        
        $stmt = $this->db->prepare($sql);

        // Data se čtou přímo z objektu
        return $stmt->execute([
            ':title' => $data->title,
            ':author' => $data->author,
            ':category' => $data->category,
            ':subcategory' => $data->subcategory ?: null,
            ':year' => $data->year,
            ':price' => $data->price,
            ':isbn' => $data->isbn,
            ':description' => $data->description,
            ':link' => $data->link,
            ':images' => json_encode($data->images),
            ':created_by' => $userId // !!! ZMĚNA: Předání ID do databáze
        ]);
    }

    // Získání všech knih z databáze
    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získání jedné konkrétní knihy podle jejího ID
    public function getById($id) {
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Metoda nyní přijímá ID, objekt BookDTO a ID uživatele
    public function update($id, BookDTO $data, int $userId) {
        $sql = "UPDATE books 
                SET title = :title, 
                    author = :author, 
                    category = :category, 
                    subcategory = :subcategory, 
                    year = :year, 
                    price = :price, 
                    isbn = :isbn, 
                    description = :description, 
                    link = :link, 
                    images = :images,
                    updated_by = :updated_by 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data->title,
            ':author' => $data->author,
            ':category' => $data->category,
            ':subcategory' => $data->subcategory ?: null,
            ':year' => $data->year,
            ':price' => $data->price,
            ':isbn' => $data->isbn,
            ':description' => $data->description,
            ':link' => $data->link,
            ':images' => json_encode($data->images),
            ':updated_by' => $userId
        ]);
    }

    // Trvalé smazání knihy z databáze
    public function delete($id) {
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}