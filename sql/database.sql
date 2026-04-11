CREATE DATABASE IF NOT EXISTS nebula_db;
USE nebula_db;

-- Abonnements (Starter, Gamer, Ultra)
DROP TABLE IF EXISTS offre;
CREATE TABLE offre (
  id_offre INT NOT NULL AUTO_INCREMENT,
  nom_offre VARCHAR(50) NOT NULL,
  prix_mensuel DECIMAL(10,2) NOT NULL DEFAULT 0,
  description TEXT,
  PRIMARY KEY (id_offre),
  UNIQUE KEY (nom_offre)
);

-- Utilisateurs (comptes clients)
DROP TABLE IF EXISTS utilisateur;
CREATE TABLE utilisateur (
  id_user INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nom VARCHAR(50) NOT NULL,
  role VARCHAR(20) DEFAULT 'client',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_user),
  UNIQUE KEY (email)
);

-- Commandes (abonnements actifs)
DROP TABLE IF EXISTS commande;
CREATE TABLE commande (
  id_commande INT NOT NULL AUTO_INCREMENT,
  date_commande TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_ttc DECIMAL(10,2) NOT NULL DEFAULT 0,
  statut VARCHAR(20) DEFAULT 'en_attente',
  id_user INT NOT NULL,
  id_offre INT NOT NULL,
  PRIMARY KEY (id_commande),
  KEY (id_user),
  KEY (id_offre),
  FOREIGN KEY (id_user) REFERENCES utilisateur(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_offre) REFERENCES offre(id_offre)
);

-- Messages (formulaire de contact)
DROP TABLE IF EXISTS message;
CREATE TABLE message (
  id_msg INT NOT NULL AUTO_INCREMENT,
  sujet VARCHAR(100) NOT NULL,
  contenu TEXT NOT NULL,
  date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_user INT DEFAULT NULL,
  PRIMARY KEY (id_msg),
  KEY (id_user),
  FOREIGN KEY (id_user) REFERENCES utilisateur(id_user) ON DELETE SET NULL
);

-- Table panier (connexion requise)
DROP TABLE IF EXISTS panier;
CREATE TABLE panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jeu_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    categorie VARCHAR(50) DEFAULT 'jeu',
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_item (user_id, jeu_id)
);

-- Données initiales
INSERT INTO offre (nom_offre, prix_mensuel, description) VALUES
('Starter', 0.00, 'Pour découvrir Nebula — 10h/mois, HD 720p, +25 jeux'),
('Gamer', 24.99, 'Le choix des joueurs passionnés — Illimité, 4K 144FPS, +200 jeux'),
('Ultra', 44.99, 'L''expérience ultime sans compromis — Support 24/7, multi-appareils');
