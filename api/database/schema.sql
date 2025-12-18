-- ============================================
-- Base de données Feminine Aura
-- ============================================

CREATE DATABASE IF NOT EXISTS feminine_aura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE feminine_aura;

-- ============================================
-- Table UTILISATRICE
-- ============================================
CREATE TABLE IF NOT EXISTS utilisatrice (
    id_utilisatrice INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client' NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table CATEGORIE
-- ============================================
CREATE TABLE IF NOT EXISTS categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    slug VARCHAR(100) NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table PRODUIT
-- ============================================
CREATE TABLE IF NOT EXISTS produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0 NOT NULL,
    couleur VARCHAR(50),
    taille VARCHAR(20),
    tissu VARCHAR(100),
    image_url VARCHAR(500),
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE CASCADE,
    INDEX idx_categorie (id_categorie),
    INDEX idx_nom (nom),
    INDEX idx_prix (prix)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table PANIER
-- ============================================
CREATE TABLE IF NOT EXISTS panier (
    id_panier INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisatrice INT NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    INDEX idx_utilisatrice (id_utilisatrice)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table LIGNE_PANIER
-- ============================================
CREATE TABLE IF NOT EXISTS ligne_panier (
    id_ligne_panier INT AUTO_INCREMENT PRIMARY KEY,
    id_panier INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT DEFAULT 1 NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_panier) REFERENCES panier(id_panier) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    UNIQUE KEY unique_panier_produit (id_panier, id_produit),
    INDEX idx_panier (id_panier),
    INDEX idx_produit (id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table PAIEMENT
-- ============================================
CREATE TABLE IF NOT EXISTS paiement (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    mode_paiement ENUM('carte', 'paypal', 'virement') NOT NULL,
    montant_paye DECIMAL(10, 2) NOT NULL,
    statut_paiement ENUM('en_attente', 'complete', 'echoue', 'rembourse') DEFAULT 'en_attente',
    transaction_id VARCHAR(255) UNIQUE,
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut_paiement),
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table COMMANDE
-- ============================================
CREATE TABLE IF NOT EXISTS commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisatrice INT NOT NULL,
    id_paiement INT,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    total_commande DECIMAL(10, 2) NOT NULL,
    statut_commande ENUM('en_attente', 'confirmee', 'en_preparation', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    adresse_livraison TEXT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(20) NOT NULL,
    pays VARCHAR(100) DEFAULT 'France',
    telephone VARCHAR(20),
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    FOREIGN KEY (id_paiement) REFERENCES paiement(id_paiement) ON DELETE SET NULL,
    INDEX idx_utilisatrice (id_utilisatrice),
    INDEX idx_numero (numero_commande),
    INDEX idx_statut (statut_commande),
    INDEX idx_date (date_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table LIGNE_COMMANDE
-- ============================================
CREATE TABLE IF NOT EXISTS ligne_commande (
    id_ligne_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT DEFAULT 1 NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    sous_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    INDEX idx_commande (id_commande),
    INDEX idx_produit (id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table AVIS
-- ============================================
CREATE TABLE IF NOT EXISTS avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisatrice INT NOT NULL,
    id_produit INT NOT NULL,
    note INT NOT NULL CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approuve BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    UNIQUE KEY unique_utilisatrice_produit (id_utilisatrice, id_produit),
    INDEX idx_produit (id_produit),
    INDEX idx_note (note),
    INDEX idx_approuve (approuve)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table FAVORI
-- ============================================
CREATE TABLE IF NOT EXISTS favori (
    id_favori INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisatrice INT NOT NULL,
    id_produit INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    UNIQUE KEY unique_utilisatrice_produit (id_utilisatrice, id_produit),
    INDEX idx_utilisatrice (id_utilisatrice),
    INDEX idx_produit (id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table ADMIN_ACTION (Historique des actions admin)
-- ============================================
CREATE TABLE IF NOT EXISTS admin_action (
    id_action INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisatrice INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    table_concernee VARCHAR(100),
    id_element INT,
    details TEXT,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    INDEX idx_utilisatrice (id_utilisatrice),
    INDEX idx_table (table_concernee),
    INDEX idx_date (date_action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table RAPPORT (pour les statistiques)
-- ============================================
CREATE TABLE IF NOT EXISTS rapport (
    id_rapport INT AUTO_INCREMENT PRIMARY KEY,
    type_rapport VARCHAR(100) NOT NULL,
    periode_debut DATE,
    periode_fin DATE,
    donnees JSON,
    genere_par INT,
    date_generation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (genere_par) REFERENCES utilisatrice(id_utilisatrice) ON DELETE SET NULL,
    INDEX idx_type (type_rapport),
    INDEX idx_date (date_generation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table de liaison UTILISATRICE_RAPPORT
-- ============================================
CREATE TABLE IF NOT EXISTS utilisatrice_rapport (
    id_utilisatrice INT NOT NULL,
    id_rapport INT NOT NULL,
    date_acces TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_utilisatrice, id_rapport),
    FOREIGN KEY (id_utilisatrice) REFERENCES utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    FOREIGN KEY (id_rapport) REFERENCES rapport(id_rapport) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
