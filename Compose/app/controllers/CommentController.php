<?php

class CommentController {

    // Přidání komentáře k vybavení (POST)
    public function store($equipmentId = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání komentáře se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$equipmentId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $content = htmlspecialchars(trim($_POST['content'] ?? ''));
        if (empty($content)) {
            $this->addErrorMessage('Obsah komentáře nesmí být prázdný.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $equipmentId);
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db           = (new Database())->getConnection();
        $commentModel = new Comment($db);

        if ($commentModel->addComment((int)$equipmentId, (int)$_SESSION['user_id'], $content)) {
            $this->addSuccessMessage('Komentář byl přidán.');
        } else {
            $this->addErrorMessage('Nepodařilo se přidat komentář.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $equipmentId);
        exit;
    }

    // Formulář pro úpravu komentáře
    public function edit($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu komentáře se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db           = (new Database())->getConnection();
        $commentModel = new Comment($db);
        $comment      = $commentModel->getById((int)$id);

        if (!$comment) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Pouze autor může upravit komentář
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $comment['equipment_id']);
            exit;
        }

        require_once '../app/views/gear/comment_edit.php';
    }

    // Uložení upraveného komentáře (POST)
    public function update($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu komentáře se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db           = (new Database())->getConnection();
        $commentModel = new Comment($db);
        $comment      = $commentModel->getById((int)$id);

        if (!$comment) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Pouze autor může upravit komentář
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $comment['equipment_id']);
            exit;
        }

        $content = htmlspecialchars(trim($_POST['content'] ?? ''));
        if (empty($content)) {
            $this->addErrorMessage('Obsah komentáře nesmí být prázdný.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=comment/edit/' . $id);
            exit;
        }

        if ($commentModel->update((int)$id, $content)) {
            $this->addSuccessMessage('Komentář byl upraven.');
        } else {
            $this->addErrorMessage('Nepodařilo se uložit komentář.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $comment['equipment_id']);
        exit;
    }

    // Smazání komentáře (autor nebo admin)
    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání komentáře se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db           = (new Database())->getConnection();
        $commentModel = new Comment($db);
        $comment      = $commentModel->getById((int)$id);

        if (!$comment) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Autor nebo admin mohou smazat
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění mazat tento komentář.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $comment['equipment_id']);
            exit;
        }

        $equipmentId = $comment['equipment_id'];

        if ($commentModel->delete((int)$id)) {
            $this->addSuccessMessage('Komentář byl smazán.');
        } else {
            $this->addErrorMessage('Nepodařilo se smazat komentář.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?open_gear_modal=' . $equipmentId);
        exit;
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
