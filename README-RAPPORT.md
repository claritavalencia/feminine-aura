# 💖 Feminine Aura - Rapport de Projet Académique

**Projet E-Commerce de Lingerie - Formation Développement Web Full Stack**

---

## 1. Introduction

### 📋 Présentation du Projet

**Feminine Aura** est une plateforme e-commerce complète dédiée à la vente de lingerie féminine, développée dans le cadre de ma formation en développement web full stack. Ce projet représente la concrétisation de plusieurs mois d'apprentissage et illustre ma capacité à concevoir et développer une application web moderne de A à Z.

L'application comprend :
- **Un frontend moderne** développé avec Next.js 16 et React 19
- **Une API REST robuste** en PHP 8.1 avec architecture MVC
- **Une base de données relationnelle** MySQL avec 13 tables normalisées
- **Un dashboard administrateur** complet pour la gestion du catalogue et des commandes
- **Un système d'authentification** sécurisé avec gestion des rôles
- **Des fonctionnalités e-commerce essentielles** (panier, favoris, commandes, avis)

### 🎯 Objectifs Pédagogiques

Ce projet m'a permis de démontrer ma maîtrise des compétences suivantes :

**Compétences Techniques** :
- Développement frontend moderne (React, Next.js, TypeScript)
- Création d'API REST sécurisées (PHP, architecture MVC)
- Modélisation et gestion de bases de données relationnelles (MySQL)
- Gestion d'état applicatif (React Context API)
- Authentification et autorisation (tokens, rôles utilisateurs)
- Intégration frontend-backend (CORS, fetch API)
- Responsive design (Tailwind CSS, mobile-first)

**Compétences Transversales** :
- Architecture logicielle (séparation des responsabilités, MVC, REST)
- Résolution de problèmes techniques complexes (CORS, hydration, performance)
- Documentation technique (README, commentaires, diagrammes)
- Gestion de projet (planification, priorisation, itérations)
- Veille technologique (frameworks récents, bonnes pratiques)

### 👩‍💻 Mon Parcours

Je suis designer UX/UI de formation, avec plusieurs années d'expérience dans la création d'interfaces utilisateur et de systèmes de design. Passionnée par la création numérique, j'ai décidé de revenir au développement pour avoir une vision complète du cycle de création d'un produit digital, de la conception à la mise en production.

Ce projet Feminine Aura représente pour moi bien plus qu'un exercice académique : c'est la preuve concrète que je peux non seulement designer une interface, mais aussi la développer entièrement, de la base de données au rendu final.

**Ma démarche** a été de :
1. Concevoir d'abord l'identité visuelle et l'expérience utilisateur (design system, maquettes)
2. Modéliser la structure de données nécessaire (MCD, MLD)
3. Développer l'API backend pour exposer les données
4. Créer l'interface frontend en respectant mes maquettes
5. Intégrer les fonctionnalités métier (panier, favoris, commandes)
6. Implémenter le dashboard administrateur

Cette approche m'a permis de comprendre intimement les enjeux de chaque couche de l'application et d'apprécier la complexité du développement full stack.

---

## 2. Choix des Technologies

Le choix de mon stack technique a été guidé par trois critères principaux : la modernité des technologies, leur pertinence pour le marché du travail, et ma volonté d'apprendre des outils à la pointe de l'industrie.

### 📊 Stack Technologique

| Couche | Technologie | Version | Justification |
|--------|-------------|---------|---------------|
| **Frontend Framework** | Next.js | 16.0 | Framework React moderne avec App Router, SSR/SSG, optimisations automatiques, routing intégré |
| **UI Library** | React | 19.0 | Bibliothèque UI leader du marché, écosystème riche, composants réutilisables |
| **Langage Frontend** | TypeScript | 5.x | Typage statique pour la fiabilité, autocomplétion IDE, meilleure maintenabilité |
| **Styling** | Tailwind CSS | 4.0 | Approche utility-first rapide, design system intégré, responsive natif |
| **Components UI** | Shadcn/UI | Latest | Composants React accessibles, personnalisables, design moderne |
| **Icons** | Lucide React | Latest | Icônes SVG légères, cohérentes, large bibliothèque (600+) |
| **State Management** | React Context API | Native | Gestion d'état globale native, pas de dépendance externe, suffisant pour ce projet |
| **Backend Language** | PHP | 8.1.25 | Langage serveur robuste, largement utilisé en e-commerce, parfait pour APIs REST |
| **Web Server** | Apache | 2.4.58 | Serveur web fiable, compatible XAMPP, configuration .htaccess flexible |
| **Database** | MySQL | 8.0 | SGBD relationnel performant, transactions ACID, support des contraintes FK |
| **Database Abstraction** | PDO | Native | Abstraction PHP sécurisée, protection SQL injection via prepared statements |
| **API Architecture** | REST | - | Architecture standard, stateless, cacheable, facile à tester |
| **Data Format** | JSON | - | Format d'échange universel, léger, support natif JavaScript |
| **Security** | Bcrypt + CORS | - | Hachage mots de passe sécurisé (bcrypt), CORS pour requêtes cross-origin |
| **Development Environment** | XAMPP | 8.1.x | Stack local complet (Apache+MySQL+PHP), facile à installer, idéal pour développement |
| **Build Tool** | Turbopack | Next.js 16 | Bundler ultra-rapide intégré à Next.js, HMR instantané |
| **Version Control** | Git | Latest | Gestion de versions, historique, collaboration |

### 🔄 Architecture Logicielle

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT (Navigateur)                      │
│                                                             │
│  Next.js App (localhost:3000)                              │
│  ├── React Components (UI)                                 │
│  ├── Context API (State)                                   │
│  ├── TypeScript (Logic)                                    │
│  └── Tailwind CSS (Styling)                                │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ HTTP/JSON (CORS)
                       │ fetch() API
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                  API REST (Backend)                         │
│                                                             │
│  PHP API (localhost/api)                                   │
│  ├── Controllers (MVC)                                     │
│  ├── Models (Business Logic)                               │
│  ├── CORS Config                                           │
│  └── Response Utils                                        │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ PDO (Prepared Statements)
                       │
