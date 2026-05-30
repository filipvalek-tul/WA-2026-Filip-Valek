<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Registrace nového uživatele
    public function register(string $username, string $email, string $password): bool {
        if ($this->findByEmail($email)) {
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (:username, :email, :password, 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hashedPassword,
        ]);
    }

    // Nalezení uživatele podle emailu (pro přihlášení)
    public function findByEmail(string $email) {
        $sql  = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nalezení uživatele podle ID
    public function findById(int $id) {
        $sql  = "SELECT id, username, email, is_admin, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Získání všech uživatelů (admin funkce)
    public function getAllUsers(): array {
        $sql  = "SELECT id, username, email, is_admin, created_at FROM users ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Aktualizace profilu (jméno a/nebo heslo)
    public function updateProfile(int $id, string $username, ?string $newPassword = null): bool {
        if ($newPassword) {
            $sql  = "UPDATE users SET username = :username, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':username' => $username,
                ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
                ':id'       => $id,
            ]);
        } else {
            $sql  = "UPDATE users SET username = :username WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':username' => $username, ':id' => $id]);
        }
    }

    // Smazání uživatele (pouze admin)
    public function deleteUser(int $id): bool {
        $sql  = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
