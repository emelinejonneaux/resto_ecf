-- ========================
-- ROLE
-- ========================
CREATE TABLE role (
    role_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

INSERT INTO role VALUES
(1, 'administrateur'),
(3, 'utilisateur'),
(2, 'employé');

-- ========================
-- UTILISATEUR
-- ========================
CREATE TABLE utilisateur (
    utilisateur_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    telephone VARCHAR(20),
    ville VARCHAR(100),
    pays VARCHAR(100),
    adresse TEXT,
    role_id INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif TINYINT(1) DEFAULT 1
);

INSERT INTO utilisateur VALUES
(1, 'admin@mail.com', 'hash_admin', 'Jean', 'Dupont', '0600000000', 'Lille', 'France', '10 rue de Lille', 1, NOW(), 1),
(2, 'client1@mail.com', 'hash_client1', 'Marie', 'Martin', '0611111111', 'Roubaix', 'France', '25 avenue de Paris', 2, NOW(), 1),
(3, 'client2@mail.com', 'hash_client2', 'Paul', 'Durand', '0622222222', 'Tourcoing', 'France', '5 rue Nationale', 2, NOW(), 1),
(4, 'employe@mail.com', 'hash_employe', 'Luc', 'Bernard', '0633333333', 'Lille', 'France', '8 rue Victor Hugo', 3, NOW(), 1);

-- ========================
-- HORAIRE
-- ========================
CREATE TABLE horaire (
    horaire_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(50),
    heure_ouverture VARCHAR(50),
    heure_fermeture VARCHAR(50)
);

INSERT INTO horaire VALUES
(1, 'Lundi', '11:00', '22:00'),
(2, 'Mardi', '11:00', '22:00'),
(3, 'Mercredi', '11:00', '22:00'),
(4, 'Jeudi', '11:00', '23:00'),
(5, 'Vendredi', '11:00', '23:30'),
(6, 'Samedi', '12:00', '23:30'),
(7, 'Dimanche', '12:00', '21:00');

-- ========================
-- REGIME
-- ========================
CREATE TABLE regime (
    regime_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

INSERT INTO regime VALUES
(1, 'Classique'),
(2, 'Végétarien'),
(3, 'Vegan'),
(4, 'Sans gluten'),
(5, 'Halal');

-- ========================
-- ALLERGENE
-- ========================
CREATE TABLE allergene (
    allergene_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

INSERT INTO allergene VALUES
(1, 'Gluten'),
(2, 'Lactose'),
(3, 'Arachides'),
(4, 'Fruits de mer'),
(5, 'Soja');

-- ========================
-- THEME
-- ========================
CREATE TABLE theme (
    theme_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

INSERT INTO theme VALUES
(1, 'Italien'),
(2, 'Américain'),
(3, 'Gastronomique'),
(4, 'Oriental'),
(5, 'Asiatique'),
(6, 'Street Food'),
(7, 'Traditionnel');

-- ========================
-- MENU
-- ========================
CREATE TABLE menu (
    menu_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(50),
    nombre_personne_minimum INT(11),
    prix_par_personne DOUBLE,
    description VARCHAR(255),
    quantite_restante INT(11),
    regime_id INT(11)
);

INSERT INTO menu VALUES
(1, 'Menu Italien', 10, 18.00, 'Menu complet italien', 50, 1),
(2, 'Menu BBQ', 15, 22.00, 'Grillades et accompagnements', 30, 2),
(3, 'Menu Végétarien', 8, 16.00, 'Plats sans viande', 40, 3),
(4, 'Menu Gastronomique', 20, 35.00, 'Menu haut de gamme', 15, 1),
(5, 'Menu Anniversaire', 12, 20.00, 'Menu festif', 25, 2),
(6, 'Menu Entreprise', 25, 28.00, 'Formule pro', 20, 1),
(7, 'Menu Vegan', 10, 19.00, '100% végétal', 35, 2),
(8, 'Menu Brunch', 6, 15.00, 'Brunch sucré/salé', 45, 3),
(9, 'Menu Mariage', 50, 45.00, 'Menu premium', 10, 1),
(10, 'Menu Street Food', 8, 14.00, 'Burgers & wraps', 60, 2);

-- ========================
-- PLAT
-- ========================
CREATE TABLE plat (
    plat_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(50),
    photo TEXT
);

INSERT INTO plat VALUES
(1, 'Pizza Margherita', 'pizza.jpg'),
(2, 'Burger Maison', 'burger.jpg'),
(3, 'Tiramisu', 'tiramisu.jpg'),
(4, 'Salade César', 'salade.jpg'),
(5, 'Wrap Vegan', 'wrap.jpg'),
(6, 'Pancakes', 'pancakes.jpg'),
(7, 'Risotto', 'risotto.jpg'),
(8, 'Cheesecake', 'cheesecake.jpg'),
(9, 'Brochettes BBQ', 'bbq.jpg'),
(10, 'Pâtes Carbonara', 'pates.jpg');

-- ========================
-- MENU_PLAT
-- ========================
CREATE TABLE menu_plat (
    menu_id INT(11),
    plat_id INT(11),
    PRIMARY KEY (menu_id, plat_id)
);

INSERT INTO menu_plat VALUES
(1,1),(1,3),(2,2),(3,5),(4,7),(5,3),(6,2),(7,5),(8,6),(9,1),(10,2);

-- ========================
-- MENU_THEME
-- ========================
CREATE TABLE menu_theme (
    menu_id INT(11),
    theme_id INT(11),
    PRIMARY KEY (menu_id, theme_id)
);

INSERT INTO menu_theme VALUES
(1,1),(2,2),(3,3),(4,3),(5,2),(6,3),(7,3),(8,1),(9,3),(10,2);

-- ========================
-- PLAT_ALLERGENE
-- ========================
CREATE TABLE plat_allergene (
    plat_id INT(11),
    allergene_id INT(11),
    PRIMARY KEY (plat_id, allergene_id)
);

INSERT INTO plat_allergene VALUES
(1,1),(1,2),(2,1),(2,2),(3,2),(4,2),(5,3),(6,1),(7,2),(8,2),(9,1),(10,1);

-- ========================
-- COMMANDE
-- ========================
CREATE TABLE commande (
    commande_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50),
    date_commande DATE,
    date_prestation DATE,
    heure_livraison TIME,
    prix_menu DOUBLE,
    nombre_personne INT(11),
    prix_livraison DOUBLE,
    statut VARCHAR(50),
    pret_materiel TINYINT(1),
    restitution_materiel TINYINT(1),
    utilisateur_id INT(11),
    motif_annulation TEXT,
    contact_annulation VARCHAR(50),
    adresse_livraison TEXT,
    ville_livraison VARCHAR(100),
    reduction_appliquee TINYINT(1) DEFAULT 0,
    menu_id INT(11)
);

INSERT INTO commande VALUES
(1,'CMD001','2026-04-01','2026-04-10','12:00:00',180,10,15,'validée',1,1,1,NULL,NULL,'10 rue de Lille','Lille',0,1),
(2,'CMD002','2026-04-05','2026-04-15','19:30:00',250,15,20,'en attente',1,0,2,NULL,NULL,'25 avenue de Paris','Roubaix',1,2);

-- ========================
-- AVIS
-- ========================
CREATE TABLE avis (
    avis_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    note INT(11),
    description TEXT,
    statut VARCHAR(50),
    utilisateur_id INT(11)
);

INSERT INTO avis VALUES
(1,5,'Excellent !','validé',1),
(2,4,'Très bon repas','validé',2),
(3,3,'Correct','en attente',1);

SET FOREIGN_KEY_CHECKS = 1;
