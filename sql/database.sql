-- Création de la base
CREATE DATABASE IF NOT EXISTS nebula_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE nebula_db;
 
-- Table UTILISATEUR
CREATE TABLE utilisateur (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    role VARCHAR(20) DEFAULT 'client'
);
 
-- Table CATEGORIE (Doit être créée avant PRODUIT)
CREATE TABLE categorie (
    id_cat INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);
 
-- Table OFFRE
CREATE TABLE offre (
    id_offre INT AUTO_INCREMENT PRIMARY KEY,
    nom_offre VARCHAR(50) NOT NULL,
    prix_mensuel DECIMAL(10,2) NOT NULL,
    description TEXT
);
 
-- Table JEU (Catalogue Cloud Gaming)
CREATE TABLE jeu (
    id_jeu INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    genre VARCHAR(50),
    image_url VARCHAR(255),
    date_sortie DATE,
    description TEXT
);
 
-- Données de test pour les jeux
INSERT INTO jeu (titre, genre, image_url) VALUES 
('Cyberpunk 2077', 'RPG', 'assets/img/cyberpunk.jpg'),
('Elden Ring', 'RPG', 'assets/img/eldenring.jpg'),
('Call of Duty', 'FPS', 'assets/img/cod.jpg');
 
-- Table PRODUIT
CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom_produit VARCHAR(100) NOT NULL,
    description TEXT,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    stock INT DEFAULT 0,
    id_cat INT NOT NULL,
    FOREIGN KEY (id_cat) REFERENCES categorie(id_cat)
);
 
-- Table COMMANDE
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_ttc DECIMAL(10,2) NOT NULL,
    statut VARCHAR(20) DEFAULT 'en attente',
    id_user INT NOT NULL,
    id_offre INT,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user),
    FOREIGN KEY (id_offre) REFERENCES offre(id_offre)
);
 
-- Table LIGNE_COMMANDE (Table de liaison)
CREATE TABLE ligne_commande (
    id_commande INT,
    id_produit INT,
    quantite INT NOT NULL,
    PRIMARY KEY (id_commande, id_produit),
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
);
 
-- Table MESSAGE
CREATE TABLE message (
    id_msg INT AUTO_INCREMENT PRIMARY KEY,
    sujet VARCHAR(100) NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user)
);