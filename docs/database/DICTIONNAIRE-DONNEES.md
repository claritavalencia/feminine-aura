# 📖 DICTIONNAIRE DE DONNÉES - Feminine Aura

## 🎯 Description

Dictionnaire exhaustif de toutes les colonnes de la base de données `feminine_aura` avec leurs caractéristiques, contraintes et utilisation.

---

## 📊 TABLE : utilisatrice (6 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_utilisatrice | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de l'utilisateur | 1, 2, 3... |
| 2 | email | VARCHAR | 255 | NON | - | UNIQUE | Adresse email (login) | client@example.com, admin@feminineaura.com |
| 3 | mot_de_passe | VARCHAR | 255 | NON | - | - | Hash bcrypt du mot de passe | $2y$10$abcd... |
| 4 | role | ENUM | - | NON | 'client' | ('client','admin') | Rôle de l'utilisateur | client, admin |
| 5 | date_creation | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date d'inscription | 2024-12-14 10:30:00 |
| 6 | date_modification | TIMESTAMP | - | NON | CURRENT_TIMESTAMP ON UPDATE | - | Dernière modification du profil | 2024-12-14 15:45:00 |

**Index** :
- PK : id_utilisatrice
- UNIQUE : email
- INDEX : idx_email (email)

**Règles métier** :
- Email doit être valide et unique
- Mot de passe hashé avec PASSWORD_BCRYPT (coût 10)
- Rôle 'admin' donne accès au dashboard
- Ne JAMAIS stocker le mot de passe en clair

---

## 📁 TABLE : categorie (5 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_categorie | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de la catégorie | 1, 2, 3, 4 |
| 2 | nom | VARCHAR | 100 | NON | - | - | Nom affiché de la catégorie | Soutiens-gorge, Culottes |
| 3 | description | TEXT | 65535 | OUI | NULL | - | Description détaillée | Articles de lingerie pour... |
| 4 | slug | VARCHAR | 100 | NON | - | UNIQUE | Identifiant URL-friendly | soutiens-gorge, culottes |
| 5 | date_creation | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de création | 2024-12-14 08:00:00 |

**Index** :
- PK : id_categorie
- UNIQUE : slug
- INDEX : idx_slug (slug)

**Règles métier** :
- Slug généré depuis le nom (minuscules, sans accents, tirets)
- Slug utilisé dans les URLs SEO-friendly
- Description facultative mais recommandée

**Exemples de catégories** :
```
id: 1, nom: "Soutiens-gorge", slug: "soutiens-gorge"
id: 2, nom: "Culottes", slug: "culottes"
id: 3, nom: "Ensembles", slug: "ensembles"
id: 4, nom: "Nuisettes", slug: "nuisettes"
```

---

## 📦 TABLE : produit (13 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_produit | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique du produit | 1, 2, 3... |
| 2 | id_categorie | INT | 11 | NON | - | FK → categorie | Catégorie du produit | 1 (Soutiens-gorge) |
| 3 | nom | VARCHAR | 255 | NON | - | - | Nom commercial du produit | Femina satin, Sweet tissu |
| 4 | description | TEXT | 65535 | OUI | NULL | - | Description marketing | Pensé pour la femme moderne... |
| 5 | prix | DECIMAL | 10,2 | NON | - | > 0 | Prix de vente en euros | 74.99, 39.99 |
| 6 | stock | INT | 11 | NON | 0 | >= 0 | Quantité disponible en stock | 50, 25, 0 |
| 7 | couleur | VARCHAR | 50 | OUI | NULL | - | Couleur principale | Rouge, Noir, Rose, Bordeaux |
| 8 | taille | VARCHAR | 20 | OUI | NULL | - | Taille standard | XS, S, M, L, XL |
| 9 | tissu | VARCHAR | 100 | OUI | NULL | - | Composition textile | Satin, Dentelle, Coton, Soie |
| 10 | image_url | VARCHAR | 500 | OUI | NULL | - | URL de l'image principale | /red-lingerie-bra.jpg |
| 11 | actif | BOOLEAN | 1 | NON | TRUE | - | Produit visible/actif | true, false |
| 12 | date_creation | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date d'ajout au catalogue | 2024-12-14 09:00:00 |
| 13 | date_modification | TIMESTAMP | - | NON | CURRENT_TIMESTAMP ON UPDATE | - | Dernière modification | 2024-12-14 16:00:00 |

**Index** :
- PK : id_produit
- FK : id_categorie → categorie(id_categorie) ON DELETE CASCADE
- INDEX : idx_categorie (id_categorie)
- INDEX : idx_nom (nom)
- INDEX : idx_prix (prix)

