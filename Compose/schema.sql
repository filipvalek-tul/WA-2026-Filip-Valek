-- ============================================================
-- e-Fotobatoh — Databázové schéma
-- Databáze: wa_2026_fv_photoapp
-- ============================================================

CREATE DATABASE IF NOT EXISTS wa_2026_fv_photoapp
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE wa_2026_fv_photoapp;

-- ------------------------------------------------------------
-- Tabulka uživatelů
-- ------------------------------------------------------------
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    is_admin    TINYINT(1)    NOT NULL DEFAULT 0,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Kategorie vybavení
-- ------------------------------------------------------------
CREATE TABLE categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Hlavní entita — fototechnika
-- ------------------------------------------------------------
CREATE TABLE equipment (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT            NOT NULL,
    name            VARCHAR(200)   NOT NULL,
    model           VARCHAR(200)   DEFAULT NULL,
    category_id     INT            DEFAULT NULL,
    quantity        INT            DEFAULT 1,
    is_used         TINYINT(1)     DEFAULT 0,
    purchase_price  DECIMAL(10,2)  DEFAULT NULL,
    purchase_date   DATE           DEFAULT NULL,
    serial_number   VARCHAR(100)   DEFAULT NULL,
    notes           TEXT           DEFAULT NULL,
    receipt_path    VARCHAR(255)   DEFAULT NULL,
    photo_path      VARCHAR(255)   DEFAULT NULL,
    created_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_equipment_user     FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    CONSTRAINT fk_equipment_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Komentáře / servisní deník
-- ------------------------------------------------------------
CREATE TABLE comments (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id  INT   NOT NULL,
    user_id       INT   NOT NULL,
    content       TEXT  NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comment_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    CONSTRAINT fk_comment_user      FOREIGN KEY (user_id)      REFERENCES users(id)     ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Balící sady
-- ------------------------------------------------------------
CREATE TABLE packing_sets (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    name        VARCHAR(200) NOT NULL,
    description TEXT         DEFAULT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_set_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Propojovací tabulka M:N — sady a vybavení
-- ------------------------------------------------------------
CREATE TABLE set_items (
    set_id        INT NOT NULL,
    equipment_id  INT NOT NULL,
    PRIMARY KEY (set_id, equipment_id),
    CONSTRAINT fk_setitem_set       FOREIGN KEY (set_id)       REFERENCES packing_sets(id) ON DELETE CASCADE,
    CONSTRAINT fk_setitem_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Ukázková data — kategorie
-- ------------------------------------------------------------
INSERT INTO categories (name) VALUES 
('Těla'),
('Objektivy'),
('Světla'),
('Doplňky');

-- ------------------------------------------------------------
-- Testovací administrátor (heslo: Admin1234)
-- ------------------------------------------------------------
INSERT INTO users (username, email, password, is_admin) VALUES
    ('admin', 'admin@fotobatoh.cz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