┌──────────────────────▼──────────────────────────────────────┐
│               BASE DE DONNÉES MySQL                         │
│                                                             │
│  13 Tables normalisées (3NF)                               │
│  ├── Utilisateurs & Auth                                   │
│  ├── Produits & Catégories                                 │
│  ├── Panier & Favoris                                      │
│  ├── Commandes & Paiements                                 │
│  └── Avis & Analytics                                      │
└─────────────────────────────────────────────────────────────┘
```

### 💡 Justifications des Choix

**Pourquoi Next.js et non React seul ?**
Next.js offre des fonctionnalités essentielles pour un projet e-commerce : le routing automatique, le Server-Side Rendering pour le SEO, l'optimisation automatique des images, et un système de build moderne. C'est devenu le standard de l'industrie pour les applications React en production.

**Pourquoi TypeScript ?**
Venant du design, j'ai rapidement compris la valeur du typage statique : moins d'erreurs à l'exécution, autocomplétion intelligente dans l'IDE, code auto-documenté. C'est un investissement initial qui paie énormément sur la durée.

**Pourquoi PHP et non Node.js pour le backend ?**
PHP reste le langage le plus utilisé pour les CMS e-commerce (WooCommerce, Magento, PrestaShop). Apprendre à créer une API REST en PHP avec architecture MVC m'a donné des bases solides applicables à de nombreux projets professionnels. De plus, XAMPP facilite grandement le développement local.

**Pourquoi MySQL et non MongoDB ?**
Pour un e-commerce, les données sont hautement relationnelles (utilisateurs → commandes → produits → catégories). MySQL avec ses contraintes d'intégrité référentielle garantit la cohérence des données, ce qui est crucial pour éviter les incohérences (commandes sans produits, paniers orphelins, etc.).

**Pourquoi Tailwind CSS ?**
Tailwind permet un développement rapide avec des classes utilitaires tout en gardant un design cohérent. En tant que designer, j'apprécie sa flexibilité : je peux facilement implémenter mon design system sans être limité par des composants pré-stylés.

---

## 3. Identité Visuelle et UI/UX

### 🎨 Design System

En tant que designer UX/UI, j'ai d'abord créé une identité visuelle complète avant de commencer le développement.

**Palette de couleurs** :
```
Primary (Rose)    : #F34792  /* Couleur de marque, féminine et moderne */
Secondary (Noir)  : #1A0A1A  /* Élégance, sophistication */
Accent (Rose clair): #FF9FC9  /* Touches de douceur */
```

Cette palette a été choisie pour évoquer la féminité tout en restant élégante et moderne. Le rose #F34792 est suffisamment saturé pour attirer l'attention sans être agressif, et le noir profond #1A0A1A apporte le contraste et la sophistication nécessaires à une marque de lingerie haut de gamme.

**Typographie** :
- Police principale : Geist (sans-serif moderne)
- Hiérarchie claire avec 8 tailles (xs à 4xl)
- Poids variables (light à bold) pour la hiérarchie visuelle

**Principes UX appliqués** :
- Mobile-first (design pensé d'abord pour mobile)
- Accessibilité (contrastes WCAG AA, navigation clavier)
- Performance (lazy loading images, code splitting)
- Cohérence (design system strict avec Tailwind)

### 📱 Maquettes et Wireframes

> **Note** : Cette section contiendra mes maquettes Figma/Adobe XD montrant :
> - La page d'accueil
> - La page produit détaillée
> - Le panier
> - Le dashboard admin
> - Les différents breakpoints responsive
>
> *Maquettes à intégrer lors de la finalisation du rapport*

### ✨ Expérience Utilisateur

J'ai structuré l'expérience en plusieurs parcours utilisateur :

**Parcours Client** :
1. Découverte (page d'accueil → catalogue)
2. Exploration (filtres, recherche, produit détaillé)
3. Sélection (ajout panier, favoris)
4. Conversion (checkout, paiement)
5. Fidélisation (compte, historique, avis)

**Parcours Admin** :
1. Authentification sécurisée
2. Vue d'ensemble (dashboard statistiques)
3. Gestion (CRUD produits, commandes, catégories)
4. Analytics (rapports, actions admin)

Chaque écran a été pensé pour minimiser les frictions et guider naturellement l'utilisateur vers l'action souhaitée.

---

## 4. Modélisation de la Base de Données

### 📊 Modèle Conceptuel de Données (MCD)

La base de données a été conçue en suivant une démarche rigoureuse de normalisation (3NF) pour garantir l'intégrité et éviter les redondances.

**Diagramme Entité-Association** :

```mermaid
erDiagram
    UTILISATRICE ||--o{ PANIER : possede
    UTILISATRICE ||--o{ COMMANDE : passe
    UTILISATRICE ||--o{ AVIS : redige
    UTILISATRICE ||--o{ FAVORI : ajoute

    CATEGORIE ||--o{ PRODUIT : contient

    PRODUIT ||--o{ LIGNE_PANIER : "est dans"
    PRODUIT ||--o{ LIGNE_COMMANDE : "est commande"
    PRODUIT ||--o{ AVIS : "recoit"
    PRODUIT ||--o{ FAVORI : "est favori"

    PANIER ||--|{ LIGNE_PANIER : contient

    COMMANDE ||--o| PAIEMENT : "est paye par"
    COMMANDE ||--|{ LIGNE_COMMANDE : contient

    PAIEMENT ||--o{ COMMANDE : "paie"
```

**Entités principales** (13 tables) :

1. **utilisatrice** - Comptes utilisateurs (clients + admins)
2. **categorie** - Classification des produits
3. **produit** - Catalogue produits
4. **panier** - Paniers d'achat (1 par utilisateur)
5. **ligne_panier** - Articles dans les paniers
6. **commande** - Commandes passées
7. **ligne_commande** - Détails articles commandés
8. **paiement** - Transactions financières
9. **favori** - Produits favoris
10. **avis** - Notes et commentaires clients
11. **admin_action** - Audit trail des actions admin
12. **rapport** - Rapports statistiques
13. **utilisatrice_rapport** - Accès aux rapports (N:M)

**Relations clés** :
- Utilisatrice ↔ Panier : **1:1** (un utilisateur = un panier unique)
- Utilisatrice ↔ Commande : **1:N** (un utilisateur peut passer plusieurs commandes)
- Produit ↔ Catégorie : **N:1** (plusieurs produits par catégorie)
- Commande ↔ Paiement : **N:1** (une commande = un paiement)
- Utilisatrice ↔ Favori : **1:N** avec contrainte UNIQUE (un produit favori une seule fois)
- Utilisatrice ↔ Avis : **1:N** avec contrainte UNIQUE (un avis par produit)

### 📋 Modèle Logique de Données (MLD)

**Schéma simplifié des tables principales** :

```sql
-- UTILISATRICE (utilisateurs du système)
utilisatrice (
    id_utilisatrice INT PK AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,  -- Hash bcrypt
    role ENUM('client', 'admin') DEFAULT 'client',
    date_creation TIMESTAMP,
    date_modification TIMESTAMP
)

-- CATEGORIE (classification produits)
categorie (
    id_categorie INT PK AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    slug VARCHAR(100) UNIQUE NOT NULL,  -- URL-friendly
    date_creation TIMESTAMP
)

-- PRODUIT (catalogue)
produit (
    id_produit INT PK AUTO_INCREMENT,
    id_categorie INT FK → categorie(id_categorie) ON DELETE CASCADE,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    couleur VARCHAR(50),
    taille VARCHAR(20),
    tissu VARCHAR(100),
    image_url VARCHAR(500),
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP,
    date_modification TIMESTAMP
)

-- PANIER (un par utilisateur)
panier (
    id_panier INT PK AUTO_INCREMENT,
    id_utilisatrice INT UNIQUE FK → utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    date_creation TIMESTAMP,
    date_modification TIMESTAMP
)

-- LIGNE_PANIER (articles dans panier)
ligne_panier (
    id_ligne_panier INT PK AUTO_INCREMENT,
    id_panier INT FK → panier(id_panier) ON DELETE CASCADE,
    id_produit INT FK → produit(id_produit) ON DELETE CASCADE,
    quantite INT DEFAULT 1,
    prix_unitaire DECIMAL(10,2),  -- Prix au moment de l'ajout
    date_ajout TIMESTAMP,
    UNIQUE(id_panier, id_produit)  -- Un produit une seule fois par panier
)

-- COMMANDE (commandes clients)
commande (
    id_commande INT PK AUTO_INCREMENT,
    id_utilisatrice INT FK → utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    id_paiement INT FK → paiement(id_paiement) ON DELETE SET NULL,
    numero_commande VARCHAR(50) UNIQUE NOT NULL,  -- CMD-YYYYMMDD-XXXXX
    total_commande DECIMAL(10,2) NOT NULL,
    statut_commande ENUM('en_attente', 'confirmee', 'en_preparation', 'expediee', 'livree', 'annulee'),
    adresse_livraison TEXT NOT NULL,
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    pays VARCHAR(100) DEFAULT 'France',
    telephone VARCHAR(20),
    date_commande TIMESTAMP,
    date_modification TIMESTAMP
)

-- LIGNE_COMMANDE (détails commande)
ligne_commande (
    id_ligne_commande INT PK AUTO_INCREMENT,
    id_commande INT FK → commande(id_commande) ON DELETE CASCADE,
    id_produit INT FK → produit(id_produit) ON DELETE CASCADE,
    quantite INT DEFAULT 1,
    prix_unitaire DECIMAL(10,2),  -- Snapshot du prix
    sous_total DECIMAL(10,2)  -- quantite × prix_unitaire
)

-- PAIEMENT (transactions)
paiement (
    id_paiement INT PK AUTO_INCREMENT,
    mode_paiement ENUM('carte', 'paypal', 'virement'),
    montant_paye DECIMAL(10,2),
    statut_paiement ENUM('en_attente', 'complete', 'echoue', 'rembourse') DEFAULT 'en_attente',
    transaction_id VARCHAR(255) UNIQUE,  -- ID externe (Stripe, PayPal)
    date_paiement TIMESTAMP
)

-- FAVORI (wishlist)
favori (
    id_favori INT PK AUTO_INCREMENT,
    id_utilisatrice INT FK → utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    id_produit INT FK → produit(id_produit) ON DELETE CASCADE,
    date_ajout TIMESTAMP,
    UNIQUE(id_utilisatrice, id_produit)  -- Un produit favori une fois
)

-- AVIS (reviews)
avis (
    id_avis INT PK AUTO_INCREMENT,
    id_utilisatrice INT FK → utilisatrice(id_utilisatrice) ON DELETE CASCADE,
    id_produit INT FK → produit(id_produit) ON DELETE CASCADE,
    note INT CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    date_avis TIMESTAMP,
    approuve BOOLEAN DEFAULT FALSE,  -- Modération admin
    UNIQUE(id_utilisatrice, id_produit)  -- Un avis par produit
)
```

**Statistiques de la base** :
- 13 tables
- 93 colonnes au total
- 16 clés étrangères
- 28 index (performance)
- 8 contraintes UNIQUE
- Normalisation 3NF complète

### 🔒 Contraintes d'Intégrité

**Contraintes de domaine** :
- `note` des avis : entre 1 et 5 (CHECK)
- `prix` produit : > 0 (validation application)
- `stock` produit : >= 0 (validation application)
- `email` : format valide + UNIQUE
- `slug` catégorie : format URL + UNIQUE

**Contraintes référentielles** :
- **CASCADE** : Suppression en cascade pour données dépendantes
  - Ex : Si un produit est supprimé → ses lignes de panier sont supprimées
- **SET NULL** : Conservation des données historiques
  - Ex : Si un paiement est supprimé → la commande reste (id_paiement devient NULL)

**Contraintes d'unicité composites** :
- `(id_panier, id_produit)` : Un produit ne peut apparaître qu'une fois dans un panier
- `(id_utilisatrice, id_produit)` : Un utilisateur ne peut laisser qu'un avis par produit
- `(id_utilisatrice, id_produit)` : Un utilisateur ne peut ajouter qu'une fois un produit en favori

**Indexes pour performance** :
- Index sur toutes les clés étrangères
- Index sur les colonnes fréquemment filtrées (email, slug, statut_commande, note)
- Index sur les dates (pour les rapports temporels)

**Documentation complète** :
- [📊 MCD Détaillé](docs/database/MCD.md) - Diagramme complet avec explications
- [📋 MLD Complet](docs/database/MLD.md) - Structure détaillée de toutes les tables
- [📖 Dictionnaire de Données](docs/database/DICTIONNAIRE-DONNEES.md) - Chaque colonne documentée

---

## 5. Fonctionnalités et Interactions

### 🏗️ Architecture Globale

L'application suit une architecture **three-tier** classique avec séparation claire des responsabilités :

```
┌─────────────────────────────────────────────────────────────┐
│                   FRONTEND (Présentation)                   │
│                                                             │
│  • Pages Next.js (App Router)                              │
│  • Composants React réutilisables                          │
│  • Gestion d'état (Context API)                            │
│  • Validation formulaires côté client                      │
│  • Responsive design (Tailwind CSS)                        │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ HTTP/JSON
                       │ CORS autorisé
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                   API REST (Logique Métier)                 │
│                                                             │
│  • Routing (index.php)                                     │
│  • Controllers (traitement requêtes)                       │
│  • Models (logique métier + DB)                            │
│  • Validation données côté serveur                         │
│  • Authentification (tokens)                               │
│  • Autorisation (vérification rôles)                       │
│  • Réponses JSON standardisées                             │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ PDO
                       │ Prepared Statements
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                 BASE DE DONNÉES (Persistance)               │
│                                                             │
│  • Tables normalisées (3NF)                                │
│  • Contraintes d'intégrité (FK, UNIQUE, CHECK)            │
│  • Transactions ACID                                       │
│  • Index pour performance                                  │
└─────────────────────────────────────────────────────────────┘
```

### 👤 Fonctionnalités Côté Client

#### 1. **Catalogue Produits**

**Page Boutique** (`/boutique`) :
- Affichage grille responsive (1→2→3→4 colonnes selon écran)
- Filtres multiples :
  - Par catégorie (Soutiens-gorge, Culottes, Ensembles, Nuisettes)
  - Par prix (slider min/max)
  - Par couleur (Rouge, Noir, Rose, Blanc, Bordeaux)
  - Par taille (XS, S, M, L, XL)
  - Par tissu (Satin, Dentelle, Coton, Soie)
- Recherche textuelle (nom produit)
- Tri (prix croissant/décroissant, nouveautés, popularité)
- Pagination (20 produits par page)
- Badge stock ("Stock bas" si < 5)

**Code exemple (simplifié)** :
```typescript
// app/boutique/page.tsx
const response = await fetch(`${API_URL}/produits?categorie=1&prix_max=100&couleur=Rouge`);
const data = await response.json();
const produits = data.data.produits; // Array de 14 produits
```

#### 2. **Page Produit Détaillée**

**Route dynamique** (`/product/[id]`) :
- Galerie d'images (principale + thumbnails)
- Informations complètes (nom, description, prix, stock)
- Sélecteurs :
  - Taille (boutons radio)
  - Couleur (boutons colorés)
  - Quantité (input number avec +/-)
- Bouton "Ajouter au panier" avec feedback visuel
- Bouton "Favoris" (cœur) avec toggle
- Section avis clients :
  - Moyenne des notes (étoiles)
  - Nombre total d'avis
  - Liste des avis approuvés
  - Formulaire nouvel avis (si connecté)
- Produits similaires (même catégorie)

#### 3. **Panier**

**Fonctionnement hybride** (localStorage + API) :
- **Non connecté** : Stockage localStorage uniquement
- **Connecté** : Synchronisation automatique avec l'API

**Fonctionnalités** :
- Affichage articles avec image/nom/prix/quantité
- Modification quantité (temps réel)
- Suppression article
- Calcul total automatique
- Vider le panier (avec confirmation)
- Bouton "Commander" (redirige vers checkout si connecté, sinon vers login)

**Code Context API** :
```typescript
// app/context/cart-context.tsx
const addItem = async (newItem: CartItem) => {
  if (user?.token) {
    // API call
    await apiAddToCart(user.token, parseInt(newItem.id), newItem.quantity);
    // Recharger depuis API
    const response = await getCart(user.token);
    setItems(response.data.items);
  } else {
    // localStorage
    setItems(prev => [...prev, newItem]);
  }
};
```

#### 4. **Favoris**

**Même logique hybride** que le panier :
- Liste des produits favoris avec images
- Bouton "Ajouter au panier" direct
- Bouton "Retirer des favoris"
- Compteur nombre de favoris (header)

**Toggle favori** :
```typescript
// app/context/favorites-context.tsx
const toggleFavorite = async (productId: string) => {
  if (user?.token) {
    await apiToggleFavorite(user.token, parseInt(productId));
  } else {
    // Toggle localStorage
    const exists = favorites.find(f => f.id === productId);
    if (exists) {
      setFavorites(prev => prev.filter(f => f.id !== productId));
    } else {
      setFavorites(prev => [...prev, product]);
    }
  }
};
```

#### 5. **Commandes**

**Page commandes** (`/commandes`) :
- Liste historique des commandes
- Filtres par statut
- Détails commande (produits, quantités, prix, total)
- Suivi statut (badges colorés)
- Bouton "Recommander" (ajoute au panier)

**Statuts commande** :
- 🟡 En attente
- 🟢 Confirmée
- 🔵 En préparation
- 🟣 Expédiée
- ✅ Livrée
- 🔴 Annulée

#### 6. **Authentification**

**Pages** :
- `/auth/login` - Connexion
- `/auth/register` - Inscription

**Fonctionnalités** :
- Validation temps réel (email format, mot de passe longueur)
- Messages d'erreur clairs
- Stockage token dans Context + localStorage
- Redirection automatique après login
- Bouton "Se déconnecter" (header)

### 👨‍💼 Fonctionnalités Côté Admin

#### 1. **Dashboard Administrateur**

**Authentification sécurisée** :
- Page login dédiée (`/admin`)
- Vérification rôle `admin` côté API
- Redirection si non autorisé

**Vue d'ensemble** (`/admin/dashboard`) :
- **4 cartes statistiques** :
  1. Revenus du mois (€, tendance %)
  2. Nombre de commandes
  3. Produits en stock
  4. Nouveaux clients du mois
- **3 onglets** :
  - Produits (CRUD)
  - Commandes (gestion)
  - Catégories (gestion)

#### 2. **Gestion Produits (CRUD Complet)**

**Liste produits** :
- Tableau avec colonnes : Image, Nom, Prix, Stock, Catégorie, Actions
- Badge stock rouge si < 5
- Boutons Modifier/Supprimer par ligne
- Bouton "Ajouter un produit" (modal)

**Créer/Modifier produit** (modal) :
- Formulaire complet :
  - Nom (required)
  - Description (textarea)
  - Prix (number, min 0.01)
  - Stock (number, min 0)
  - Catégorie (select)
  - Couleur (input)
  - Taille (input)
  - Tissu (input)
  - Image URL (input)
  - Actif (checkbox)
- Validation front + back
- Feedback succès/erreur

**Supprimer produit** :
- Confirmation modal
- Soft delete (actif = false) ou hard delete (cascade)

#### 3. **Gestion Commandes**

**Fonctionnalités** :
- Liste toutes les commandes (tableau)
- Filtres par statut
- Voir détails commande (modal)
- Changer statut commande (select)
- Voir infos client (email, adresse)
- Voir liste produits commandés

#### 4. **Gestion Catégories**

- Liste catégories (grille de cartes)
- Statistiques par catégorie (nombre produits, CA)
- Créer/Modifier/Supprimer catégorie

### 🔌 API REST - Endpoints Principaux

**Base URL** : `http://localhost/Feminine%20Aura_last/api`

#### Authentification

**POST** `/auth/register` - Inscription
```json
Request:
{
  "email": "client@example.com",
  "mot_de_passe": "password123"
}

Response (200):
{
  "success": true,
  "message": "Inscription réussie",
  "data": {
    "user": {
      "id_utilisatrice": 5,
      "email": "client@example.com",
      "role": "client"
    },
    "token": "eyJpZCI6NSwiZW1haWw..."
  }
}
```

**POST** `/auth/login` - Connexion
```json
Request:
{
  "email": "admin@feminineaura.com",
  "mot_de_passe": "password123"
}

Response (200):
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id_utilisatrice": 1,
      "email": "admin@feminineaura.com",
      "role": "admin"
    },
    "token": "eyJpZCI6MSwiZW1haWw..."
  }
}
```

#### Produits

**GET** `/produits` - Liste produits avec filtres
```
Query params:
  ?categorie=1
  &prix_min=20
  &prix_max=100
  &couleur=Rouge
  &taille=M
  &recherche=satin
  &page=1
  &limit=20

Response (200):
{
  "success": true,
  "message": "Success",
  "data": {
    "produits": [
      {
        "id_produit": 1,
        "nom": "Femina satin",
        "description": "Pensé pour la femme moderne...",
        "prix": "74.99",
        "stock": 50,
        "couleur": "Rouge",
        "taille": "M",
        "tissu": "Satin",
        "image_url": "/red-lingerie-bra.jpg",
        "categorie_nom": "Ensembles",
        "categorie_slug": "ensembles"
      },
      // ... 13 autres produits
    ],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 14,
      "pages": 1
    }
  }
}
```

**GET** `/produits/{id}` - Détails produit + avis
```json
Response (200):
{
  "success": true,
  "data": {
    "produit": {
      "id_produit": 1,
      "nom": "Femina satin",
      "prix": "74.99",
      // ... toutes les infos
    },
    "avis": {
      "moyenne": 4.5,
      "total": 12,
      "liste": [
        {
          "note": 5,
          "commentaire": "Excellente qualité !",
          "date_avis": "2024-12-10"
        }
      ]
    }
  }
}
```

**POST** `/produits` (Admin) - Créer produit
```json
Request:
Headers: Authorization: Bearer {admin_token}
{
  "id_categorie": 1,
  "nom": "Nouveau produit",
  "description": "Description...",
  "prix": 49.99,
  "stock": 50,
  "couleur": "Noir",
  "taille": "M",
  "tissu": "Dentelle",
  "image_url": "/image.jpg"
}

Response (201):
{
  "success": true,
  "message": "Produit créé avec succès",
  "data": {
    "id_produit": 15
  }
}
```

#### Panier

**GET** `/panier` - Récupérer panier utilisateur
```json
Headers: Authorization: Bearer {token}

Response (200):
{
  "success": true,
  "data": {
    "items": [
      {
        "id_produit": 5,
        "nom": "Love Kit",
        "prix": "75.00",
        "quantite": 2,
        "image_url": "/luxury-lingerie-set.jpg",
        "sous_total": 150.00
      }
    ],
    "total": 150.00
  }
}
```

**POST** `/panier/add` - Ajouter article
```json
Request:
Headers: Authorization: Bearer {token}
{
  "id_produit": 5,
  "quantite": 2
}

Response (200):
{
  "success": true,
  "message": "Produit ajouté au panier"
}
```

#### Favoris

**POST** `/favoris/toggle` - Toggle favori
```json
Request:
Headers: Authorization: Bearer {token}
{
  "id_produit": 8
}

Response (200):
{
  "success": true,
  "message": "Produit ajouté aux favoris",
  "data": {
    "is_favorite": true
  }
}
```

#### Commandes

**POST** `/commandes` - Créer commande
```json
Request:
Headers: Authorization: Bearer {token}
{
  "adresse_livraison": "15 Rue de la Paix",
  "ville": "Paris",
  "code_postal": "75001",
  "telephone": "+33 6 12 34 56 78",
  "mode_paiement": "carte"
}

Response (201):
{
  "success": true,
  "message": "Commande créée avec succès",
  "data": {
    "id_commande": 12,
    "numero_commande": "CMD-20241214-00012",
    "total": 150.00
  }
}
```

### 🔐 Système d'Authentification

**Flow d'authentification** :

```
1. Client envoie email + mot_de_passe
   POST /api/auth/login

2. API vérifie en base :
   - Email existe ?
   - Mot de passe match (password_verify avec hash bcrypt) ?
   - Récupère le rôle

3. API génère token :
   - Token = base64(JSON{id, email, role, exp})
   - (Note: token simplifié pour ce projet, JWT serait mieux en production)

4. API retourne user + token

5. Frontend stocke token :
   - Dans Context API (state global)
   - Dans localStorage (persistance)

6. Requêtes suivantes incluent token :
   - Header: Authorization: Bearer {token}

7. API vérifie token :
   - Decode base64
   - Vérifie expiration
   - Vérifie rôle si besoin (admin routes)
```

**Protection routes** :
```typescript
// Middleware Next.js (hypothétique)
export function middleware(request: NextRequest) {
  const token = request.cookies.get('token')?.value;

  if (request.nextUrl.pathname.startsWith('/admin')) {
    if (!token || !isAdmin(token)) {
      return NextResponse.redirect('/admin/login');
    }
  }

  return NextResponse.next();
}
```

**Côté API PHP** :
```php
// AuthController.php
public function login() {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validation
    if (!isset($data['email']) || !isset($data['mot_de_passe'])) {
        Response::badRequest('Email et mot de passe requis');
    }

    // Vérifier utilisateur
    $user = $this->utilisatriceModel->findByEmail($data['email']);

    if (!$user || !password_verify($data['mot_de_passe'], $user['mot_de_passe'])) {
        Response::unauthorized('Email ou mot de passe incorrect');
    }

    // Générer token
    $token = base64_encode(json_encode([
        'id' => $user['id_utilisatrice'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + 3600 // 1h
    ]));

    Response::success([
        'user' => $user,
        'token' => $token
    ], 'Connexion réussie');
}
```

### 🔄 Gestion d'État (Context API)

J'ai utilisé **React Context API** pour gérer 4 états globaux :

#### 1. AuthContext (Authentification)
```typescript
interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (email: string, password: string) => Promise<void>;
  logout: () => void;
}
```

#### 2. CartContext (Panier)
```typescript
interface CartContextType {
  items: CartItem[];
  addItem: (item: CartItem) => Promise<void>;
  removeItem: (id: string) => Promise<void>;
  updateQuantity: (id: string, quantity: number) => Promise<void>;
  clearCart: () => Promise<void>;
  total: number;
}
```

#### 3. FavoritesContext (Favoris)
```typescript
interface FavoritesContextType {
  favorites: Product[];
  toggleFavorite: (product: Product) => Promise<void>;
  isFavorite: (productId: string) => boolean;
}
```

#### 4. AdminContext (Admin Auth)
```typescript
interface AdminContextType {
  admin: AdminUser | null;
  isAdmin: boolean;
  loginAdmin: (email: string, password: string) => Promise<boolean>;
  logoutAdmin: () => void;
  loading: boolean;
}
```

**Avantages de cette approche** :
- Pas de dépendance externe (Redux, Zustand)
- État partagé entre composants
- Logique métier centralisée
- Synchronisation localStorage ↔ API transparente

---

## 6. Tests et Validation

### 📦 Installation Pas à Pas

#### Prérequis Système
- Windows 10/11
- Node.js 18+ ([télécharger](https://nodejs.org/))
- XAMPP 8.1+ ([télécharger](https://www.apachefriends.org/))
- Navigateur moderne (Chrome, Firefox, Edge)
- Éditeur de code (VS Code recommandé)

#### Étape 1 : Télécharger le Projet
```bash
# Méthode 1 : Cloner avec Git
git clone https://github.com/votre-nom/feminine-aura.git
cd feminine-aura

# Méthode 2 : Télécharger ZIP
# Extraire dans c:\xampp\htdocs\
```

#### Étape 2 : Installer les Dépendances
```bash
# Dans le dossier du projet
npm install
```

Cela installe :
- Next.js, React, TypeScript
- Tailwind CSS
- Shadcn/UI components
- Lucide icons
- ~200 packages (node_modules/)

#### Étape 3 : Configurer XAMPP

1. **Démarrer XAMPP Control Panel**
2. **Start Apache** (port 80)
3. **Start MySQL** (port 3306)
4. Vérifier les voyants verts

#### Étape 4 : Créer la Base de Données

**Option A : Via script automatique** (recommandé)
```
http://localhost/Feminine%20Aura_last/api/database/install-db.php
```

**Option B : Via ligne de commande**
```bash
# Ouvrir terminal dans c:\xampp\mysql\bin\
mysql -u root -p

# Copier/coller le contenu de api/database/schema.sql
# Puis le contenu de api/database/seed.sql
```

Cela crée :
- Base `feminine_aura`
- 13 tables
- 14 produits de démonstration
- 4 catégories
- 1 compte admin
- Quelques comptes clients de test

#### Étape 5 : Configurer l'API Backend

Créer `api/.env` (copier depuis `api/.env.example`) :
```env
# Database
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Application
APP_ENV=development
APP_DEBUG=true

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
```

#### Étape 6 : Configurer le Frontend

Créer `.env.local` à la racine :
```env
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api
NODE_ENV=development
```

#### Étape 7 : Lancer l'Application

```bash
# Terminal 1 : Frontend Next.js
npm run dev

# L'application démarre sur http://localhost:3000
```

Backend (Apache) déjà lancé via XAMPP.

#### Étape 8 : Vérifier l'Installation

**Tester l'API** :
```bash
curl http://localhost/Feminine%20Aura_last/api
# Doit retourner du JSON avec info API
```

**Tester le Frontend** :
```
http://localhost:3000
# Doit afficher la page d'accueil avec 14 produits
```

**Vérifier la console (F12)** :
```javascript
// Doit afficher :
🔵 API Request: http://localhost/Feminine%20Aura_last/api/produits
🟢 API Response status: 200
📦 API Data: {success: true, data: {...}}
```

### 👥 Comptes de Test

#### Compte Administrateur
```
URL : http://localhost:3000/admin
Email : admin@feminineaura.com
Mot de passe : password123
```

**Fonctionnalités accessibles** :
- Dashboard avec statistiques
- CRUD produits
- Gestion commandes (changer statuts)
- Gestion catégories
- Modération avis clients
- Historique actions admin

#### Comptes Clients (créés par seed.sql)
```
Client 1:
Email : marie.dupont@example.com
Mot de passe : password123

Client 2:
Email : sophie.martin@example.com
Mot de passe : password123
```

**Fonctionnalités accessibles** :
- Navigation boutique
- Ajout panier/favoris
- Passage de commandes
- Historique commandes
- Laisser avis produits

### 🧪 Parcours de Tests Utilisateur

#### Test 1 : Navigation et Découverte (Non connecté)

1. **Page d'accueil** (`http://localhost:3000`)
   - [ ] Les 14 produits s'affichent en grille
   - [ ] Images chargées
   - [ ] Prix affichés correctement
   - [ ] Boutons "Ajouter au panier" et "Favoris" visibles

2. **Filtres**
   - [ ] Filtre par catégorie (Ensembles → 8 produits)
   - [ ] Filtre par prix (max 50€ → 3 produits)
   - [ ] Filtre par couleur (Rouge → 2 produits)
   - [ ] Recherche textuelle ("satin" → 2 produits)
   - [ ] Réinitialiser filtres

3. **Page Produit**
   - [ ] Cliquer sur un produit
   - [ ] URL change (`/product/1`)
   - [ ] Détails affichés (nom, prix, description, stock)
   - [ ] Galerie d'images fonctionnelle
   - [ ] Sélecteurs taille/couleur/quantité
   - [ ] Avis clients visibles
   - [ ] Produits similaires en bas

4. **Ajout Panier (localStorage)**
   - [ ] Sélectionner taille M, couleur Rouge, quantité 2
   - [ ] Cliquer "Ajouter au panier"
   - [ ] Notification succès
   - [ ] Compteur panier header s'incrémente
   - [ ] Aller sur `/cart`
   - [ ] Produit affiché avec bonne quantité

5. **Favoris (localStorage)**
   - [ ] Cliquer cœur sur un produit
   - [ ] Cœur devient plein (rose)
   - [ ] Aller sur `/favorites`
   - [ ] Produit présent dans la liste
   - [ ] Retirer des favoris fonctionne

6. **Tentative Commande (sans auth)**
   - [ ] Aller sur `/cart`
   - [ ] Cliquer "Commander"
   - [ ] Redirigé vers `/auth/login`
   - [ ] Message "Connectez-vous pour commander"

#### Test 2 : Authentification

1. **Inscription**
   - [ ] Aller sur `/auth/register`
   - [ ] Remplir email `test@example.com`, password `Test1234!`
   - [ ] Cliquer "S'inscrire"
   - [ ] Message succès
   - [ ] Redirection vers `/`
   - [ ] Email affiché dans header (connecté)

2. **Déconnexion**
   - [ ] Cliquer "Se déconnecter" (header)
   - [ ] Retour état non connecté
   - [ ] localStorage token supprimé

3. **Connexion**
   - [ ] Aller sur `/auth/login`
   - [ ] Email `marie.dupont@example.com`, password `password123`
   - [ ] Cliquer "Se connecter"
   - [ ] Redirection vers `/`
   - [ ] Email affiché header

#### Test 3 : Panier et Commande (Connecté)

1. **Synchronisation Panier**
   - [ ] Ajouter 2 produits au panier
   - [ ] Recharger page (F5)
   - [ ] Panier toujours présent (API)
   - [ ] Ouvrir onglet incognito, se connecter
   - [ ] Panier synchronisé

2. **Modification Panier**
   - [ ] Modifier quantité (spinner +/-)
   - [ ] Total recalculé instantanément
   - [ ] Supprimer un article
   - [ ] Confirmation demandée

3. **Passage Commande**
   - [ ] Panier avec 2+ articles
   - [ ] Cliquer "Commander"
   - [ ] Formulaire livraison affiché
   - [ ] Remplir adresse complète
   - [ ] Sélectionner mode paiement
   - [ ] Cliquer "Valider commande"
   - [ ] Message succès + numéro commande
   - [ ] Panier vidé automatiquement
   - [ ] Commande dans `/commandes`

#### Test 4 : Historique et Avis

1. **Mes Commandes**
   - [ ] Aller sur `/commandes`
   - [ ] Dernière commande affichée
   - [ ] Statut correct (en_attente)
   - [ ] Cliquer "Voir détails"
   - [ ] Modal avec liste produits + total

2. **Laisser Avis**
   - [ ] Aller sur un produit acheté
   - [ ] Formulaire avis visible (si connecté + acheté)
   - [ ] Sélectionner 5 étoiles
   - [ ] Écrire commentaire
   - [ ] Soumettre
   - [ ] Message "Avis en attente de modération"
   - [ ] Avis non visible publiquement (approuve=false)

### 🔐 Parcours de Tests Administrateur

#### Test 1 : Connexion Admin

1. **Login Admin**
   - [ ] Aller sur `http://localhost:3000/admin`
   - [ ] Email `admin@feminineaura.com`, password `password123`
   - [ ] Cliquer "Se connecter"
   - [ ] Redirection `/admin/dashboard`
   - [ ] 4 cartes statistiques affichées

2. **Vérification Rôle**
   - [ ] Se connecter avec compte client sur `/admin`
   - [ ] Message erreur "Accès non autorisé"
   - [ ] Pas de redirection dashboard

#### Test 2 : Gestion Produits

1. **Liste Produits**
   - [ ] Onglet "Produits" actif par défaut
   - [ ] Tableau 14 produits
   - [ ] Colonnes : Image, Nom, Prix, Stock, Catégorie, Actions
   - [ ] Badge rouge si stock < 5

2. **Créer Produit**
   - [ ] Cliquer "Ajouter un produit"
   - [ ] Modal formulaire s'ouvre
   - [ ] Remplir tous les champs :
     ```
     Nom : Soutien-gorge Test
     Description : Produit de test pour rapport
     Prix : 39.99
     Stock : 100
     Catégorie : Soutiens-gorge
     Couleur : Bleu
     Taille : L
     Tissu : Coton
     Image URL : /test-image.jpg
     Actif : true
     ```
   - [ ] Cliquer "Créer"
   - [ ] Modal se ferme
   - [ ] Nouveau produit dans la liste (15 produits total)
   - [ ] Notification succès

3. **Modifier Produit**
   - [ ] Cliquer icône crayon sur produit #15
   - [ ] Modal pré-rempli avec données
   - [ ] Modifier prix : 34.99
   - [ ] Modifier stock : 50
   - [ ] Cliquer "Enregistrer"
   - [ ] Prix et stock mis à jour dans tableau
   - [ ] Notification succès

4. **Supprimer Produit**
   - [ ] Cliquer icône poubelle sur produit #15
   - [ ] Modal confirmation
   - [ ] Cliquer "Confirmer"
   - [ ] Produit disparaît (14 produits restants)
   - [ ] Notification succès

#### Test 3 : Gestion Commandes

1. **Liste Commandes**
   - [ ] Onglet "Commandes"
   - [ ] Tableau toutes les commandes
   - [ ] Colonnes : N°, Client, Date, Total, Statut, Actions
   - [ ] Badges colorés selon statut

2. **Changer Statut**
   - [ ] Sélectionner commande "en_attente"
   - [ ] Cliquer "Modifier statut"
   - [ ] Sélectionner "confirmee"
   - [ ] Cliquer "Enregistrer"
   - [ ] Badge devient vert "Confirmée"
   - [ ] Email notification envoyé client (si implémenté)

3. **Détails Commande**
   - [ ] Cliquer "Voir détails" sur une commande
   - [ ] Modal affiche :
     - Infos client (email, téléphone)
     - Adresse livraison complète
     - Liste produits commandés
     - Quantités et prix unitaires
     - Total TTC

#### Test 4 : Gestion Catégories

1. **Liste Catégories**
   - [ ] Onglet "Catégories"
   - [ ] 4 cartes affichées (Soutiens-gorge, Culottes, Ensembles, Nuisettes)
   - [ ] Chaque carte montre : Nom, Description, Slug, Nb produits

2. **Créer Catégorie**
   - [ ] Bouton "Ajouter catégorie"
   - [ ] Nom : Pyjamas
   - [ ] Description : Ensembles confortables pour la nuit
   - [ ] Slug : pyjamas (auto-généré)
   - [ ] Cliquer "Créer"
   - [ ] Nouvelle carte apparaît

#### Test 5 : Modération Avis

1. **Liste Avis En Attente**
   - [ ] Section "Avis à modérer" (si implémentée)
   - [ ] Liste avis avec `approuve=false`
   - [ ] Affiche : Produit, Client, Note, Commentaire, Date

2. **Approuver Avis**
   - [ ] Cliquer "Approuver"
   - [ ] Avis devient public
   - [ ] Visible sur page produit
   - [ ] Moyenne notes recalculée

3. **Rejeter Avis**
   - [ ] Cliquer "Rejeter"
   - [ ] Avis supprimé définitivement
   - [ ] Pas affiché publiquement

### 📬 Tests API (Postman / curl)

#### Collection Postman Recommandée

**Base URL** : `http://localhost/Feminine%20Aura_last/api`

**Endpoints à tester** :

1. **GET** `/` - Info API
2. **GET** `/produits` - Liste produits
3. **GET** `/produits/1` - Produit détails
4. **GET** `/categories` - Liste catégories
5. **POST** `/auth/register` - Inscription
6. **POST** `/auth/login` - Connexion
7. **GET** `/auth/me` (+ token) - Profil
8. **GET** `/panier` (+ token) - Panier
9. **POST** `/panier/add` (+ token) - Ajouter panier
10. **GET** `/favoris` (+ token) - Favoris
11. **POST** `/favoris/toggle` (+ token) - Toggle favori
12. **GET** `/commandes` (+ token) - Mes commandes
13. **POST** `/commandes` (+ token) - Créer commande
14. **POST** `/produits` (+ admin token) - Créer produit
15. **PUT** `/produits/1` (+ admin token) - Modifier produit
16. **DELETE** `/produits/1` (+ admin token) - Supprimer produit

**Exemple curl** :
```bash
# Login
curl -X POST "http://localhost/Feminine%20Aura_last/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@feminineaura.com","mot_de_passe":"password123"}'

# Récupérer le token dans la réponse JSON

# Utiliser le token
curl -X GET "http://localhost/Feminine%20Aura_last/api/panier" \
  -H "Authorization: Bearer eyJpZCI6MSwiZW1haWw..."
```

### 🐛 Résolution de Problèmes Courants

#### Problème 1 : "Failed to fetch" (Erreur CORS)

**Symptôme** : Console affiche erreur rouge CORS
**Cause** : Headers CORS mal configurés ou dupliqués

**Solution** :
1. Vérifier `api/.env` contient `CORS_ALLOWED_ORIGINS=http://localhost:3000`
2. Vérifier `api/config/cors.php` charge bien le `.env`
3. Vérifier `.htaccess` ne définit PAS de headers CORS (conflit)
4. Redémarrer Apache dans XAMPP
5. Vider cache navigateur (Ctrl+Shift+R)

**Test de vérification** :
```bash
curl -X OPTIONS "http://localhost/Feminine%20Aura_last/api/produits" \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: GET" \
  -i

# Doit retourner :
# Access-Control-Allow-Origin: http://localhost:3000
# Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS
```

#### Problème 2 : Hydration Mismatch (Next.js)

**Symptôme** : Warning React "Hydration failed"
**Cause** : Différence entre rendu serveur et client (localStorage)

**Solution** :
1. N'utiliser localStorage que côté client
2. Utiliser `useEffect` pour charger depuis localStorage :
```typescript
const [mounted, setMounted] = useState(false);

useEffect(() => {
  setMounted(true);
  // Charger localStorage ici
}, []);

if (!mounted) return null; // Ou loader
```

#### Problème 3 : Produits ne s'affichent pas

**Symptôme** : Grille vide, aucun produit
**Causes possibles** :
1. API ne répond pas
2. Base de données vide
3. CORS bloque la requête

**Diagnostic** :
```bash
# 1. Tester API directement
curl "http://localhost/Feminine%20Aura_last/api/produits"

# 2. Vérifier console navigateur (F12)
# Chercher :
#   - Erreurs CORS (rouge)
#   - 🔵 API Request
#   - 🟢 API Response status: 200
#   - 📦 API Data

# 3. Vérifier DB
# PhpMyAdmin : http://localhost/phpmyadmin
# Base : feminine_aura
# Table : produit
# Doit contenir 14 lignes
```

**Solutions** :
- Si API ne répond pas → Redémarrer Apache (XAMPP)
- Si DB vide → Relancer `install-db.php`
- Si CORS → Voir Problème 1

#### Problème 4 : Token Invalid / Expired

**Symptôme** : Erreur 401 Unauthorized sur requêtes authentifiées
**Cause** : Token expiré (1h par défaut) ou invalide

**Solution** :
1. Se déconnecter puis reconnecter (obtenir nouveau token)
2. Vérifier format token :
   ```javascript
   // Console navigateur
   localStorage.getItem('feminine-aura-user')
   // Doit contenir {user: {...}, token: "eyJ..."}
   ```
3. Vérifier backend décode bien le token :
   ```php
   // api/utils/Auth.php
   $decoded = json_decode(base64_decode($token), true);
   if (time() > $decoded['exp']) {
       // Token expiré
   }
   ```

#### Problème 5 : Images ne chargent pas

**Symptôme** : Icônes cassées, images 404
**Cause** : Chemins images incorrects

**Solution** :
1. Vérifier images dans `public/images/`
2. Utiliser chemins relatifs : `/images/product.jpg` (pas `./images`)
3. En Next.js, utiliser `<Image>` de `next/image` pour optimisation :
   ```tsx
   import Image from 'next/image';
   <Image src="/images/product.jpg" alt="Product" width={500} height={500} />
   ```

#### Problème 6 : Panier ne persiste pas

**Symptôme** : Panier vide après F5
**Diagnostic** :
```javascript
// Console (F12)
localStorage.getItem('feminine-aura-cart')
// Doit retourner un JSON array d'items
```

**Solution** :
1. Vérifier Context API sauvegarde bien :
   ```typescript
   useEffect(() => {
     if (isLoaded && !user?.token) {
       localStorage.setItem('feminine-aura-cart', JSON.stringify(items));
     }
   }, [items, isLoaded, user]);
   ```
2. Vérifier `isLoaded` est bien à `true` avant de sauvegarder
3. Si connecté, vérifier sync avec API

---

## 7. Conclusion

### 💪 Compétences Acquises

Ce projet m'a permis de développer un large éventail de compétences techniques et transversales :

#### Compétences Techniques

**Frontend** :
- Maîtrise de **React 19** et du système de hooks (useState, useEffect, useContext)
- Utilisation avancée de **Next.js 16** (App Router, Server/Client Components, routing dynamique)
- **TypeScript** : typage strict, interfaces, types génériques
- **Tailwind CSS** : design system, responsive design, utility classes
- Gestion d'état avec **Context API** (alternative à Redux pour projets moyens)
- Intégration de bibliothèques UI (**Shadcn/UI**, **Lucide Icons**)

**Backend** :
- Architecture **MVC** en PHP (séparation Controllers/Models/Views)
- Création d'**API REST** complète avec authentification
- **PDO** et **Prepared Statements** (sécurité SQL injection)
- Gestion **CORS** pour requêtes cross-origin
- Hachage sécurisé de mots de passe (**bcrypt**)
- Système d'autorisation basé sur les rôles

**Base de Données** :
- Modélisation relationnelle (**MCD**, **MLD**)
- Normalisation **3NF** (éviter redondances)
- Contraintes d'intégrité (FK, UNIQUE, CHECK, CASCADE, SET NULL)
- Optimisation avec **index** sur colonnes fréquemment requêtées
- Requêtes complexes avec **JOIN**, **GROUP BY**, agrégations

**DevOps & Outils** :
- Environnement de développement **XAMPP** (Apache + MySQL + PHP)
- Configuration **CORS** et `.htaccess`
- Variables d'environnement (`.env`)
- **Git** pour versioning
- **npm** pour gestion dépendances

#### Compétences Transversales

**Architecture Logicielle** :
- Séparation des responsabilités (frontend ↔ API ↔ DB)
- Design patterns (MVC, Repository, Singleton pour DB)
- RESTful API design (stateless, cacheable, routes sémantiques)

**Résolution de Problèmes** :
- Debugging méthodique (logs console, DevTools, var_dump PHP)
- Analyse d'erreurs (CORS, hydration, 404, 500)
- Recherche autonome (documentation officielle, Stack Overflow, GitHub Issues)

**Gestion de Projet** :
- Planification des fonctionnalités (MVP → itérations)
- Priorisation (fonctionnalités core vs nice-to-have)
- Documentation (README, commentaires code, diagrammes)

**Veille Technologique** :
- Apprentissage de technologies récentes (Next.js 16, React 19)
- Compréhension des évolutions du web (Server Components, Turbopack)
- Adoption des bonnes pratiques (sécurité, performance, accessibilité)

### 🚧 Défis Rencontrés et Solutions

#### Défi 1 : CORS "Failed to fetch"

**Problème** : Erreur CORS bloquant toutes les requêtes API depuis le frontend.

**Cause** : Headers CORS définis à deux endroits (`.htaccess` ET `cors.php`), créant des headers dupliqués que le navigateur rejetait.

**Solution** :
1. Suppression des headers CORS dans `.htaccess`
2. Gestion centralisée uniquement dans `cors.php`
3. Ajout de `mode: 'cors'` et `credentials: 'include'` dans les fetch frontend
4. Vérification que `.env` est chargé AVANT l'initialisation CORS

**Leçon** : Les headers HTTP ne doivent jamais être dupliqués. Toujours centraliser la configuration au même endroit.

#### Défi 2 : Hydration Mismatch (Next.js)

**Problème** : Warning React "Hydration failed" car le rendu serveur différait du rendu client à cause de localStorage.

**Cause** : localStorage n'existe pas côté serveur (Node.js), donc les composants rendus côté serveur avaient un état initial différent du client.

**Solution** :
```typescript
const [mounted, setMounted] = useState(false);

useEffect(() => {
  setMounted(true);
  // Charger localStorage uniquement côté client
  const savedCart = localStorage.getItem('cart');
  if (savedCart) setCart(JSON.parse(savedCart));
}, []);

// Rendu conditionnel
if (!mounted) return <Loader />;
return <CartContent />;
```

**Leçon** : Toujours différencier le code serveur du code client avec Next.js. Utiliser `useEffect` pour accéder à `window`, `localStorage`, etc.

#### Défi 3 : Gestion Panier Hybride (localStorage + API)

**Problème** : Synchroniser le panier entre localStorage (non connecté) et API (connecté) sans perte de données.

**Solution** :
1. Au login, fusionner panier localStorage avec panier API
2. Lors de chaque modification, sauvegarder dans les deux si connecté
3. Utiliser `isLoaded` flag pour éviter les écrasements au chargement

```typescript
useEffect(() => {
  if (user?.token) {
    // Fusionner localStorage → API
    const localCart = JSON.parse(localStorage.getItem('cart') || '[]');
    localCart.forEach(item => apiAddToCart(user.token, item.id, item.quantity));

    // Recharger depuis API (source de vérité)
    const apiCart = await getCart(user.token);
    setItems(apiCart.data.items);

    // Vider localStorage (API devient la référence)
    localStorage.removeItem('cart');
  }
}, [user]);
```

**Leçon** : Toujours définir une "source de vérité" (ici l'API) et synchroniser dans un seul sens.

#### Défi 4 : Performance avec 14 Produits + Filtres

**Problème** : Re-rendu complet de la liste à chaque changement de filtre.

**Solution** :
1. Filtrage côté serveur (query params API)
2. Mémoïzation des composants produits (React.memo)
3. Debouncing de la recherche textuelle (useDebounce hook)

```typescript
const debouncedSearch = useDebounce(searchTerm, 500);

useEffect(() => {
  fetchProducts({ search: debouncedSearch });
}, [debouncedSearch]);
```

**Leçon** : Ne pas hésiter à déplacer la logique côté serveur pour des performances optimales.

### 🤖 Rôle de Claude AI dans l'Apprentissage

**Claude AI** a joué un rôle de mentor technique tout au long de ce projet. Voici comment il m'a aidée :

**1. Debugging Assisté**
Quand je rencontrais une erreur, je lui fournissais :
- Le message d'erreur complet
- Le code concerné
- Les logs console/terminal

Claude m'expliquait :
- La cause racine du problème
- Pourquoi cela se produisait
- Plusieurs solutions possibles avec leurs avantages/inconvénients

**2. Revue de Code et Bonnes Pratiques**
Claude a revu mon code et suggéré des améliorations :
- Typage TypeScript plus strict
- Patterns React modernes (hooks, Context API)
- Sécurité (validation inputs, prepared statements, CORS)
- Performance (mémoïzation, lazy loading)

**3. Explications Conceptuelles**
Quand je ne comprenais pas un concept :
- CORS et son fonctionnement
- Différence Server/Client Components Next.js
- Normalisation de bases de données
- Architecture MVC

Claude m'a fourni des explications claires avec des schémas et exemples.

**4. Documentation et Structuration**
Claude m'a aidée à :
- Structurer ce README de manière académique
- Créer les diagrammes MCD/MLD
- Documenter chaque endpoint API
- Rédiger des guides d'installation

**Limites de Claude** :
- Ne peut pas exécuter le code (j'ai dû tester moi-même)
- Parfois suggestions théoriques non adaptées au contexte
- Nécessite de bien formuler les questions (garbage in, garbage out)

**Mon Approche** :
- Toujours essayer de résoudre seule d'abord (Google, docs officielles)
- Demander à Claude quand bloquée >30min
- Ne jamais copier/coller sans comprendre
- Tester chaque suggestion et adapter à mon contexte

**Bilan** : Claude a été un **accélérateur d'apprentissage** précieux, mais n'a jamais remplacé ma propre réflexion et mes tests. C'est un outil formidable si utilisé intelligemment.

### ⚠️ Limitations Actuelles

**Techniques** :
1. **Authentification simplifiée** : Tokens base64 au lieu de vrais JWT signés
2. **Pas de paiement réel** : Intégration Stripe/PayPal manquante
3. **Upload d'images** : Uniquement URLs statiques, pas de vraie upload
4. **Email** : Pas d'envoi d'emails (confirmations, factures)
5. **Stock temps réel** : Pas de gestion des stocks en concurrence (locks)
6. **Tests automatisés** : Pas de tests unitaires/intégration (Jest, PHPUnit)
7. **SEO** : Balises meta basiques, pas de sitemap XML

**Fonctionnelles** :
1. **Variantes produits** : Pas de stock par couleur/taille individuellement
2. **Promotions** : Pas de codes promo ou réductions
3. **Multi-adresses** : Une seule adresse livraison par commande
4. **Retours** : Pas de gestion des retours produits
5. **Multi-langues** : Français uniquement
6. **Analytics** : Statistiques basiques, pas de tracking utilisateur avancé

**UX** :
1. **Animations** : Peu de transitions/animations
2. **Notifications** : Toast notifications basiques
3. **Accessibilité** : ARIA labels partiels, navigation clavier à améliorer

### 🚀 Améliorations Futures

**Court terme (1-2 mois)** :
- [ ] Implémenter vrais **JWT tokens** avec refresh tokens
- [ ] Ajouter **tests unitaires** (Jest pour frontend, PHPUnit pour backend)
- [ ] Intégration **Stripe** pour paiements réels
- [ ] Upload d'images réel (Cloudinary ou S3)
- [ ] Envoi **emails transactionnels** (confirmations, factures)
- [ ] Améliorer **SEO** (meta tags dynamiques, sitemap)

**Moyen terme (3-6 mois)** :
- [ ] **PWA** (Progressive Web App) : installation, offline mode
- [ ] **Notifications push** (nouveaux produits, promos, commandes)
- [ ] Système de **promotions** (codes promo, réductions temporaires)
- [ ] **Multi-variantes** produits (stock par couleur/taille)
- [ ] **Multi-adresses** livraison
- [ ] **Chat support** client (Intercom, Crisp)
- [ ] **Recommandations** produits (ML basique)

**Long terme (6-12 mois)** :
- [ ] **Application mobile** (React Native)
- [ ] **Internationalisation** (multi-langues, multi-devises)
- [ ] **Programme fidélité** (points, niveaux VIP)
- [ ] **Abonnements** (box mensuelle lingerie)
- [ ] **AR** (essayage virtuel)
- [ ] **Analytics avancés** (heatmaps, funnels conversion)
- [ ] **API publique** (pour partenaires/affiliés)

### 🎓 Bilan Personnel et Perspectives

#### Ce que j'ai appris

Au-delà des compétences techniques listées, ce projet m'a appris :

**1. L'importance de l'architecture**
Un projet mal structuré au départ devient ingérable. Prendre le temps de modéliser la base de données et définir l'architecture API dès le début a été crucial.

**2. La sécurité dès la conception**
Intégrer la sécurité après coup est difficile. J'ai appris à penser sécurité dès le départ : validation inputs, prepared statements, CORS, tokens, rôles.

**3. Le debugging méthodique**
Plutôt que de modifier au hasard, j'ai développé une approche systématique :
1. Reproduire le bug
2. Isoler la cause (logs, DevTools, var_dump)
3. Tester une hypothèse
4. Vérifier que la fix ne casse rien d'autre

**4. L'équilibre simplicité/perfection**
J'ai appris à ne pas sur-engineer : un token base64 suffit pour un projet étudiant, pas besoin de JWT complexe. L'important est que ça fonctionne et soit compréhensible.

**5. La documentation est essentielle**
Revenir sur mon code 2 semaines après sans commentaires est difficile. Documenter au fur et à mesure m'a fait gagner énormément de temps.

#### Mon Evolution Designer → Développeuse

Ce projet illustre ma transition de designer UX/UI vers développeuse full stack :

**Avant (100% Design)** :
- Je créais des maquettes Figma parfaites
- Mais frustrée de ne pas pouvoir les implémenter
- Dépendante des développeurs pour concrétiser mes idées

**Après (Design + Dev)** :
- Je peux designer ET développer mes idées
- Compréhension intime des contraintes techniques
- Meilleure communication avec les équipes dev
- Autonomie totale sur des projets personnels

**Ce que le design m'a apporté au dev** :
- Souci du détail (UX, accessibilité, responsive)
- Vision globale utilisateur
- Sens de l'esthétique (même dans le code !)

**Ce que le dev m'a apporté au design** :
- Designs réalisables techniquement
- Compréhension des coûts de développement
- Meilleure communication avec les devs

#### Perspectives Professionnelles

Ce projet démontre que je suis capable de :

✅ Concevoir et développer une application complète de A à Z
✅ Travailler en full stack (frontend + backend + DB)
✅ Résoudre des problèmes techniques complexes de manière autonome
✅ Documenter mon travail de manière professionnelle
✅ Apprendre rapidement de nouvelles technologies

**Objectifs professionnels** :
- Poste **Développeuse Full Stack Junior** dans une startup/agence
- Ou **Designer/Développeuse** (profil hybride rare et recherché)
- Continuer à apprendre (Vue.js, Node.js, Docker, CI/CD)
- Contribuer à des projets open-source

**Ce projet dans mon portfolio** :
Feminine Aura sera mon projet phare démontrant :
- Maîtrise stack moderne (Next.js, React, TypeScript)
- Compétences backend (PHP, API REST, MySQL)
- Capacité à mener un projet de bout en bout
- Attention au détail (design, UX, sécurité)

#### Mot de la Fin

Développer Feminine Aura a été un **voyage d'apprentissage intense et passionnant**. Chaque bug résolu, chaque fonctionnalité implémentée, chaque ligne de code écrite m'a rapprochée de mon objectif : devenir une développeuse full stack autonome et compétente.

Ce projet représente bien plus que quelques milliers de lignes de code. C'est la preuve concrète que, avec de la détermination, de bonnes ressources (merci Claude !), et beaucoup de persévérance, on peut apprendre des technologies complexes et créer des applications fonctionnelles et professionnelles.

Je suis fière du chemin parcouru et excitée par tout ce qu'il me reste à apprendre. **Le développement web est un domaine en constante évolution, et c'est exactement ce qui me passionne.**

---

## 8. Liens et Ressources

### 🔗 Projet

**Dépôt GitHub** : [https://github.com/votre-nom/feminine-aura](https://github.com/votre-nom/feminine-aura)

**Démo Live** : [https://feminine-aura.vercel.app](https://feminine-aura.vercel.app) *(à déployer)*

**Portfolio** : [https://votre-portfolio.com](https://votre-portfolio.com)

### 📚 Documentation Technique

**Documentation Interne** :
- [📊 MCD - Modèle Conceptuel](docs/database/MCD.md)
- [📋 MLD - Modèle Logique](docs/database/MLD.md)
- [📖 Dictionnaire de Données](docs/database/DICTIONNAIRE-DONNEES.md)
- [🔧 Guide Diagnostic](DIAGNOSTIC-RAPIDE.md)
- [✅ Corrections CORS](CORRECTION-FINALE-API.md)

**Technologies Utilisées** :
- [Next.js Documentation](https://nextjs.org/docs)
- [React Documentation](https://react.dev)
- [TypeScript Documentation](https://www.typescriptlang.org/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Shadcn/UI](https://ui.shadcn.com)
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc)

### 📧 Contact

**Email** : votre.email@example.com
**LinkedIn** : [linkedin.com/in/votre-profil](https://linkedin.com/in/votre-profil)
**GitHub** : [github.com/votre-nom](https://github.com/votre-nom)

---

**Rapport rédigé par** : [Votre Nom]
**Formation** : [Nom de la Formation] - [Établissement]
**Date** : Décembre 2024
**Encadrant** : [Nom du Professeur]

---

*Ce projet a été réalisé dans le cadre de ma formation en développement web full stack. Tous les droits sont réservés.*

Made with 💖 using Next.js, React, PHP & MySQL
