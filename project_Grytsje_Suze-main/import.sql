-- Tabel voor de gebruikers (users)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    -- Let op: sla wachtwoorden in je backend altijd op als een veilige hash (bijv. via bcrypt), nooit als platte tekst!
    wachtwoord VARCHAR(255) NOT NULL,
    -- ENUM zorgt ervoor dat alleen 'owner' of 'admin' als rol gekozen kan worden.
    rol ENUM('owner', 'admin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel voor de tassen
CREATE TABLE tassen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL,
    -- In de afbeelding kolom sla je de bestandsnaam of de URL van de afbeelding op.
    afbeelding VARCHAR(255) DEFAULT NULL,
    beschrijving TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Dummy data toevoegen om te testen
-- --------------------------------------------------------

INSERT INTO users (email, wachtwoord, rol) VALUES
('owner@voorbeeld.nl', 'gehashte_wachtwoord_string_hier', 'owner'),
('admin@voorbeeld.nl', 'gehashte_wachtwoord_string_hier', 'admin');

INSERT INTO tassen (naam, afbeelding, beschrijving) VALUES
('Leren Rugzak', 'https://via.placeholder.com/150/000000/FFFFFF/?text=Tas+1', 'Een stijlvolle en duurzame leren rugzak, perfect voor dagelijks gebruik en werk.'),
('Linnen Shopper', 'https://via.placeholder.com/150/333333/FFFFFF/?text=Tas+2', 'Grote, lichte shopper gemaakt van biologisch linnen. Ideaal voor de boodschappen.'),
('Zakelijke Laptoptas', 'https://via.placeholder.com/150/666666/FFFFFF/?text=Tas+3', 'Waterafstotende laptoptas met extra vakken voor accessoires en documenten.');

ALTER TABLE tassen ADD COLUMN kleurcode VARCHAR(7) DEFAULT '#ffffff';

INSERT INTO users (email, wachtwoord, rol) VALUES
('test@example.com', '$2y$10$a9Z3BRQHvx7atUp5QoIGwOdHU4zEQ4XAyn9J.LPsUecTcy4Vlz.3q', 'admin');