**Règles métier** :
- Prix > 0 (obligatoire)
- Stock >= 0 (ne peut pas être négatif)
- `actif = FALSE` pour "soft delete" (ne pas afficher mais conserver historique)
- Couleurs standardisées : Noir, Blanc, Rouge, Rose, Bordeaux, Bleu, Vert
- Tailles standardisées : XS, S, M, L, XL
- Tissus communs : Satin, Dentelle, Coton, Soie, Modal

**Valeurs par défaut recommandées** :
- stock : 0
- actif : true
- couleur : NULL (à spécifier)
- taille : NULL (à spécifier)

---

## 🛒 TABLE : panier (4 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_panier | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique du panier | 1, 2, 3... |
| 2 | id_utilisatrice | INT | 11 | NON | - | UNIQUE, FK → utilisatrice | Propriétaire du panier | 5 |
| 3 | date_creation | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de création du panier | 2024-12-14 10:00:00 |
| 4 | date_modification | TIMESTAMP | - | NON | CURRENT_TIMESTAMP ON UPDATE | - | Dernière modification | 2024-12-14 14:30:00 |

**Index** :
- PK : id_panier
- UNIQUE : id_utilisatrice
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- INDEX : idx_utilisatrice (id_utilisatrice)

**Règles métier** :
- **Relation 1:1** avec utilisatrice (contrainte UNIQUE)
- Un utilisateur = un seul panier persistant
- Créé automatiquement à l'inscription
- Supprimé si l'utilisateur est supprimé (CASCADE)

---

## 🛍️ TABLE : ligne_panier (6 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_ligne_panier | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de la ligne | 1, 2, 3... |
| 2 | id_panier | INT | 11 | NON | - | FK → panier | Panier parent | 5 |
| 3 | id_produit | INT | 11 | NON | - | FK → produit | Produit dans le panier | 12 |
| 4 | quantite | INT | 11 | NON | 1 | >= 1 | Quantité du produit | 1, 2, 3 |
| 5 | prix_unitaire | DECIMAL | 10,2 | NON | - | > 0 | Prix à l'ajout au panier | 74.99 |
| 6 | date_ajout | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date d'ajout au panier | 2024-12-14 11:00:00 |

**Index** :
- PK : id_ligne_panier
- FK : id_panier → panier(id_panier) ON DELETE CASCADE
- FK : id_produit → produit(id_produit) ON DELETE CASCADE
- UNIQUE : (id_panier, id_produit)
- INDEX : idx_panier (id_panier)
- INDEX : idx_produit (id_produit)

**Règles métier** :
- Un produit ne peut apparaître qu'UNE FOIS par panier (UNIQUE composite)
- Si l'utilisateur rajoute le même produit → UPDATE quantite (pas INSERT)
- `prix_unitaire` capture le prix au moment de l'ajout (peut différer du prix actuel)
- Quantité minimum : 1
- Suppression cascade si panier ou produit supprimé

---

## 💳 TABLE : paiement (6 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_paiement | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique du paiement | 1, 2, 3... |
| 2 | mode_paiement | ENUM | - | NON | - | ('carte','paypal','virement') | Mode de paiement utilisé | carte, paypal |
| 3 | montant_paye | DECIMAL | 10,2 | NON | - | > 0 | Montant de la transaction | 149.98 |
| 4 | statut_paiement | ENUM | - | NON | 'en_attente' | ('en_attente','complete','echoue','rembourse') | Statut du paiement | complete |
| 5 | transaction_id | VARCHAR | 255 | OUI | NULL | UNIQUE | ID de transaction externe | ch_1ABC2DEF3GHI4JKL |
| 6 | date_paiement | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de la transaction | 2024-12-14 12:00:00 |

**Index** :
- PK : id_paiement
- UNIQUE : transaction_id
- INDEX : idx_statut (statut_paiement)
- INDEX : idx_transaction (transaction_id)

**Valeurs ENUM - mode_paiement** :
- `carte` : Carte bancaire (CB, Visa, Mastercard)
- `paypal` : Paiement PayPal
- `virement` : Virement bancaire

**Valeurs ENUM - statut_paiement** :
- `en_attente` : Paiement initié, en attente de confirmation
- `complete` : Paiement validé et encaissé
- `echoue` : Paiement refusé ou échoué
- `rembourse` : Paiement remboursé au client

**Règles métier** :
- `transaction_id` rempli par le processeur de paiement (Stripe, PayPal)
- Montant doit correspondre au total de la commande
- Un paiement peut être lié à plusieurs commandes (exceptionnellement)

