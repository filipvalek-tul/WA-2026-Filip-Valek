<?php

class BookDTO {
    public $title;
    public $author;
    public $isbn;
    public $category;
    public $subcategory;
    public $year;
    public $price;
    public $link;
    public $description;
    public $images;

    // Konstruktor naplní objekt daty hned při jeho vytvoření
    public function __construct($data) {
        $this->title = $data['title'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->isbn = $data['isbn'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->subcategory = $data['subcategory'] ?? '';
        
        // Rovnou jsem sem přidal přetypování, abys měl jistotu, že čísla budou vždy čísla
        $this->year = (int)($data['year'] ?? 0);
        $this->price = (float)($data['price'] ?? 0);
        
        $this->link = $data['link'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->images = $data['images'] ?? [];
    }
}