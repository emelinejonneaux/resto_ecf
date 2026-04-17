-- =========================================
-- BASE DE DONNÉES RESTAURATION - ECF
-- SQL VERSION (MySQL)
-- =========================================

CREATE DATABASE IF NOT EXISTS resto_ecf;
USE resto_ecf;

-- =========================================
-- USERS
-- =========================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    role ENUM('user', 'employe', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
--  MENUS
-- =========================================
CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    theme VARCHAR(100),
    prix DECIMAL(10,2) NOT NULL,
    nb_personnes_min INT DEFAULT 1,
    stock INT DEFAULT 0,
    conditions TEXT,
    regime VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- PLATS
-- =========================================
CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    type ENUM('entrée', 'plat', 'dessert') NOT NULL
);

-- =========================================
-- MENU_PLAT
-- =========================================
CREATE TABLE menu_plat (
    menu_id INT,
    plat_id INT,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plats(id) ON DELETE CASCADE
);

-- =========================================
-- ALLERGENES
-- =========================================
CREATE TABLE allergenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- =========================================
-- PLAT_ALLERGENE 
-- =========================================
CREATE TABLE plat_allergene (
    plat_id INT,
    allergene_id INT,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plats(id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergenes(id) ON DELETE CASCADE
);

-- =========================================
-- COMMANDES
-- =========================================
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    menu_id INT,
    date_livraison DATE,
    heure_livraison TIME,
    adresse TEXT,
    prix_total DECIMAL(10,2),
    statut ENUM('en_attente', 'validée', 'préparée', 'livrée', 'annulée') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id)
);

-- =========================================
-- AVIS
-- =========================================
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    commande_id INT,
    note INT CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    valide BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
);

-- =========================================
-- DONNÉES DE TEST
-- =========================================

INSERT INTO users (nom, prenom, email, password, telephone, adresse, role)
VALUES 
('Dupont', 'Jean', 'jean@mail.com', 'hashed_password', '0600000000', 'Lille', 'user'),
('Martin', 'Sophie', 'sophie@mail.com', 'hashed_password', '0611111111', 'Roubaix', 'admin');

INSERT INTO menus (titre, description, theme, prix, nb_personnes_min, stock, regime)
VALUES 
('Menu Classique', 'Entrée + Plat + Dessert', 'Traditionnel', 25.00, 1, 10, 'standard'),
('Menu Festif', 'Menu spécial fête', 'Noël', 45.00, 2, 5, 'sans porc');

INSERT INTO plats (nom, type)
VALUES 
('Salade composée', 'entrée'),
('Poulet rôti', 'plat'),
('Tiramisu', 'dessert');

INSERT INTO allergenes (nom)
VALUES 
('Gluten'),
('Lactose'),
('Arachides');

INSERT INTO menu_plat (menu_id, plat_id)
VALUES 
(1, 1),
(1, 2),
(1, 3);

INSERT INTO commandes (user_id, menu_id, date_livraison, heure_livraison, adresse, prix_total, statut)
VALUES 
(1, 1, '2026-05-10', '12:30:00', 'Lille', 25.00, 'en_attente');

INSERT INTO avis (user_id, commande_id, note, commentaire, valide)
VALUES 
(1, 1, 5, 'Très bon menu !', TRUE);