---

## 📦 TABLE : commande (13 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_commande | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de la commande | 1, 2, 3... |
| 2 | id_utilisatrice | INT | 11 | NON | - | FK → utilisatrice | Client ayant passé commande | 5 |
| 3 | id_paiement | INT | 11 | OUI | NULL | FK → paiement | Paiement associé | 7 |
| 4 | numero_commande | VARCHAR | 50 | NON | - | UNIQUE | Numéro de commande unique | CMD-20241214-00001 |
| 5 | total_commande | DECIMAL | 10,2 | NON | - | > 0 | Montant total TTC | 224.97 |
| 6 | statut_commande | ENUM | - | NON | 'en_attente' | 6 valeurs | Statut de la commande | confirmee, expediee |
| 7 | adresse_livraison | TEXT | 65535 | NON | - | - | Adresse complète | 15 Rue de la Paix |
| 8 | ville | VARCHAR | 100 | NON | - | - | Ville de livraison | Paris |
| 9 | code_postal | VARCHAR | 20 | NON | - | - | Code postal | 75001 |
| 10 | pays | VARCHAR | 100 | NON | 'France' | - | Pays de livraison | France, Belgique |
| 11 | telephone | VARCHAR | 20 | OUI | NULL | - | Téléphone de contact | +33 6 12 34 56 78 |
| 12 | date_commande | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de passage commande | 2024-12-14 13:00:00 |
| 13 | date_modification | TIMESTAMP | - | NON | CURRENT_TIMESTAMP ON UPDATE | - | Dernière modification | 2024-12-14 15:00:00 |

**Index** :
- PK : id_commande
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- FK : id_paiement → paiement(id_paiement) ON DELETE SET NULL
- UNIQUE : numero_commande
- INDEX : idx_utilisatrice (id_utilisatrice)
- INDEX : idx_numero (numero_commande)
- INDEX : idx_statut (statut_commande)
- INDEX : idx_date (date_commande)

**Valeurs ENUM - statut_commande** :
- `en_attente` : Commande créée, paiement en attente
- `confirmee` : Paiement confirmé, commande validée
- `en_preparation` : Commande en cours de préparation
- `expediee` : Commande expédiée, en transit
- `livree` : Commande livrée au client
- `annulee` : Commande annulée (par client ou admin)

**Format numero_commande** :
- Pattern : `CMD-YYYYMMDD-XXXXX`
- Exemple : `CMD-20241214-00001`
- YYYYMMDD : Date de la commande
- XXXXX : Numéro séquentiel sur 5 chiffres

**Règles métier** :
- `id_paiement` peut être NULL initialement (paiement en attente)
- Adresse de livraison complète obligatoire
- Total commande = SUM(ligne_commande.sous_total)
- Suppression paiement → SET NULL (garder historique commande)

---

## 📄 TABLE : ligne_commande (6 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_ligne_commande | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de la ligne | 1, 2, 3... |
| 2 | id_commande | INT | 11 | NON | - | FK → commande | Commande parent | 12 |
| 3 | id_produit | INT | 11 | NON | - | FK → produit | Produit commandé | 8 |
| 4 | quantite | INT | 11 | NON | 1 | >= 1 | Quantité commandée | 2 |
| 5 | prix_unitaire | DECIMAL | 10,2 | NON | - | > 0 | Prix unitaire à la commande | 74.99 |
| 6 | sous_total | DECIMAL | 10,2 | NON | - | > 0 | Sous-total (quantité × prix) | 149.98 |

**Index** :
- PK : id_ligne_commande
- FK : id_commande → commande(id_commande) ON DELETE CASCADE
- FK : id_produit → produit(id_produit) ON DELETE CASCADE
- INDEX : idx_commande (id_commande)
- INDEX : idx_produit (id_produit)

**Règles métier** :
- `prix_unitaire` = snapshot du prix au moment de la commande
- `sous_total` = `quantite` × `prix_unitaire` (calculé et stocké)
- Même produit peut apparaître plusieurs fois (contrairement au panier)
- Suppression cascade si commande supprimée

**Calcul** :
```
sous_total = quantite × prix_unitaire
total_commande = SUM(sous_total) pour toutes les lignes
```

---

