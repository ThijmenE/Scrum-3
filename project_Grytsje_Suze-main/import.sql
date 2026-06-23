-- =============================================================
-- Grytsje Suze – Database import script
-- Importeer dit bestand via phpMyAdmin of via de terminal:
--   mysql -u gebruikersnaam -p databasenaam < import.sql
-- =============================================================

-- Tabel voor de gebruikers (admin-panel toegang)
CREATE TABLE IF NOT EXISTS users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(255) NOT NULL UNIQUE,
    -- Wachtwoorden worden opgeslagen als bcrypt-hash via password_hash()
    wachtwoord VARCHAR(255) NOT NULL,
    -- Alleen 'owner' of 'admin' zijn geldige rollen
    rol        ENUM('owner','admin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Tabel voor de tassen (portfolio-items)
CREATE TABLE IF NOT EXISTS tassen (
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    naam        VARCHAR(255) NOT NULL,
    beschrijving TEXT,
    -- Bestandspad relatief aan /public/, bijv. "uploads/foto.jpg"
    afbeelding  VARCHAR(255) DEFAULT NULL,
    kleurcode   VARCHAR(7)   NOT NULL DEFAULT '#ffffff',
    -- Optioneel .glb 3D-model, pad relatief aan /public/
    model_3d    VARCHAR(255) DEFAULT '',
    -- Tekstkleur en titelkleur als hex-waarde voor de portfolio-weergave
    tekst_kleur VARCHAR(7)   NOT NULL DEFAULT '#000000',
    titel_kleur VARCHAR(7)   NOT NULL DEFAULT '#000000',
    -- Extra media (afbeeldingen/video's) opgeslagen als JSON-array
    -- Formaat: [{"type":"image","path":"uploads/x.jpg"}, ...]
    media       JSON         DEFAULT (JSON_ARRAY()),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================================
-- Standaard owner-account
-- Wachtwoord: Admin1234!
-- Verander dit wachtwoord direct na de eerste keer inloggen!
-- Hash gegenereerd met: password_hash('Admin1234!', PASSWORD_DEFAULT)
-- =============================================================
INSERT INTO users (email, wachtwoord, rol) VALUES
('owner@example.com', '$2y$10$a9Z3BRQHvx7atUp5QoIGwOdHU4zEQ4XAyn9J.LPsUecTcy4Vlz.3q', 'owner');

-- =============================================================
-- Voorbeeldtassen (optioneel – verwijder deze regels als je
-- met een lege database wilt beginnen)
-- =============================================================
INSERT INTO tassen (naam, beschrijving, afbeelding, kleurcode, tekst_kleur, titel_kleur) VALUES
('Leren Rugzak',       'Een stijlvolle en duurzame leren rugzak, perfect voor dagelijks gebruik.',      NULL, '#f5f0eb', '#333333', '#111111'),
('Linnen Shopper',     'Grote, lichte shopper gemaakt van biologisch linnen. Ideaal voor boodschappen.',NULL, '#e8e0d5', '#333333', '#111111'),
('Zakelijke Laptoptas','Waterafstotende laptoptas met extra vakken voor accessoires en documenten.',     NULL, '#d6cfc7', '#333333', '#111111');
