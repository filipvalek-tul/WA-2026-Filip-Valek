<?php

class UserController {

    // Zobrazení profilu přihlášeného uživatele
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro zobrazení profilu se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);
        $user      = $userModel->findById((int)$_SESSION['user_id']);

        require_once '../app/views/user/profile.php';
    }

    // Zpracování aktualizace profilu
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu profilu se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=user/profile');
            exit;
        }

        $username        = htmlspecialchars(trim($_POST['username'] ?? ''));
        $newPassword     = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($username)) {
            $this->addErrorMessage('Uživatelské jméno nesmí být prázdné.');
            header('Location: ' . BASE_URL . '/index.php?url=user/profile');
            exit;
        }

        // Validace hesla — vyplní se jen pokud chce uživatel změnit heslo
        $finalPassword = null;
        if (!empty($newPassword)) {
            if ($newPassword !== $passwordConfirm) {
                $this->addErrorMessage('Nová hesla se neshodují.');
                header('Location: ' . BASE_URL . '/index.php?url=user/profile');
                exit;
            }
            if (strlen($newPassword) < 8 || !preg_match('/[0-9]/', $newPassword)) {
                $this->addErrorMessage('Heslo musí mít alespoň 8 znaků a obsahovat minimálně jedno číslo.');
                header('Location: ' . BASE_URL . '/index.php?url=user/profile');
                exit;
            }
            $finalPassword = $newPassword;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->updateProfile((int)$_SESSION['user_id'], $username, $finalPassword)) {
            $_SESSION['user_name'] = $username;
            $this->addSuccessMessage('Profil byl úspěšně aktualizován.');
        } else {
            $this->addErrorMessage('Nepodařilo se uložit změny profilu.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=user/profile');
        exit;
    }

    // Seznam všech uživatelů (pouze admin)
    public function index() {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Tato stránka je přístupná pouze administrátorům.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);
        $users     = $userModel->getAllUsers();

        require_once '../app/views/user/users_list.php';
    }

    // Smazání uživatele (pouze admin)
    public function delete($id = null) {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Smazat uživatele může pouze administrátor.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID uživatele.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        // Ochrana — admin nemůže smazat sám sebe
        if ((int)$id === (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžete smazat vlastní účet.');
            session_write_close();
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->deleteUser((int)$id)) {
            $this->addSuccessMessage('Uživatel byl trvale smazán.');
        } else {
            $this->addErrorMessage('Nepodařilo se smazat uživatele.');
        }

        session_write_close();
        header('Location: ' . BASE_URL . '/index.php?url=user/index');
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