## ⭐ TABLE : avis (7 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_avis | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de l'avis | 1, 2, 3... |
| 2 | id_utilisatrice | INT | 11 | NON | - | FK → utilisatrice | Auteur de l'avis | 5 |
| 3 | id_produit | INT | 11 | NON | - | FK → produit | Produit évalué | 12 |
| 4 | note | INT | 11 | NON | - | CHECK (1-5) | Note sur 5 étoiles | 4, 5 |
| 5 | commentaire | TEXT | 65535 | OUI | NULL | - | Commentaire textuel | Très belle qualité... |
| 6 | date_avis | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de publication | 2024-12-14 14:00:00 |
| 7 | approuve | BOOLEAN | 1 | NON | FALSE | - | Avis modéré et approuvé | false, true |

**Index** :
- PK : id_avis
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- FK : id_produit → produit(id_produit) ON DELETE CASCADE
- UNIQUE : (id_utilisatrice, id_produit)
- INDEX : idx_produit (id_produit)
- INDEX : idx_note (note)
- INDEX : idx_approuve (approuve)

**Règles métier** :
- Un utilisateur = UN SEUL avis par produit (UNIQUE composite)
- Note obligatoire entre 1 et 5 étoiles (CHECK)
- Commentaire facultatif (peut laisser juste une note)
- `approuve = FALSE` par défaut → modération admin requise
- Seuls les avis approuvés sont affichés publiquement

**Calcul moyenne** :
```sql
SELECT AVG(note) as moyenne, COUNT(*) as total
FROM avis
WHERE id_produit = ? AND approuve = TRUE
```

---

## ❤️ TABLE : favori (4 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_favori | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique du favori | 1, 2, 3... |
| 2 | id_utilisatrice | INT | 11 | NON | - | FK → utilisatrice | Utilisateur propriétaire | 5 |
| 3 | id_produit | INT | 11 | NON | - | FK → produit | Produit favori | 8 |
| 4 | date_ajout | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date d'ajout aux favoris | 2024-12-14 10:30:00 |

**Index** :
- PK : id_favori
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- FK : id_produit → produit(id_produit) ON DELETE CASCADE
- UNIQUE : (id_utilisatrice, id_produit)
- INDEX : idx_utilisatrice (id_utilisatrice)
- INDEX : idx_produit (id_produit)

**Règles métier** :
- Un utilisateur ne peut ajouter qu'UNE FOIS le même produit (UNIQUE)
- Toggle favori : Si existe → DELETE, sinon → INSERT
- Liste des favoris : SELECT avec JOIN produit
- Compteur favoris par produit : COUNT(*)

**API Toggle** :
```sql
-- Vérifier si existe
SELECT COUNT(*) FROM favori WHERE id_utilisatrice = ? AND id_produit = ?

-- Si existe : DELETE
DELETE FROM favori WHERE id_utilisatrice = ? AND id_produit = ?

-- Si n'existe pas : INSERT
INSERT INTO favori (id_utilisatrice, id_produit) VALUES (?, ?)
```

---

## 🔐 TABLE : admin_action (7 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_action | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique de l'action | 1, 2, 3... |
| 2 | id_utilisatrice | INT | 11 | NON | - | FK → utilisatrice | Admin ayant agi | 1 |
| 3 | action | VARCHAR | 255 | NON | - | - | Type d'action | CREATE_PRODUCT, UPDATE_ORDER |
| 4 | table_concernee | VARCHAR | 100 | OUI | NULL | - | Table modifiée | produit, commande |
| 5 | id_element | INT | 11 | OUI | NULL | - | ID de l'élément modifié | 12 |
| 6 | details | TEXT | 65535 | OUI | NULL | - | Détails JSON | {"ancien": {...}, "nouveau": {...}} |
| 7 | date_action | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de l'action | 2024-12-14 16:00:00 |

**Index** :
- PK : id_action
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- INDEX : idx_utilisatrice (id_utilisatrice)
- INDEX : idx_table (table_concernee)
- INDEX : idx_date (date_action)

**Types d'actions** :
- `CREATE_PRODUCT` - Création produit
- `UPDATE_PRODUCT` - Modification produit
- `DELETE_PRODUCT` - Suppression produit
- `UPDATE_ORDER_STATUS` - Changement statut commande
- `APPROVE_REVIEW` - Approbation avis
- `CREATE_CATEGORY` - Création catégorie

**Format details (JSON)** :
```json
{
  "ancien": {"prix": 49.99, "stock": 10},
  "nouveau": {"prix": 39.99, "stock": 15},
  "raison": "Promotion de Noël"
}
```

**Utilité** :
- Audit trail complet
- Traçabilité des modifications
- Enquête en cas de problème
- Statistiques d'utilisation admin

---

