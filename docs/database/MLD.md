# 📋 MODÈLE LOGIQUE DE DONNÉES (MLD) - Feminine Aura

## 🎯 Vue d'ensemble

Schéma logique complet de la base de données `feminine_aura` avec toutes les tables, colonnes, types de données, contraintes et relations.

**SGBD** : MySQL 8.0
**Charset** : utf8mb4_unicode_ci
**Moteur** : InnoDB
**Collation** : utf8mb4_unicode_ci

---

## 📊 Liste des Tables (13 tables)

### Tables Principales (5)
1. `utilisatrice` - Utilisateurs du système
2. `categorie` - Catégories de produits
3. `produit` - Catalogue produits
4. `commande` - Commandes clients
5. `paiement` - Transactions financières

### Tables de Liaison (5)
6. `ligne_panier` - Articles dans les paniers
7. `ligne_commande` - Articles dans les commandes
8. `favori` - Produits favoris
9. `avis` - Avis clients sur produits
10. `utilisatrice_rapport` - Accès aux rapports

### Tables de Gestion (3)
11. `panier` - Paniers utilisateurs
12. `admin_action` - Historique actions admin
13. `rapport` - Rapports statistiques

---

## 📐 Schéma Logique Détaillé

### 1. 👤 TABLE : utilisatrice

**Description** : Utilisateurs du système (clients et administrateurs)

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_utilisatrice` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | Email de connexion |
| `mot_de_passe` | VARCHAR(255) | NOT NULL | Hash bcrypt du mot de passe |
| `role` | ENUM('client','admin') | NOT NULL, DEFAULT 'client' | Rôle de l'utilisateur |
| `date_creation` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date d'inscription |
| `date_modification` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Dernière modification |

**Index** :
- `PRIMARY KEY` (id_utilisatrice)
- `UNIQUE KEY` (email)
- `INDEX idx_email` (email)

**Relations** :
- → `panier` (1:1)
- → `commande` (1:N)
- → `avis` (1:N)
- → `favori` (1:N)
- → `admin_action` (1:N)
- → `rapport` (1:N via genere_par)
- → `utilisatrice_rapport` (N:M)

---

### 2. 📁 TABLE : categorie

**Description** : Catégories de classification des produits

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_categorie` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `nom` | VARCHAR(100) | NOT NULL | Nom de la catégorie |
| `description` | TEXT | NULL | Description détaillée |
| `slug` | VARCHAR(100) | NOT NULL, UNIQUE | Identifiant URL-friendly |
| `date_creation` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de création |

**Index** :
- `PRIMARY KEY` (id_categorie)
- `UNIQUE KEY` (slug)
- `INDEX idx_slug` (slug)

**Relations** :
- → `produit` (1:N)

**Exemples de données** :
- Soutiens-gorge (slug: `soutiens-gorge`)
- Culottes (slug: `culottes`)
- Ensembles (slug: `ensembles`)
- Nuisettes (slug: `nuisettes`)

---

### 3. 📦 TABLE : produit

**Description** : Catalogue des produits vendus

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_produit` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_categorie` | INT | NOT NULL, FK | Catégorie du produit |
| `nom` | VARCHAR(255) | NOT NULL | Nom du produit |
| `description` | TEXT | NULL | Description détaillée |
| `prix` | DECIMAL(10,2) | NOT NULL | Prix en euros |
| `stock` | INT | NOT NULL, DEFAULT 0 | Quantité en stock |
| `couleur` | VARCHAR(50) | NULL | Couleur principale |
| `taille` | VARCHAR(20) | NULL | Taille (XS, S, M, L, XL) |
| `tissu` | VARCHAR(100) | NULL | Composition textile |
| `image_url` | VARCHAR(500) | NULL | URL de l'image principale |
| `actif` | BOOLEAN | DEFAULT TRUE | Produit actif/visible |
| `date_creation` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date d'ajout |
| `date_modification` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Dernière modification |

**Index** :
- `PRIMARY KEY` (id_produit)
- `FOREIGN KEY` (id_categorie) → categorie(id_categorie) ON DELETE CASCADE
- `INDEX idx_categorie` (id_categorie)
- `INDEX idx_nom` (nom)
- `INDEX idx_prix` (prix)

