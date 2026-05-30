<?php

class AuthController {

    // 1. Zobrazení registračního formuláře
    public function register() {
        $old_username = $_SESSION['old_username'] ?? '';
        $old_email = $_SESSION['old_email'] ?? '';
        unset($_SESSION['old_username'], $_SESSION['old_email']);
        require_once '../app/views/auth/register.php';
    }

    // 2. Zpracování registrace
    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        $username        = htmlspecialchars(trim($_POST['username'] ?? ''));
        $email           = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $_SESSION['old_username'] = $username;
        $_SESSION['old_email'] = $email;

        if (empty($username) || empty($email) || empty($password)) {
            $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $this->addErrorMessage('Zadaná hesla se neshodují.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        if (strlen($password) < 8 || !preg_match('/[0-9]/', $password)) {
            $this->addErrorMessage('Heslo musí mít alespoň 8 znaků a obsahovat minimálně jedno číslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->register($username, $email, $password)) {
            $this->addSuccessMessage('Registrace proběhla úspěšně. Nyní se můžete přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        } else {
            $this->addErrorMessage('Uživatel s tímto e-mailem již existuje.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }
    }

    // 3. Zobrazení přihlašovacího formuláře
    public function login() {
        $old_email = $_SESSION['old_email'] ?? '';
        unset($_SESSION['old_email']);
        require_once '../app/views/auth/login.php';
    }

    // 4. Zpracování přihlášení
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $email    = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db        = (new Database())->getConnection();
        $userModel = new User($db);
        $user      = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['is_admin'] = (int)$user['is_admin'];

            $this->addSuccessMessage('Vítejte, ' . htmlspecialchars($_SESSION['user_name']) . '!');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        } else {
            $_SESSION['old_email'] = $email;
            $this->addErrorMessage('Nesprávný e-mail nebo heslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    // 5. Odhlášení
    public function logout() {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin']);
        $this->addSuccessMessage('Byli jste úspěšně odhlášeni.');
        header('Location: ' . BASE_URL . '/index.php');
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
