<?php

class GearController {

    // Výpis všeho vybavení (domovská stránka)
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            require_once '../app/views/gear/landing.php';
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';
        require_once '../app/models/Category.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $categoryModel  = new Category($db);

        // Zobrazení pouze vlastního vybavení pro běžného uživatele, případně všeho pro admina
        if (!empty($_SESSION['is_admin'])) {
            $equipmentList = $equipmentModel->getAll();
        } else {
            $equipmentList = $equipmentModel->getByUser((int)$_SESSION['user_id']);
        }
        
        $categories    = $categoryModel->getAllCategories();

        require_once '../app/views/gear/gear_list.php';
    }

    // API Detail jednoho kusu vybavení + komentáře (pro modal)
    public function apiDetail($id = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing ID']);
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';
        require_once '../app/models/Comment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $commentModel   = new Comment($db);

        $item = $equipmentModel->getById((int)$id);

        if (!$item) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not found']);
            exit;
        }

        $comments = $commentModel->getByEquipment((int)$id);

        header('Content-Type: application/json');
        echo json_encode([
            'item' => $item,
            'comments' => $comments,
            'current_user_id' => $_SESSION['user_id'],
            'is_admin' => !empty($_SESSION['is_admin'])
        ]);
        exit;
    }

    // Formulář pro přidání vybavení
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání vybavení se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/PackingSet.php';

        $db            = (new Database())->getConnection();
        $categoryModel = new Category($db);
        $categories    = $categoryModel->getAllCategories();
        
        $packingSetModel = new PackingSet($db);
        $userSets = $packingSetModel->getByUser((int)$_SESSION['user_id']);

        require_once '../app/views/gear/gear_create.php';
    }

    // Uložení nového vybavení (POST)
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání vybavení se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=gear/create');
            exit;
        }

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        if (empty($name)) {
            $this->addErrorMessage('Název vybavení je povinný.');
            header('Location: ' . BASE_URL . '/index.php?url=gear/create');
            exit;
        }

        $receiptPath = $this->processReceiptUpload();
        $photoPath   = $this->processPhotoUpload();

        $data = [
            'name'           => $name,
            'model'          => htmlspecialchars(trim($_POST['model'] ?? '')),
            'category_id'    => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'quantity'       => !empty($_POST['quantity']) ? (int)$_POST['quantity'] : 1,
            'is_used'        => !empty($_POST['is_used']) ? 1 : 0,
            'purchase_price' => !empty($_POST['purchase_price']) ? (float)$_POST['purchase_price'] : null,
            'purchase_date'  => !empty($_POST['purchase_date'])  ? $_POST['purchase_date'] : null,
            'serial_number'  => htmlspecialchars(trim($_POST['serial_number'] ?? '')),
            'notes'          => htmlspecialchars(trim($_POST['notes'] ?? '')),
            'receipt_path'   => $receiptPath,
            'photo_path'     => $photoPath,
        ];

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);

        $newEquipmentId = $equipmentModel->create($data, (int)$_SESSION['user_id']);

        if ($newEquipmentId) {
            // Přidání do sady, pokud byla vybrána
            if (!empty($_POST['set_id'])) {
                require_once '../app/models/SetItem.php';
                $setItemModel = new SetItem($db);
                $setItemModel->addItem((int)$_POST['set_id'], $newEquipmentId);
            }

            $this->addSuccessMessage('Vybavení bylo úspěšně přidáno do inventáře.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        } else {
            $this->addErrorMessage('Nepodařilo se uložit vybavení do databáze.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=gear/create');
            exit;
        }
    }

    // Formulář pro úpravu vybavení
    public function edit($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu vybavení se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID vybavení k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/PackingSet.php';
        require_once '../app/models/SetItem.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $categoryModel  = new Category($db);
        $packingSetModel = new PackingSet($db);
        $setItemModel    = new SetItem($db);

        $item = $equipmentModel->getById((int)$id);

        if (!$item) {
            $this->addErrorMessage('Požadované vybavení nebylo nalezeno.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola vlastnictví
        if ((int)$item['user_id'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat toto vybavení.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $categories = $categoryModel->getAllCategories();
        $userSets = $packingSetModel->getByUser((int)$_SESSION['user_id']);
        $itemSets = array_column($setItemModel->getSetsForItem((int)$id), 'set_id');
        $currentSetId = !empty($itemSets) ? (int)$itemSets[0] : null;

        require_once '../app/views/gear/gear_edit.php';
    }

    // Uložení úprav vybavení (POST)
    public function update($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu vybavení se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $item           = $equipmentModel->getById((int)$id);

        if (!$item) {
            $this->addErrorMessage('Požadované vybavení nebylo nalezeno.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola vlastnictví
        if ((int)$item['user_id'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat toto vybavení.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        if (empty($name)) {
            $this->addErrorMessage('Název vybavení je povinný.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=gear/edit/' . $id);
            exit;
        }

        // Nová účtenka — pokud nebyla nahrána, ponecháme stávající
        $receiptPath = $this->processReceiptUpload();
        if (!$receiptPath) {
            $receiptPath = $item['receipt_path'];
        }

        // Možnost smazat stávající účtenku
        if (!empty($_POST['delete_receipt']) && $item['receipt_path']) {
            $uploadDir = __DIR__ . '/../../public/uploads/receipts/';
            if (file_exists($uploadDir . $item['receipt_path'])) {
                unlink($uploadDir . $item['receipt_path']);
            }
            $receiptPath = null;
        }

        // Nová fotka — pokud nebyla nahrána, ponecháme stávající
        $photoPath = $this->processPhotoUpload();
        if (!$photoPath) {
            $photoPath = $item['photo_path'];
        }

        // Možnost smazat stávající fotku
        if (!empty($_POST['delete_photo']) && $item['photo_path']) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $item['photo_path'])) {
                unlink($uploadDirPhoto . $item['photo_path']);
            }
            $photoPath = null;
        }

        $data = [
            'name'           => $name,
            'model'          => htmlspecialchars(trim($_POST['model'] ?? '')),
            'category_id'    => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'quantity'       => !empty($_POST['quantity']) ? (int)$_POST['quantity'] : 1,
            'is_used'        => !empty($_POST['is_used']) ? 1 : 0,
            'purchase_price' => !empty($_POST['purchase_price']) ? (float)$_POST['purchase_price'] : null,
            'purchase_date'  => !empty($_POST['purchase_date'])  ? $_POST['purchase_date'] : null,
            'serial_number'  => htmlspecialchars(trim($_POST['serial_number'] ?? '')),
            'notes'          => htmlspecialchars(trim($_POST['notes'] ?? '')),
            'receipt_path'   => $receiptPath,
            'photo_path'     => $photoPath,
        ];

        if ($equipmentModel->update((int)$id, $data)) {
            require_once '../app/models/SetItem.php';
            $setItemModel = new SetItem($db);
            $setItemModel->clearItemSets((int)$id);
            if (!empty($_POST['set_id'])) {
                $setItemModel->addItem((int)$_POST['set_id'], (int)$id);
            }

            $this->addSuccessMessage('Vybavení bylo úspěšně aktualizováno.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $id);
            exit;
        } else {
            $this->addErrorMessage('Nepodařilo se uložit změny.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=gear/edit/' . $id);
            exit;
        }
    }

    // Okamžité smazání fotky
    public function deletePhoto($id = null) {
        if (!isset($_SESSION['user_id']) || !$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $item           = $equipmentModel->getById((int)$id);

        if ($item && ((int)$item['user_id'] === (int)$_SESSION['user_id'] || !empty($_SESSION['is_admin'])) && !empty($item['photo_path'])) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $item['photo_path'])) {
                unlink($uploadDirPhoto . $item['photo_path']);
            }
            $item['photo_path'] = null;
            $equipmentModel->update((int)$id, $item);
            $this->addSuccessMessage('Fotka vybavení byla odstraněna.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=gear/edit/' . (int)$id);
        exit;
    }

    // Okamžité smazání dokladu
    public function deleteReceipt($id = null) {
        if (!isset($_SESSION['user_id']) || !$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $item           = $equipmentModel->getById((int)$id);

        if ($item && ((int)$item['user_id'] === (int)$_SESSION['user_id'] || !empty($_SESSION['is_admin'])) && !empty($item['receipt_path'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/receipts/';
            if (file_exists($uploadDir . $item['receipt_path'])) {
                unlink($uploadDir . $item['receipt_path']);
            }
            $item['receipt_path'] = null;
            $equipmentModel->update((int)$id, $item);
            $this->addSuccessMessage('Doklad byl odstraněn.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=gear/edit/' . (int)$id);
        exit;
    }

    // Smazání vybavení
    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání vybavení se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID vybavení ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db             = (new Database())->getConnection();
        $equipmentModel = new Equipment($db);
        $item           = $equipmentModel->getById((int)$id);

        if (!$item) {
            $this->addErrorMessage('Požadované vybavení nebylo nalezeno.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola vlastnictví
        if ((int)$item['user_id'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění mazat toto vybavení.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Fyzické smazání souboru účtenky
        if (!empty($item['receipt_path'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/receipts/';
            if (file_exists($uploadDir . $item['receipt_path'])) {
                unlink($uploadDir . $item['receipt_path']);
            }
        }

        // Fyzické smazání fotky vybavení
        if (!empty($item['photo_path'])) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $item['photo_path'])) {
                unlink($uploadDirPhoto . $item['photo_path']);
            }
        }

        if ($equipmentModel->delete((int)$id)) {
            $this->addSuccessMessage('Vybavení bylo trvale smazáno.');
        } else {
            $this->addErrorMessage('Nepodařilo se smazat vybavení.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // Upload účtenky (PDF, JPG, PNG)
    protected function processReceiptUpload(): ?string {
        if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/receipts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName  = basename($_FILES['receipt']['name']);
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed       = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($fileExtension, $allowed)) {
            $this->addNoticeMessage('Nepodporovaný formát souboru u účtenky. Povoleny jsou: JPG, PNG, PDF.');
            return null;
        }

        $newName        = 'receipt_' . uniqid() . '.' . $fileExtension;
        $targetFilePath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetFilePath)) {
            return $newName;
        }

        return null;
    }

    // Upload fotky vybavení (JPG, PNG)
    protected function processPhotoUpload(): ?string {
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName  = basename($_FILES['photo']['name']);
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed       = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($fileExtension, $allowed)) {
            $this->addNoticeMessage('Nepodporovaný formát fotky. Povoleny jsou: JPG, PNG, WEBP.');
            return null;
        }

        $newName        = 'photo_' . uniqid() . '.' . $fileExtension;
        $targetFilePath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            return $newName;
        }

        return null;
    }

    // --- Flash zprávy ---
    protected function addSuccessMessage(string $message): void {
        $_SESSION['messages']['success'][] = $message;
    }
    protected function addNoticeMessage(string $message): void {
        $_SESSION['messages']['notice'][] = $message;
    }
    protected function addErrorMessage(string $message): void {
        $_SESSION['messages']['error'][] = $message;
    }
}