**Relations** :
- ← `categorie` (N:1)
- → `ligne_panier` (1:N)
- → `ligne_commande` (1:N)
- → `avis` (1:N)
- → `favori` (1:N)

**Contraintes métier** :
- `prix` > 0
- `stock` >= 0
- `couleur` : Noir, Blanc, Rouge, Rose, Bordeaux, etc.
- `taille` : XS, S, M, L, XL
- `tissu` : Satin, Dentelle, Coton, Soie, etc.

---

### 4. 🛒 TABLE : panier

**Description** : Paniers d'achat des utilisateurs (1 panier par utilisateur)

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_panier` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_utilisatrice` | INT | NOT NULL, UNIQUE, FK | Propriétaire du panier |
| `date_creation` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de création |
| `date_modification` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Dernière modification |

**Index** :
- `PRIMARY KEY` (id_panier)
- `UNIQUE KEY` (id_utilisatrice)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `INDEX idx_utilisatrice` (id_utilisatrice)

**Relations** :
- ← `utilisatrice` (1:1)
- → `ligne_panier` (1:N)

**Note** : Relation 1:1 avec utilisatrice (contrainte UNIQUE sur id_utilisatrice)

---

### 5. 🛍️ TABLE : ligne_panier

**Description** : Articles ajoutés dans les paniers

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_ligne_panier` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_panier` | INT | NOT NULL, FK | Panier parent |
| `id_produit` | INT | NOT NULL, FK | Produit ajouté |
| `quantite` | INT | NOT NULL, DEFAULT 1 | Quantité du produit |
| `prix_unitaire` | DECIMAL(10,2) | NOT NULL | Prix au moment de l'ajout |
| `date_ajout` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date d'ajout |

**Index** :
- `PRIMARY KEY` (id_ligne_panier)
- `FOREIGN KEY` (id_panier) → panier(id_panier) ON DELETE CASCADE
- `FOREIGN KEY` (id_produit) → produit(id_produit) ON DELETE CASCADE
- `UNIQUE KEY unique_panier_produit` (id_panier, id_produit)
- `INDEX idx_panier` (id_panier)
- `INDEX idx_produit` (id_produit)

**Relations** :
- ← `panier` (N:1)
- ← `produit` (N:1)

**Contraintes métier** :
- Un produit ne peut apparaître qu'une fois par panier (UNIQUE sur id_panier + id_produit)
- `quantite` >= 1
- `prix_unitaire` > 0

---

### 6. 💳 TABLE : paiement

