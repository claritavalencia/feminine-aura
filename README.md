Feminine Aura

Application e-commerce complète de vente de lingerie féminine avec interface Next.js et API REST PHP.

![Version](https://img.shields.io/badge/version-1.0.0-pink)
![Next.js](https://img.shields.io/badge/Next.js-16-black)
![React](https://img.shields.io/badge/React-19-blue)
![PHP](https://img.shields.io/badge/PHP-8.1-purple)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)

---

## Description

**Feminine Aura** est une boutique en ligne élégante de lingerie féminine offrant :
- Catalogue de produits avec filtres et recherche
- Panier d'achat persistant
- Système de favoris
- Authentification utilisateur
- Gestion des commandes
- Avis et notes clients
- Dashboard administrateur complet
- Statistiques et rapports

---

## Technologies Utilisées

### Frontend

#### Framework & Librairies
- **[Next.js 16](https://nextjs.org/)** - Framework React avec App Router
- **[React 19](https://react.dev/)** - Bibliothèque UI
- **[TypeScript 5.x](https://www.typescriptlang.org/)** - Typage statique
- **[Tailwind CSS 4](https://tailwindcss.com/)** - Framework CSS utility-first

#### Components UI
- **[Shadcn/UI](https://ui.shadcn.com/)** - Collection de composants React
  - Button, Card, Dialog, Table, Tabs, Badge
  - Input, Select, Textarea, Label
  - DropdownMenu, Accordion
- **[Lucide React](https://lucide.dev/)** - Icônes SVG (600+ icônes)
  - ShoppingCart, Heart, User, Package
  - TrendingUp, Star, Shield, etc.

#### State Management
- **React Context API** - Gestion d'état globale
  - `AuthContext` - Authentification
  - `AdminContext` - Admin auth
  - `CartContext` - Panier
  - `FavoritesContext` - Favoris

#### Routing
- **Next.js App Router** - Routing fichiers
- **Dynamic Routes** - `[id]`, `[slug]`
- **Server Components** - Rendu serveur
- **Client Components** - Interactivité client

---

###  Backend

#### Serveur & Runtime
- **Apache 2.4.58** (XAMPP) - Serveur web
- **PHP 8.1.25** - Langage serveur
- **OpenSSL 3.1.3** - Sécurité HTTPS

#### Base de Données
- **MySQL 8.0** - SGBD relationnel
- **PDO (PHP Data Objects)** - Abstraction DB
- **Prepared Statements** - Sécurité SQL

#### API
- **REST API** - Architecture RESTful
- **JSON** - Format d'échange
- **CORS** - Cross-Origin Resource Sharing
- **JWT-like Tokens** - Authentification (base64)
- **Bearer Authentication** - Header Authorization

#### Architecture MVC
```
api/
├── controllers/       # Logique métier
│   ├── AuthController.php
│   ├── ProduitController.php
│   ├── CategorieController.php
│   ├── PanierController.php
│   ├── FavoriController.php
│   └── CommandeController.php
├── models/           # Modèles de données
│   ├── Utilisatrice.php
│   ├── Produit.php
│   ├── Categorie.php
│   ├── Panier.php
│   ├── Favori.php
│   └── Commande.php
├── config/           # Configuration
│   ├── database.php
│   └── cors.php
└── utils/            # Utilitaires
    ├── Response.php
    └── Validator.php
```

#### Sécurité
- **Bcrypt (PASSWORD_BCRYPT)** - Hachage mots de passe
- **CORS Headers** - Configuration sécurisée
- **Input Validation** - Validation entrées
- **SQL Prepared Statements** - Protection SQL injection
- **Role-Based Access** - Contrôle accès admin

---

### Base de Données

#### Structure (13 tables)

**Tables Principales** :
- `utilisatrice` - Utilisateurs (clients + admins)
- `categorie` - Catégories produits
- `produit` - Catalogue produits
- `commande` - Commandes clients
- `paiement` - Transactions

**Tables de Liaison** :
- `ligne_panier` - Articles paniers
- `ligne_commande` - Articles commandes
- `favori` - Produits favoris
- `avis` - Avis clients
- `utilisatrice_rapport` - Accès rapports

**Tables de Gestion** :
- `panier` - Paniers utilisateurs
- `admin_action` - Audit trail
- `rapport` - Statistiques

**Documentation complète** :
- [📊 Modèle Conceptuel (MCD)](docs/database/MCD.md)
- [📋 Modèle Logique (MLD)](docs/database/MLD.md)
- [📖 Dictionnaire de Données](docs/database/DICTIONNAIRE-DONNEES.md)

---

###  Design System

#### Palette de Couleurs
```css
--primary: #F34792      /* Rose principal */
--secondary: #1A0A1A    /* Noir profond */
--accent: #FF9FC9       /* Rose clair */
--success: #10B981      /* Vert */
--warning: #F59E0B      /* Orange */
--danger: #EF4444       /* Rouge */
--info: #3B82F6         /* Bleu */
```

#### Typographie
- **Police** : Geist (sans-serif moderne)
- **Tailles** : xs, sm, base, lg, xl, 2xl, 3xl, 4xl
- **Poids** : light, normal, medium, semibold, bold

---

## Installation

### Prérequis

- **Node.js** 18+ et npm
- **XAMPP** (Apache + MySQL + PHP 8.1+)
- **Git** (optionnel)

### Étape 1 : Cloner le projet

```bash
git clone https://github.com/votre-repo/feminine-aura.git
cd feminine-aura
```

Ou télécharger et extraire dans `c:\xampp\htdocs\`

### Étape 2 : Installer les dépendances

```bash
npm install
```

### Étape 3 : Configurer la base de données

1. **Démarrer XAMPP** :
   - Ouvrir XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Créer la base de données** :
   - Ouvrir navigateur : `http://localhost/Feminine%20Aura_last/api/database/install-db.php`
   - Ou via ligne de commande :
   ```bash
   mysql -u root -p < api/database/schema.sql
   mysql -u root -p feminine_aura < api/database/seed.sql
   ```

### Étape 4 : Configuration

#### Backend (API)

Créer `api/.env` (copier depuis `.env.example`) :

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Application Configuration
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:3000

# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
CORS_ALLOWED_METHODS=GET,POST,PUT,DELETE,OPTIONS
CORS_ALLOWED_HEADERS=Content-Type,Authorization,X-Requested-With

# JWT Configuration
JWT_SECRET=votre_secret_jwt_tres_securise_a_changer
JWT_EXPIRATION=3600
```

#### Frontend (Next.js)

Créer `.env.local` :

```env
# API Backend URL
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api

# Environment
NODE_ENV=development
```

### Étape 5 : Lancer l'application

#### Développement

```bash
# Frontend Next.js
npm run dev
```

Puis ouvrir : `http://localhost:3000`

