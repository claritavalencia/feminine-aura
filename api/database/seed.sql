-- ============================================
-- Données de test pour Feminine Aura
-- ============================================

USE feminine_aura;

-- ============================================
-- UTILISATRICES (mot de passe: "password123" hashé en bcrypt)
-- ============================================
INSERT INTO utilisatrice (email, mot_de_passe, role) VALUES
('admin@feminineaura.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('client1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client'),
('client2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

-- ============================================
-- CATÉGORIES
-- ============================================
INSERT INTO categorie (nom, description, slug) VALUES
('Soutiens-gorge', 'Collection de soutiens-gorge élégants et confortables', 'soutiens-gorge'),
('Culottes', 'Culottes et strings raffinés', 'culottes'),
('Ensembles', 'Ensembles assortis pour un look complet', 'ensembles'),
('Nuisettes', 'Nuisettes et chemises de nuit sensuelles', 'nuisettes'),
('Bodys', 'Bodys élégants et modernes', 'bodys');

-- ============================================
-- PRODUITS
-- ============================================
INSERT INTO produit (id_categorie, nom, description, prix, stock, couleur, taille, tissu, image_url, actif) VALUES
-- Ensembles
(3, 'Femina satin', 'Pensé pour la femme moderne, il incarne l\'équilibre parfait entre charisme et douceur. Que ce soit pour une occasion spéciale ou pour sublimer votre confiance chaque jour, cette création est une ode à la féminité.', 74.99, 50, 'Rouge', 'M', 'Satin', '/red-lingerie-bra.jpg', TRUE),
(3, 'Sweet tissu', 'Ensemble délicat en dentelle avec finitions satinées', 74.99, 45, 'Bordeaux', 'M', 'Dentelle', '/burgundy-lingerie-set.jpg', TRUE),
(3, 'Love brush', 'Ensemble romantique avec détails en dentelle', 70.99, 40, 'Rose', 'M', 'Coton', '/pink-elegant-lingerie.jpg', TRUE),
(3, 'Love mood', 'Ensemble élégant pour toutes occasions', 74.99, 35, 'Noir', 'L', 'Satin', '/elegant-lingerie-bra.jpg', TRUE),
(3, 'Love Kit', 'Set complet pour un look sophistiqué', 75.00, 30, 'Bordeaux', 'M', 'Satin', '/luxury-lingerie-set.jpg', TRUE),
(3, 'Elegance', 'Ensemble raffiné en dentelle noire', 72.20, 25, 'Noir', 'S', 'Dentelle', '/black-lingerie-elegant.jpg', TRUE),
(3, 'Premium collection', 'Collection premium avec détails luxueux', 73.00, 38, 'Bordeaux', 'L', 'Satin', '/premium-lingerie-collection.jpg', TRUE),
(3, 'Classique', 'Design intemporel et confortable', 75.00, 42, 'Blanc', 'M', 'Coton', '/classic-lingerie-design.jpg', TRUE),

-- Soutiens-gorge
(1, 'Soutien-gorge Rouge Passion', 'Soutien-gorge balconnet en dentelle', 39.99, 60, 'Rouge', 'M', 'Dentelle', '/red-lingerie-bra.jpg', TRUE),
(1, 'Soutien-gorge Rose Douceur', 'Soutien-gorge triangle sans armature', 35.99, 55, 'Rose', 'S', 'Coton', '/pink-lingerie-top.jpg', TRUE),
(1, 'Soutien-gorge Noir Élégance', 'Soutien-gorge push-up en satin', 42.99, 48, 'Noir', 'M', 'Satin', '/elegant-lingerie-bra.jpg', TRUE),

-- Culottes
(2, 'Culotte Rouge Charme', 'Culotte en dentelle assortie', 19.99, 80, 'Rouge', 'M', 'Dentelle', '/red-lingerie-bra.jpg', TRUE),
(2, 'Culotte Rose Confort', 'Culotte en coton doux', 15.99, 75, 'Rose', 'M', 'Coton', '/pink-elegant-lingerie.jpg', TRUE),
(2, 'String Noir Séduction', 'String en satin avec détails dentelle', 22.99, 65, 'Noir', 'S', 'Satin', '/black-lingerie-elegant.jpg', TRUE);

-- ============================================
-- PANIERS (exemples)
-- ============================================
INSERT INTO panier (id_utilisatrice) VALUES
(2),
(3);

-- ============================================
-- LIGNES PANIER (exemples)
-- ============================================
INSERT INTO ligne_panier (id_panier, id_produit, quantite, prix_unitaire) VALUES
(1, 1, 1, 74.99),
(1, 9, 1, 39.99),
(2, 3, 2, 70.99);

-- ============================================
-- PAIEMENTS (exemples)
-- ============================================
INSERT INTO paiement (mode_paiement, montant_paye, statut_paiement, transaction_id) VALUES
('carte', 114.98, 'complete', 'TXN_001_2024'),
('paypal', 141.98, 'complete', 'TXN_002_2024');

-- ============================================
-- COMMANDES (exemples)
-- ============================================
INSERT INTO commande (id_utilisatrice, id_paiement, numero_commande, total_commande, statut_commande, adresse_livraison, ville, code_postal, pays, telephone) VALUES
(2, 1, 'CMD-2024-0001', 114.98, 'expediee', '15 Rue de la Paix', 'Paris', '75001', 'France', '+33612345678'),
(3, 2, 'CMD-2024-0002', 141.98, 'confirmee', '25 Avenue des Champs', 'Lyon', '69001', 'France', '+33698765432');

-- ============================================
-- LIGNES COMMANDE
-- ============================================
INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire, sous_total) VALUES
(1, 1, 1, 74.99, 74.99),
(1, 9, 1, 39.99, 39.99),
(2, 3, 2, 70.99, 141.98);

-- ============================================
-- AVIS (exemples)
-- ============================================
INSERT INTO avis (id_utilisatrice, id_produit, note, commentaire, approuve) VALUES
(2, 1, 5, 'J\'ai rarement porté une lingerie aussi élégante et confortable à la fois. Le satin est incroyablement doux, et les finitions en dentelle donnent une vraie touche de luxe.', TRUE),
(3, 3, 5, 'L\'équilibre parfait entre charisme et douceur, cette création est une ode à la féminité. Chaque détail a été pensé avec soin.', TRUE),
(2, 5, 4, 'Qualité exceptionnelle, design moderne et élégant. J\'adore cette collection !', TRUE);

-- ============================================
-- FAVORIS (exemples)
-- ============================================
INSERT INTO favori (id_utilisatrice, id_produit) VALUES
(2, 2),
(2, 4),
(3, 1),
(3, 5);

-- ============================================
-- ACTIONS ADMIN (exemples)
-- ============================================
INSERT INTO admin_action (id_utilisatrice, action, table_concernee, id_element, details) VALUES
(1, 'Création produit', 'produit', 1, 'Ajout du produit "Femina satin"'),
(1, 'Validation avis', 'avis', 1, 'Approbation de l\'avis client #1');