**Description** : Transactions de paiement

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_paiement` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `mode_paiement` | ENUM('carte','paypal','virement') | NOT NULL | Mode de paiement |
| `montant_paye` | DECIMAL(10,2) | NOT NULL | Montant de la transaction |
| `statut_paiement` | ENUM('en_attente','complete','echoue','rembourse') | DEFAULT 'en_attente' | Statut du paiement |
| `transaction_id` | VARCHAR(255) | UNIQUE | ID de transaction externe |
| `date_paiement` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de la transaction |

**Index** :
- `PRIMARY KEY` (id_paiement)
- `UNIQUE KEY` (transaction_id)
- `INDEX idx_statut` (statut_paiement)
- `INDEX idx_transaction` (transaction_id)

**Relations** :
- → `commande` (1:N)

**Valeurs ENUM** :
- `mode_paiement` :
  - `carte` - Carte bancaire (CB, Visa, Mastercard)
  - `paypal` - PayPal
  - `virement` - Virement bancaire

- `statut_paiement` :
  - `en_attente` - Paiement initié mais non confirmé
  - `complete` - Paiement réussi
  - `echoue` - Paiement échoué
  - `rembourse` - Paiement remboursé

---

### 7. 📦 TABLE : commande

**Description** : Commandes passées par les clients

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_commande` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_utilisatrice` | INT | NOT NULL, FK | Client ayant passé la commande |
| `id_paiement` | INT | NULL, FK | Paiement associé |
| `numero_commande` | VARCHAR(50) | NOT NULL, UNIQUE | Numéro de commande unique |
| `total_commande` | DECIMAL(10,2) | NOT NULL | Montant total TTC |
| `statut_commande` | ENUM('en_attente','confirmee','en_preparation','expediee','livree','annulee') | DEFAULT 'en_attente' | Statut de la commande |
| `adresse_livraison` | TEXT | NOT NULL | Adresse complète de livraison |
| `ville` | VARCHAR(100) | NOT NULL | Ville de livraison |
| `code_postal` | VARCHAR(20) | NOT NULL | Code postal |
| `pays` | VARCHAR(100) | DEFAULT 'France' | Pays de livraison |
| `telephone` | VARCHAR(20) | NULL | Téléphone de contact |
| `date_commande` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de passage de commande |
| `date_modification` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Dernière modification |

**Index** :
- `PRIMARY KEY` (id_commande)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `FOREIGN KEY` (id_paiement) → paiement(id_paiement) ON DELETE SET NULL
- `UNIQUE KEY` (numero_commande)
- `INDEX idx_utilisatrice` (id_utilisatrice)
- `INDEX idx_numero` (numero_commande)
- `INDEX idx_statut` (statut_commande)
- `INDEX idx_date` (date_commande)

**Relations** :
- ← `utilisatrice` (N:1)
- ← `paiement` (N:1)
- → `ligne_commande` (1:N)

**Valeurs ENUM statut_commande** :
- `en_attente` - Commande créée, paiement en attente
- `confirmee` - Paiement confirmé
- `en_preparation` - Commande en cours de préparation
- `expediee` - Commande expédiée
- `livree` - Commande livrée
- `annulee` - Commande annulée

**Format numero_commande** : `CMD-YYYYMMDD-XXXXX` (ex: CMD-20241214-00001)

---

### 8. 📄 TABLE : ligne_commande

**Description** : Articles commandés dans chaque commande

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_ligne_commande` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_commande` | INT | NOT NULL, FK | Commande parent |
| `id_produit` | INT | NOT NULL, FK | Produit commandé |
| `quantite` | INT | NOT NULL, DEFAULT 1 | Quantité commandée |
| `prix_unitaire` | DECIMAL(10,2) | NOT NULL | Prix unitaire au moment de la commande |
| `sous_total` | DECIMAL(10,2) | NOT NULL | Sous-total (quantite × prix_unitaire) |

**Index** :
- `PRIMARY KEY` (id_ligne_commande)
- `FOREIGN KEY` (id_commande) → commande(id_commande) ON DELETE CASCADE
- `FOREIGN KEY` (id_produit) → produit(id_produit) ON DELETE CASCADE
- `INDEX idx_commande` (id_commande)
- `INDEX idx_produit` (id_produit)

**Relations** :
- ← `commande` (N:1)
- ← `produit` (N:1)

**Contraintes métier** :
- `quantite` >= 1
- `prix_unitaire` > 0
- `sous_total` = `quantite` × `prix_unitaire`

**Note** : Le prix_unitaire est stocké pour garder l'historique (le prix produit peut changer)

---

### 9. ⭐ TABLE : avis

**Description** : Avis et notes des clients sur les produits

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_avis` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_utilisatrice` | INT | NOT NULL, FK | Auteur de l'avis |
| `id_produit` | INT | NOT NULL, FK | Produit évalué |
| `note` | INT | NOT NULL, CHECK (note >= 1 AND note <= 5) | Note sur 5 étoiles |
| `commentaire` | TEXT | NULL | Commentaire textuel |
| `date_avis` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de publication |
| `approuve` | BOOLEAN | DEFAULT FALSE | Avis modéré et approuvé |

**Index** :
- `PRIMARY KEY` (id_avis)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `FOREIGN KEY` (id_produit) → produit(id_produit) ON DELETE CASCADE
- `UNIQUE KEY unique_utilisatrice_produit` (id_utilisatrice, id_produit)
- `INDEX idx_produit` (id_produit)
- `INDEX idx_note` (note)
- `INDEX idx_approuve` (approuve)

**Relations** :
- ← `utilisatrice` (N:1)
- ← `produit` (N:1)

**Contraintes métier** :
- Un utilisateur ne peut laisser qu'un seul avis par produit
- `note` entre 1 et 5 inclus (CHECK)
- Les avis doivent être approuvés par un admin avant affichage public

---

### 10. ❤️ TABLE : favori

**Description** : Produits mis en favoris par les utilisateurs

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_favori` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_utilisatrice` | INT | NOT NULL, FK | Utilisateur propriétaire |
| `id_produit` | INT | NOT NULL, FK | Produit favori |
| `date_ajout` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date d'ajout aux favoris |

**Index** :
- `PRIMARY KEY` (id_favori)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `FOREIGN KEY` (id_produit) → produit(id_produit) ON DELETE CASCADE
- `UNIQUE KEY unique_utilisatrice_produit` (id_utilisatrice, id_produit)
- `INDEX idx_utilisatrice` (id_utilisatrice)
- `INDEX idx_produit` (id_produit)

**Relations** :
- ← `utilisatrice` (N:1)
- ← `produit` (N:1)

**Contraintes métier** :
- Un utilisateur ne peut ajouter qu'une fois le même produit en favori (UNIQUE)

---

### 11. 🔐 TABLE : admin_action

**Description** : Historique des actions administratives (audit trail)

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_action` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `id_utilisatrice` | INT | NOT NULL, FK | Admin ayant effectué l'action |
| `action` | VARCHAR(255) | NOT NULL | Type d'action (CREATE, UPDATE, DELETE) |
| `table_concernee` | VARCHAR(100) | NULL | Table modifiée |
| `id_element` | INT | NULL | ID de l'élément modifié |
| `details` | TEXT | NULL | Détails supplémentaires (JSON) |
| `date_action` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de l'action |

