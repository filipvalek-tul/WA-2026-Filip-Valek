<?php

class SetController {

    // Seznam sad přihlášeného uživatele
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro zobrazení sad se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $sets     = $setModel->getByUser((int)$_SESSION['user_id']);

        require_once '../app/views/sets/sets_list.php';
    }

    // Detail sady — vrací JSON pro Modal v seznamu sad
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
        require_once '../app/models/PackingSet.php';
        require_once '../app/models/SetItem.php';

        $db         = (new Database())->getConnection();
        $setModel   = new PackingSet($db);
        $itemModel  = new SetItem($db);

        $set = $setModel->getById((int)$id);

        if (!$set || (int)$set['user_id'] !== (int)$_SESSION['user_id']) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not found or forbidden']);
            exit;
        }

        $items = $itemModel->getItemsForSet((int)$id);

        header('Content-Type: application/json');
        echo json_encode([
            'set' => $set,
            'items' => $items
        ]);
        exit;
    }

    // Formulář pro novou sadu
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro vytvoření sady se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Equipment.php';

        $db = (new Database())->getConnection();
        $gearModel = new Equipment($db);
        $availableGear = $gearModel->getByUser((int)$_SESSION['user_id']);

        require_once '../app/views/sets/set_create.php';
    }

    // Uložení nové sady (POST)
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro vytvoření sady se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=set/create');
            exit;
        }

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        if (empty($name)) {
            $this->addErrorMessage('Název sady je povinný.');
            header('Location: ' . BASE_URL . '/index.php?url=set/create');
            exit;
        }

        $description = htmlspecialchars(trim($_POST['description'] ?? ''));
        $equipmentIds = $_POST['equipment_ids'] ?? [];
        $photoPath = $this->processPhotoUpload();

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';
        require_once '../app/models/SetItem.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $setItemModel = new SetItem($db);

        if ($setModel->create((int)$_SESSION['user_id'], $name, $description ?: null, $photoPath)) {
            $newId = $setModel->lastInsertId();
            
            // Přidání vybraného vybavení
            if (is_array($equipmentIds)) {
                foreach ($equipmentIds as $eqId) {
                    $setItemModel->addItem($newId, (int)$eqId);
                }
            }

            $this->addSuccessMessage('Sada "' . $name . '" byla vytvořena.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        } else {
            $this->addErrorMessage('Nepodařilo se vytvořit sadu.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/create');
            exit;
        }
    }

    // Formulář pro úpravu sady
    public function edit($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu sady se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';
        require_once '../app/models/Equipment.php';
        require_once '../app/models/SetItem.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $gearModel = new Equipment($db);
        $itemModel = new SetItem($db);

        $set      = $setModel->getById((int)$id);

        if (!$set || (int)$set['user_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Sada nebyla nalezena nebo k ní nemáte přístup.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        $availableGear = $gearModel->getByUser((int)$_SESSION['user_id']);
        $currentItems = $itemModel->getItemsForSet((int)$id);
        $currentGearIds = array_column($currentItems, 'id');

        require_once '../app/views/sets/set_edit.php';
    }

    // Uložení úprav sady (POST)
    public function update($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu sady se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';
        require_once '../app/models/SetItem.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $setItemModel = new SetItem($db);
        $set      = $setModel->getById((int)$id);

        if (!$set || (int)$set['user_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Sada nebyla nalezena nebo k ní nemáte přístup.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        if (empty($name)) {
            $this->addErrorMessage('Název sady je povinný.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/edit/' . $id);
            exit;
        }

        $description = htmlspecialchars(trim($_POST['description'] ?? ''));
        $equipmentIds = $_POST['equipment_ids'] ?? [];

        $photoPath = $this->processPhotoUpload();
        if (!$photoPath) {
            $photoPath = $set['photo_path'] ?? null;
        }

        // Smazání staré fotky
        if (!empty($_POST['delete_photo']) && !empty($set['photo_path'])) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $set['photo_path'])) {
                unlink($uploadDirPhoto . $set['photo_path']);
            }
            $photoPath = null;
        }

        if ($setModel->update((int)$id, $name, $description ?: null, $photoPath)) {
            // Aktualizace obsahu sady
            $setItemModel->clearSet((int)$id);
            if (is_array($equipmentIds)) {
                foreach ($equipmentIds as $eqId) {
                    $setItemModel->addItem((int)$id, (int)$eqId);
                }
            }

            $this->addSuccessMessage('Sada byla aktualizována.');
        } else {
            $this->addErrorMessage('Nepodařilo se uložit změny sady.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=set/index');
        exit;
    }

    // Okamžité smazání fotky ze sady
    public function deletePhoto($id = null) {
        if (!isset($_SESSION['user_id']) || !$id) {
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $set      = $setModel->getById((int)$id);

        if ($set && (int)$set['user_id'] === (int)$_SESSION['user_id'] && !empty($set['photo_path'])) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $set['photo_path'])) {
                unlink($uploadDirPhoto . $set['photo_path']);
            }
            $setModel->update((int)$id, $set['name'], $set['description'], null);
            $this->addSuccessMessage('Fotka sady byla odstraněna.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=set/edit/' . (int)$id);
        exit;
    }



    // Smazání sady
    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání sady se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID sady ke smazání.');
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/PackingSet.php';

        $db       = (new Database())->getConnection();
        $setModel = new PackingSet($db);
        $set      = $setModel->getById((int)$id);

        if (!$set) {
            $this->addErrorMessage('Sada nebyla nalezena.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        if ((int)$set['user_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění smazat tuto sadu.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=set/index');
            exit;
        }

        // Fyzické smazání fotky
        if (!empty($set['photo_path'])) {
            $uploadDirPhoto = __DIR__ . '/../../public/uploads/photos/';
            if (file_exists($uploadDirPhoto . $set['photo_path'])) {
                unlink($uploadDirPhoto . $set['photo_path']);
            }
        }

        require_once '../app/models/SetItem.php';
        $setItemModel = new SetItem($db);
        $setItemModel->clearSet((int)$id);

        if ($setModel->delete((int)$id)) {
            $this->addSuccessMessage('Sada byla úspěšně smazána.');
        } else {
            $this->addErrorMessage('Nepodařilo se smazat sadu.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=set/index');
        exit;
    }

    // Upload fotky sady
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

        $newName        = 'set_' . uniqid() . '.' . $fileExtension;
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
