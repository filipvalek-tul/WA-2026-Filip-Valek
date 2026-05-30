<?php

class BookController {

    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $books = $bookModel->getAll(); 
        
        require_once '../app/views/books/books_list.php';
    }

    public function create() {
    // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
    if (!isset($_SESSION['user_id'])) {
        $this->addErrorMessage('Pro přidání knihy se musíte nejprve přihlásit.');
        header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        exit;
    }

    // ZMĚNA: Načtení databáze a nového modelu Category
    require_once '../app/models/Database.php';
    require_once '../app/models/Category.php';
    require_once '../app/models/Subcategory.php';

    $database = new Database();
    $db = $database->getConnection();

    // ZMĚNA: Získání seznamu kategorií a subkategorií
    $categoryModel = new Category($db);
    $categories = $categoryModel->getAllCategories();

    $subcategoryModel = new Subcategory($db);
    $subcategories = $subcategoryModel->getAllSubcategories();
    
    require_once '../app/views/books/book_create.php';
}

    public function store() {
        // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $cleanData = [
                'title' => htmlspecialchars($_POST['title'] ?? ''),
                'author' => htmlspecialchars($_POST['author'] ?? ''),
                'isbn' => htmlspecialchars($_POST['isbn'] ?? ''),
                'category' => !empty($_POST['category']) ? (int)$_POST['category'] : null,
                'subcategory' => !empty($_POST['subcategory']) ? (int)$_POST['subcategory'] : null,
                'year' => $_POST['year'] ?? 0,
                'price' => $_POST['price'] ?? 0,
                'link' => htmlspecialchars($_POST['link'] ?? ''),
                'description' => htmlspecialchars($_POST['description'] ?? ''),
                'images' => $this->processImageUploads() 
            ];

            require_once '../app/dto/BookDTO.php';
            $bookDTO = new BookDTO($cleanData);

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            try {
                // !!! ZMĚNA: Do metody předáváme navíc i ID uživatele ze SESSION
                $isSaved = $bookModel->create($bookDTO, $_SESSION['user_id']);

                if ($isSaved) {
                    $this->addSuccessMessage('Kniha byla úspěšně uložena do databáze.');
                    session_write_close();
                    header('Location: ' . BASE_URL . '/index.php');
                    exit;
                } else {
                    $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit knihu do databáze.');
                    session_write_close();
                    header('Location: ' . BASE_URL . '/index.php?url=book/create');
                    exit;
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $this->addErrorMessage('Chyba: Kniha s tímto ISBN již v databázi existuje!');
                } else {
                    $this->addErrorMessage('Databázová chyba: ' . $e->getMessage());
                }
                
                session_write_close();
                header('Location: ' . BASE_URL . '/index.php?url=book/create');
                exit;
            }
            
        } else {
            $this->addNoticeMessage('Pro přidání knihy je nutné odeslat formulář.');
        }
    }

    public function delete($id = null) {
        // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy ke smazání.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // 🛡️ ZMĚNA: Kontrola vlastnictví
        if ((int)$book['created_by'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění mazat tuto knihu, protože nejste jejím autorem ani administrátor.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isDeleted = $bookModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Kniha byla trvale smazána z databáze.');
        } else {
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function edit($id = null) {
        // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k úpravě.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        require_once '../app/models/Category.php'; // ZMĚNA: Načtení modelu Category
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // 🛡️ !!! ZMĚNA: Kontrola vlastnictví (Autorizace).
        // Ověříme, zda ID přihlášeného uživatele odpovídá ID autora uloženého u knihy.
        if ((int)$book['created_by'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tuto knihu, protože nejste jejím autorem ani administrátor.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // ZMĚNA: Získání seznamu kategorií pro editační formulář
        $categoryModel = new Category($db);
        $categories = $categoryModel->getAllCategories();

        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();

        require_once '../app/views/books/book_edit.php';
    }

    public function update($id = null) {
        // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k aktualizaci.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';
            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            // 1. Získáme staré obrázky z databáze
            $existingBook = $bookModel->getById($id);

            if (!$existingBook) {
                $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
                session_write_close();
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            // 🛡️ ZMĚNA: Kontrola vlastnictví při aktualizaci
            if ((int)$existingBook['created_by'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tuto knihu, protože nejste jejím autorem ani administrátor.');
                session_write_close();
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $existingImages = json_decode($existingBook['images'] ?? '[]', true) ?: [];

            // 2. Podíváme se, co chce uživatel smazat
            $imagesToDelete = $_POST['delete_images'] ?? [];
            
            // Fyzicky smažeme soubory ze serveru (Profi UX!)
            $uploadDir = __DIR__ . '/../../public/uploads/';
            foreach ($imagesToDelete as $delImg) {
                if (file_exists($uploadDir . $delImg)) {
                    unlink($uploadDir . $delImg); 
                }
            }

            // Odečteme smazané obrázky od těch stávajících
            $remainingImages = array_diff($existingImages, $imagesToDelete);

            // 3. Nahrajeme nové obrázky
            $newImages = $this->processImageUploads();

            // 4. Sloučíme to dohromady (Zbylé staré + Nově nahrané) a srovnáme indexy (array_values)
            $finalImages = array_values(array_merge($remainingImages, $newImages));

            $cleanData = [
                'title' => htmlspecialchars($_POST['title'] ?? ''),
                'author' => htmlspecialchars($_POST['author'] ?? ''),
                'isbn' => htmlspecialchars($_POST['isbn'] ?? ''),
                'category' => !empty($_POST['category']) ? (int)$_POST['category'] : null,
                'subcategory' => !empty($_POST['subcategory']) ? (int)$_POST['subcategory'] : null,
                'year' => $_POST['year'] ?? 0,
                'price' => $_POST['price'] ?? 0,
                'link' => htmlspecialchars($_POST['link'] ?? ''),
                'description' => htmlspecialchars($_POST['description'] ?? ''),
                'images' => $finalImages 
            ];

            require_once '../app/dto/BookDTO.php';
            $bookDTO = new BookDTO($cleanData);

            try {
                // !!! ZMĚNA: Předání $_SESSION['user_id'] pro uložení stopy, kdo záznam opravdu jako poslední upravil
                $isUpdated = $bookModel->update($id, $bookDTO, $_SESSION['user_id']);

                if ($isUpdated) {
                    $this->addSuccessMessage('Kniha byla úspěšně upravena.');
                    session_write_close();
                    header('Location: ' . BASE_URL . '/index.php');
                    exit;
                } else {
                    $this->addErrorMessage('Nastala chyba. Změny se nepodařilo uložit.');
                    session_write_close();
                    header('Location: ' . BASE_URL . '/index.php?url=book/edit/' . $id);
                    exit;
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $this->addErrorMessage('Chyba: Kniha s tímto ISBN již v databázi existuje!');
                } else {
                    $this->addErrorMessage('Databázová chyba: ' . $e->getMessage());
                }
                
                session_write_close();
                header('Location: ' . BASE_URL . '/index.php?url=book/edit/' . $id);
                exit;
            }
            
        } else {
            $this->addNoticeMessage('Pro úpravu knihy je nutné odeslat formulář.');
        }
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; 
                    }

                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
}