**Index** :
- `PRIMARY KEY` (id_action)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `INDEX idx_utilisatrice` (id_utilisatrice)
- `INDEX idx_table` (table_concernee)
- `INDEX idx_date` (date_action)

**Relations** :
- ← `utilisatrice` (N:1)

**Exemples d'actions** :
- `CREATE_PRODUCT` - Création d'un produit
- `UPDATE_PRODUCT` - Modification d'un produit
- `DELETE_PRODUCT` - Suppression d'un produit
- `UPDATE_ORDER_STATUS` - Changement statut commande
- `APPROVE_REVIEW` - Approbation d'un avis

**Format details (JSON)** :
```json
{
  "ancien": {"prix": 49.99, "stock": 10},
  "nouveau": {"prix": 39.99, "stock": 15}
}
```

---

### 12. 📊 TABLE : rapport

**Description** : Rapports statistiques générés

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_rapport` | INT | PK, AUTO_INCREMENT | Identifiant unique |
| `type_rapport` | VARCHAR(100) | NOT NULL | Type de rapport |
| `periode_debut` | DATE | NULL | Date de début de la période |
| `periode_fin` | DATE | NULL | Date de fin de la période |
| `donnees` | JSON | NULL | Données du rapport (format JSON) |
| `genere_par` | INT | NULL, FK | Admin ayant généré le rapport |
| `date_generation` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date de génération |

**Index** :
- `PRIMARY KEY` (id_rapport)
- `FOREIGN KEY` (genere_par) → utilisatrice(id_utilisatrice) ON DELETE SET NULL
- `INDEX idx_type` (type_rapport)
- `INDEX idx_date` (date_generation)

**Relations** :
- ← `utilisatrice` (N:1 via genere_par)
- → `utilisatrice_rapport` (1:N)

**Types de rapports** :
- `VENTES_MENSUELLES` - Rapport des ventes du mois
- `PRODUITS_POPULAIRES` - Produits les plus vendus
- `CLIENTS_ACTIFS` - Statistiques clients actifs
- `REVENUS_PAR_CATEGORIE` - CA par catégorie

**Format donnees (JSON)** :
```json
{
  "total_ventes": 15420.50,
  "nb_commandes": 87,
  "panier_moyen": 177.25,
  "top_produits": [...]
}
```

---

### 13. 🔗 TABLE : utilisatrice_rapport

**Description** : Table de liaison pour accès aux rapports (relation N:M)

| Colonne | Type | Contraintes | Description |
|---------|------|-------------|-------------|
| `id_utilisatrice` | INT | NOT NULL, FK, PK | Utilisateur ayant accès |
| `id_rapport` | INT | NOT NULL, FK, PK | Rapport accessible |
| `date_acces` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Date du premier accès |

**Index** :
- `PRIMARY KEY` (id_utilisatrice, id_rapport)
- `FOREIGN KEY` (id_utilisatrice) → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- `FOREIGN KEY` (id_rapport) → rapport(id_rapport) ON DELETE CASCADE

**Relations** :
- ← `utilisatrice` (N:M)
- ← `rapport` (N:M)

**Utilité** :
- Permet à plusieurs admins d'accéder aux mêmes rapports
- Traçabilité des consultations de rapports

---

## 🔗 Récapitulatif des Relations

| Table Source | Colonne FK | Table Cible | Colonne | Cardinalité | ON DELETE |
|--------------|-----------|-------------|---------|-------------|-----------|
| `produit` | id_categorie | `categorie` | id_categorie | N:1 | CASCADE |
| `panier` | id_utilisatrice | `utilisatrice` | id_utilisatrice | 1:1 | CASCADE |
| `ligne_panier` | id_panier | `panier` | id_panier | N:1 | CASCADE |
| `ligne_panier` | id_produit | `produit` | id_produit | N:1 | CASCADE |
| `commande` | id_utilisatrice | `utilisatrice` | id_utilisatrice | N:1 | CASCADE |
| `commande` | id_paiement | `paiement` | id_paiement | N:1 | SET NULL |
| `ligne_commande` | id_commande | `commande` | id_commande | N:1 | CASCADE |
| `ligne_commande` | id_produit | `produit` | id_produit | N:1 | CASCADE |
| `avis` | id_utilisatrice | `utilisatrice` | id_utilisatrice | N:1 | CASCADE |
| `avis` | id_produit | `produit` | id_produit | N:1 | CASCADE |
| `favori` | id_utilisatrice | `utilisatrice` | id_utilisatrice | N:1 | CASCADE |
| `favori` | id_produit | `produit` | id_produit | N:1 | CASCADE |
| `admin_action` | id_utilisatrice | `utilisatrice` | id_utilisatrice | N:1 | CASCADE |
| `rapport` | genere_par | `utilisatrice` | id_utilisatrice | N:1 | SET NULL |
| `utilisatrice_rapport` | id_utilisatrice | `utilisatrice` | id_utilisatrice | N:M | CASCADE |
| `utilisatrice_rapport` | id_rapport | `rapport` | id_rapport | N:M | CASCADE |

**Total** : 16 relations (clés étrangères)

---

## 📊 Statistiques du Schéma

| Métrique | Valeur |
|----------|--------|
| **Nombre de tables** | 13 |
| **Nombre total de colonnes** | 93 |
| **Nombre de clés primaires** | 13 |
| **Nombre de clés étrangères** | 16 |
| **Nombre de contraintes UNIQUE** | 8 |
| **Nombre d'index** | 28 |
| **Nombre de colonnes ENUM** | 3 |
| **Nombre de colonnes JSON** | 1 |
| **Nombre de colonnes TIMESTAMP** | 17 |
| **Nombre de colonnes DECIMAL** | 9 |

---

## ✅ Conformité aux Normes

### Normalisation
- ✅ **1NF** - Tous les attributs sont atomiques
- ✅ **2NF** - Pas de dépendance partielle
- ✅ **3NF** - Pas de dépendance transitive

### Conventions de Nommage
- ✅ Tables au singulier (sauf tables de liaison)
- ✅ Préfixe `id_` pour les identifiants
- ✅ Suffixe `_date` pour les dates
- ✅ Snake_case pour les noms
- ✅ Noms en français (métier français)

### Bonnes Pratiques
- ✅ Clés primaires auto-incrémentées
- ✅ Timestamps de création et modification
- ✅ Index sur clés étrangères
- ✅ Contraintes d'intégrité référentielle
- ✅ Valeurs par défaut appropriées
- ✅ Types de données optimisés

---

**Version** : 1.0
**Date** : Décembre 2024
**Auteur** : Équipe Feminine Aura