## 📊 TABLE : rapport (7 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_rapport | INT | 11 | NON | AUTO_INCREMENT | PK | Identifiant unique du rapport | 1, 2, 3... |
| 2 | type_rapport | VARCHAR | 100 | NON | - | - | Type de rapport | VENTES_MENSUELLES |
| 3 | periode_debut | DATE | - | OUI | NULL | - | Date début période | 2024-12-01 |
| 4 | periode_fin | DATE | - | OUI | NULL | - | Date fin période | 2024-12-31 |
| 5 | donnees | JSON | - | OUI | NULL | - | Données du rapport (JSON) | {"total_ventes": 15420.50, ...} |
| 6 | genere_par | INT | 11 | OUI | NULL | FK → utilisatrice | Admin générateur | 1 |
| 7 | date_generation | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date de génération | 2024-12-14 17:00:00 |

**Index** :
- PK : id_rapport
- FK : genere_par → utilisatrice(id_utilisatrice) ON DELETE SET NULL
- INDEX : idx_type (type_rapport)
- INDEX : idx_date (date_generation)

**Types de rapports** :
- `VENTES_MENSUELLES` - CA et commandes du mois
- `PRODUITS_POPULAIRES` - Top produits vendus
- `CLIENTS_ACTIFS` - Statistiques clients
- `REVENUS_PAR_CATEGORIE` - CA par catégorie
- `STOCK_FAIBLE` - Alerte stocks bas

**Format donnees (JSON)** :
```json
{
  "total_ventes": 15420.50,
  "nb_commandes": 87,
  "panier_moyen": 177.25,
  "top_produits": [
    {"id": 5, "nom": "Love Kit", "ventes": 45},
    {"id": 12, "nom": "Culotte Rouge", "ventes": 38}
  ],
  "graphique": {
    "labels": ["Sem 1", "Sem 2", "Sem 3", "Sem 4"],
    "values": [3200, 4100, 3850, 4270]
  }
}
```

**Règles métier** :
- `genere_par` SET NULL si admin supprimé (garder rapport)
- JSON permet flexibilité des données
- Rapports peuvent être partagés via `utilisatrice_rapport`

---

## 🔗 TABLE : utilisatrice_rapport (3 colonnes)

| # | Colonne | Type | Taille | NULL | Défaut | Contraintes | Description | Exemples |
|---|---------|------|--------|------|--------|-------------|-------------|----------|
| 1 | id_utilisatrice | INT | 11 | NON | - | PK, FK → utilisatrice | Admin ayant accès | 1 |
| 2 | id_rapport | INT | 11 | NON | - | PK, FK → rapport | Rapport accessible | 5 |
| 3 | date_acces | TIMESTAMP | - | NON | CURRENT_TIMESTAMP | - | Date du premier accès | 2024-12-14 17:30:00 |

**Index** :
- PRIMARY KEY (id_utilisatrice, id_rapport)
- FK : id_utilisatrice → utilisatrice(id_utilisatrice) ON DELETE CASCADE
- FK : id_rapport → rapport(id_rapport) ON DELETE CASCADE

**Règles métier** :
- Relation N:M (plusieurs admins, plusieurs rapports)
- Traçabilité des consultations
- Droits d'accès aux rapports
- `date_acces` = première consultation

**Utilité** :
- Partage de rapports entre admins
- Historique de consultation
- Statistiques d'utilisation des rapports

---

## 📊 Résumé Global

### Colonnes par Type

| Type | Nombre | Pourcentage |
|------|--------|-------------|
| INT | 54 | 58.1% |
| VARCHAR | 16 | 17.2% |
| TIMESTAMP | 17 | 18.3% |
| DECIMAL | 9 | 9.7% |
| TEXT | 7 | 7.5% |
| ENUM | 3 | 3.2% |
| BOOLEAN | 2 | 2.2% |
| DATE | 2 | 2.2% |
| JSON | 1 | 1.1% |
| **TOTAL** | **93** | **100%** |

### Contraintes

| Contrainte | Nombre |
|------------|--------|
| PRIMARY KEY | 13 |
| FOREIGN KEY | 16 |
| UNIQUE | 8 |
| NOT NULL | 71 |
| DEFAULT | 45 |
| CHECK | 1 |
| AUTO_INCREMENT | 13 |
| ON DELETE CASCADE | 13 |
| ON DELETE SET NULL | 3 |

### Index

| Type d'index | Nombre |
|--------------|--------|
| PRIMARY KEY | 13 |
| UNIQUE | 8 |
| INDEX simple | 15 |
| INDEX composite | 2 |
| **TOTAL** | **38** |

---

**Version** : 1.0
**Date** : Décembre 2024
**Dernière mise à jour** : 2024-12-14
