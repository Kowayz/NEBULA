-- Schema PostgreSQL pour Supabase
-- Coller dans l'éditeur SQL de Supabase

CREATE TABLE IF NOT EXISTS categorie (
  id_cat   SERIAL PRIMARY KEY,
  libelle  VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS offre (
  id_offre     SERIAL PRIMARY KEY,
  nom_offre    VARCHAR(50) NOT NULL,
  prix_mensuel NUMERIC(10,2) NOT NULL,
  description  TEXT
);

CREATE TABLE IF NOT EXISTS utilisateur (
  id_user  SERIAL PRIMARY KEY,
  email    VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nom      VARCHAR(50) NOT NULL,
  role     VARCHAR(20) DEFAULT 'client'
);

CREATE TABLE IF NOT EXISTS jeu (
  id_jeu      SERIAL PRIMARY KEY,
  titre       VARCHAR(100) NOT NULL,
  genre       VARCHAR(150),
  developpeur VARCHAR(100),
  image_url   VARCHAR(255),
  date_sortie DATE,
  description TEXT
);

CREATE TABLE IF NOT EXISTS commande (
  id_commande  SERIAL PRIMARY KEY,
  date_commande TIMESTAMP NOT NULL DEFAULT NOW(),
  total_ttc    NUMERIC(10,2) NOT NULL,
  statut       VARCHAR(20) DEFAULT 'en attente',
  id_user      INT NOT NULL REFERENCES utilisateur(id_user),
  id_offre     INT REFERENCES offre(id_offre)
);

CREATE TABLE IF NOT EXISTS produit (
  id_produit   SERIAL PRIMARY KEY,
  nom_produit  VARCHAR(100) NOT NULL,
  description  TEXT,
  prix_unitaire NUMERIC(10,2) NOT NULL,
  image_url    VARCHAR(255),
  stock        INT DEFAULT 0,
  id_cat       INT NOT NULL REFERENCES categorie(id_cat)
);

CREATE TABLE IF NOT EXISTS ligne_commande (
  id_commande INT NOT NULL REFERENCES commande(id_commande),
  id_produit  INT NOT NULL REFERENCES produit(id_produit),
  quantite    INT NOT NULL,
  PRIMARY KEY (id_commande, id_produit)
);

CREATE TABLE IF NOT EXISTS message (
  id_msg    SERIAL PRIMARY KEY,
  sujet     VARCHAR(100) NOT NULL,
  contenu   TEXT NOT NULL,
  date_envoi TIMESTAMP DEFAULT NOW(),
  id_user   INT REFERENCES utilisateur(id_user)
);

-- Données initiales (jeux)
INSERT INTO jeu (titre, genre, developpeur, image_url, date_sortie, description) VALUES
('The Witcher 4', 'Action-RPG,Monde ouvert,Fantasy', 'CD Projekt Red', 'assets/img/witcher4.jpg', '2026-10-15', 'Le début d''une nouvelle saga pour l''univers de The Witcher.'),
('Marvel''s Wolverine', 'Action-Aventure,Beat''em up', 'Insomniac Games', 'assets/img/wolverine.jpg', '2026-09-10', 'Incarnez le mutant emblématique Logan dans une aventure mature et brutale.'),
('Grand Theft Auto VI', 'Action-aventure, Monde ouvert', 'Rockstar North', 'assets/img/gta6.jpg', '2026-11-19', 'Le jeu se déroule dans l''État de Leonida et suit le duo criminel Lucia et Jason.'),
('Mafia: The Old Country', 'Action-aventure,Monde ouvert,Crime', 'Hangar 13', 'assets/img/mafia.jpg', '2026-05-20', 'Plongez aux origines du crime organisé dans la Sicile des années 1900.'),
('Control 2', 'Action-aventure,Paranormal,TPS', 'Remedy Entertainment', 'assets/img/control2.jpg', '2026-11-12', 'Jesse Faden retourne au Bureau Fédéral du Contrôle.'),
('Persona 6', 'JRPG,Simulation de vie', 'Atlus', 'assets/img/persona6.jpg', '2026-08-08', 'Découvrez un tout nouveau casting d''étudiants confrontés à une menace psychologique.');

-- Données initiales (offres)
INSERT INTO offre (nom_offre, prix_mensuel, description) VALUES
('Starter', 0.00,  'Pour découvrir Nebula'),
('Gamer',   24.99, 'Le choix des joueurs passionnés'),
('Ultra',   44.99, 'L''expérience ultime sans compromis');
