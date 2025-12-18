# 💎 Feminine Aura - Boutique de Lingerie

Une boutique en ligne moderne et élégante pour la vente de lingerie, développée avec Next.js 16 et une API REST PHP.

---

## 🚀 Technologies Utilisées

### Frontend
- **Next.js 16** - Framework React avec Server Components
- **React 19** - Bibliothèque UI
- **TypeScript** - Typage statique
- **Tailwind CSS 4** - Framework CSS utility-first
- **Shadcn/UI** - Composants UI réutilisables
- **Lucide React** - Icônes modernes

### Backend
- **PHP 8+** - Langage serveur
- **MySQL** - Base de données relationnelle
- **Architecture REST** - API RESTful
- **PDO** - Connexion sécurisée à la base de données

---

## 📁 Structure du Projet

```
Feminine Aura_last/
├── 📂 api/                         # Backend PHP
│   ├── 📂 config/                  # Configuration
│   │   ├── cors.php               # Configuration CORS
│   │   └── database.php           # Connexion base de données
│   ├── 📂 controllers/             # Contrôleurs API
│   │   ├── AuthController.php     # Authentification
│   │   ├── ProduitController.php  # Gestion produits
│   │   ├── CategorieController.php
│   │   ├── PanierController.php
│   │   ├── FavoriController.php
│   │   └── CommandeController.php
│   ├── 📂 models/                  # Modèles de données
│   │   ├── BaseModel.php          # Modèle de base
│   │   ├── Utilisatrice.php
│   │   ├── Produit.php
│   │   ├── Categorie.php
│   │   ├── Panier.php
│   │   ├── Favori.php
│   │   └── Commande.php
│   ├── 📂 database/                # Scripts SQL
│   │   ├── 1_create_tables.sql    # Création des tables
│   │   └── 2_insert_data.sql      # Données de test
│   ├── 📂 utils/                   # Utilitaires
│   │   └── Response.php           # Gestion des réponses JSON
│   ├── .env                       # Variables d'environnement
│   ├── index.php                  # Point d'entrée API
│   └── install-db.php             # Installation base de données
│
├── 📂 app/                         # Frontend Next.js (App Router)
│   ├── 📂 auth/                    # Authentification
│   ├── 📂 boutique/                # Catalogue produits
│   ├── 📂 cart/                    # Panier
│   ├── 📂 checkout/                # Commande
│   ├── 📂 profile/                 # Profil utilisateur
│   ├── 📂 favorites/               # Favoris
│   ├── 📂 context/                 # Contextes React
│   │   └── auth-context.tsx       # Contexte authentification
│   └── layout.tsx                 # Layout principal
│
├── 📂 components/                  # Composants React
│   ├── 📂 ui/                      # Composants Shadcn/UI
│   ├── Navbar.tsx                 # Navigation
│   ├── Footer.tsx                 # Pied de page
│   └── ProductCard.tsx            # Carte produit
│
├── 📂 lib/                         # Librairies et utilitaires
│   ├── api.ts                     # Client API
│   └── utils.ts                   # Fonctions utilitaires
│
├── 📂 public/                      # Ressources statiques
│   └── 📂 images/                  # Images
│
├── .env.local                     # Variables d'environnement (frontend)
├── package.json                   # Dépendances Node.js
└── tsconfig.json                  # Configuration TypeScript
```

---

## ⚙️ Installation

### Prérequis

- **XAMPP** (Apache + MySQL + PHP 8+)
- **Node.js** 18+ et npm
- **Git** (optionnel)

### Étape 1 : Cloner le projet

```bash
cd C:\xampp\htdocs
# Le projet est déjà dans "Feminine Aura_last"
cd "Feminine Aura_last"
```

### Étape 2 : Installer les dépendances frontend

```bash
npm install
```

### Étape 3 : Configuration de la base de données

#### Option A : Installation automatique (Recommandé)

1. **Démarrez XAMPP** :
   - Ouvrez XAMPP Control Panel
   - Démarrez **Apache** et **MySQL**

2. **Ouvrez dans votre navigateur** :
   ```
   http://localhost/Feminine%20Aura_last/api/install-db.php
   ```

3. Le script va automatiquement :
   - Créer la base de données `feminine_aura`
   - Créer toutes les tables
   - Insérer des données de test

#### Option B : Installation manuelle

```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données
CREATE DATABASE feminine_aura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE feminine_aura;

# Exécuter les scripts SQL
source C:/xampp/htdocs/Feminine Aura_last/api/database/1_create_tables.sql
source C:/xampp/htdocs/Feminine Aura_last/api/database/2_insert_data.sql
```

### Étape 4 : Configuration des variables d'environnement

#### Backend (PHP)

Le fichier `api/.env` existe déjà avec la configuration par défaut :

```env
# Database
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001

# JWT
JWT_SECRET=votre_secret_jwt_tres_securise_a_changer
JWT_EXPIRATION=3600
```

#### Frontend (Next.js)

Le fichier `.env.local` existe déjà :

```env
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api
NODE_ENV=development
```

### Étape 5 : Lancer l'application

#### 1. Démarrer XAMPP
- Apache : ✅ Running
- MySQL : ✅ Running

#### 2. Démarrer le serveur de développement Next.js

```bash
npm run dev
```

L'application sera accessible sur : **http://localhost:3000**

---

## 👥 Comptes de Test

Après l'installation, vous pouvez utiliser ces comptes :

### Administrateur
- **Email** : `admin@feminineaura.com`
- **Mot de passe** : `password123`

### Clients
- **Email** : `client1@example.com`
- **Mot de passe** : `password123`

- **Email** : `client2@example.com`
- **Mot de passe** : `password123`

---

## 🌐 API REST - Endpoints Disponibles

### Base URL
```
http://localhost/Feminine%20Aura_last/api
```

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/auth/register` | Inscription utilisateur |
| POST | `/auth/login` | Connexion utilisateur |
| POST | `/auth/logout` | Déconnexion |
| GET | `/auth/me` | Récupérer utilisateur connecté |

#### Exemple : Inscription

```bash
curl -X POST "http://localhost/Feminine%20Aura_last/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{"email":"nouveau@example.com","password":"password123"}'
```

**Réponse :**
```json
{
  "success": true,
  "message": "Inscription réussie",
  "data": {
    "user": {
      "id_utilisatrice": 4,
      "email": "nouveau@example.com",
      "role": "client"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

### Produits

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/produits` | Liste tous les produits |
| GET | `/produits/{id}` | Récupère un produit |
| GET | `/produits?categorie={id}` | Produits par catégorie |
| POST | `/produits` | Crée un produit (admin) |
| PUT | `/produits/{id}` | Met à jour un produit (admin) |
| DELETE | `/produits/{id}` | Supprime un produit (admin) |

### Catégories

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/categories` | Liste toutes les catégories |
| GET | `/categories/{id}` | Récupère une catégorie |

### Panier (Authentification requise)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/panier` | Récupère le panier |
| POST | `/panier/add` | Ajoute un article |
| PUT | `/panier/update` | Met à jour la quantité |
| DELETE | `/panier/remove` | Supprime un article |
| DELETE | `/panier/clear` | Vide le panier |

### Favoris (Authentification requise)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/favoris` | Liste tous les favoris |
| POST | `/favoris` | Ajoute un favori |
| POST | `/favoris/toggle` | Toggle favori |
| DELETE | `/favoris` | Supprime un favori |

### Commandes (Authentification requise)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/commandes` | Liste toutes les commandes |
| GET | `/commandes/{id}` | Récupère une commande |
| POST | `/commandes` | Crée une commande |
| PUT | `/commandes/{id}` | Met à jour le statut (admin) |

---

## 🔐 Authentification

L'API utilise un système de token JWT simplifié (Bearer Token).

### Utilisation

Après connexion/inscription, l'API renvoie un token :

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

Pour les requêtes authentifiées, incluez le token dans le header :

```bash
curl -X GET "http://localhost/Feminine%20Aura_last/api/panier" \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

---

## 🎨 Fonctionnalités

### Pour les Visiteurs
- ✅ Navigation dans le catalogue de produits
- ✅ Filtrage par catégorie
- ✅ Recherche de produits
- ✅ Visualisation des détails produit

### Pour les Clients Connectés
- ✅ Inscription et connexion
- ✅ Gestion du panier
- ✅ Gestion des favoris
- ✅ Passage de commande
- ✅ Historique des commandes
- ✅ Gestion du profil

### Pour les Administrateurs
- ✅ Gestion des produits (CRUD)
- ✅ Gestion des catégories
- ✅ Gestion des commandes
- ✅ Statistiques (à venir)

---

## 🛠️ Scripts Disponibles

### Frontend (Next.js)

```bash
# Développement
npm run dev

# Build production
npm run build

# Lancer en production
npm start

# Linter
npm run lint
```

### Backend (PHP)

Les scripts utilitaires sont dans le dossier `api/` :

- `install-db.php` - Installation/réinstallation de la base de données
- `test-register.php` - Test du système d'inscription
- `test-inscription-simple.php` - Test simple d'inscription
- `diagnostic-inscription.php` - Diagnostic complet du système
- `debug-auth.php` - Debug authentification

Accès via navigateur :
```
http://localhost/Feminine%20Aura_last/api/install-db.php
http://localhost/Feminine%20Aura_last/api/diagnostic-inscription.php
```

---

## 🐛 Dépannage

### Problème : "Failed to fetch" lors de l'inscription

**Cause** : Problème CORS ou Apache non démarré

**Solution** :
1. Vérifiez que XAMPP/Apache est démarré
2. Videz le cache du navigateur : `Ctrl + Shift + R`
3. Vérifiez que le fichier `api/.env` existe avec `CORS_ALLOWED_ORIGINS=http://localhost:3000`

### Problème : "Email ou mot de passe incorrect"

**Cause** : Vous êtes en mode "Connexion" au lieu de "Inscription"

**Solution** :
- Cliquez sur le bouton **"Inscription"** à droite (pas "Connexion")
- Ou utilisez un des comptes de test existants

### Problème : La base de données n'existe pas

**Solution** :
```
http://localhost/Feminine%20Aura_last/api/install-db.php
```

### Problème : Erreur 404 sur l'API

**Cause** : Apache non démarré ou mauvaise URL

**Solution** :
1. Démarrez Apache dans XAMPP
2. Vérifiez l'URL : `http://localhost/Feminine%20Aura_last/api/`
3. L'espace dans "Feminine Aura_last" doit être encodé en `%20`

### Problème : Le frontend ne démarre pas

```bash
# Supprimer node_modules et réinstaller
rm -rf node_modules package-lock.json
npm install

# Relancer
npm run dev
```

---

## 📊 Base de Données

### Structure des Tables

- **utilisatrice** - Utilisateurs du site
- **categorie** - Catégories de produits
- **produit** - Produits en vente
- **panier** - Paniers des utilisateurs
- **ligne_panier** - Articles dans les paniers
- **commande** - Commandes passées
- **ligne_commande** - Détails des commandes
- **paiement** - Informations de paiement
- **favori** - Produits favoris
- **avis** - Avis sur les produits
- **admin_action** - Logs des actions admin
- **rapport** - Rapports statistiques

### Schéma de Base

```
utilisatrice
├── id_utilisatrice (PK)
├── email (UNIQUE)
├── mot_de_passe (hashed)
├── role (client|admin)
└── timestamps

produit
├── id_produit (PK)
├── id_categorie (FK → categorie)
├── nom
├── description
├── prix
├── stock
├── couleur
├── taille
├── tissu
├── image_url
└── timestamps

commande
├── id_commande (PK)
├── id_utilisatrice (FK → utilisatrice)
├── id_paiement (FK → paiement)
├── numero_commande (UNIQUE)
├── total_commande
├── statut_commande
├── adresse_livraison
└── timestamps
```

---

## 🚀 Déploiement

### Production

Pour déployer en production :

1. **Frontend** :
```bash
npm run build
npm start
```

2. **Backend** :
- Configurez un serveur Apache/PHP
- Importez la base de données
- Modifiez les variables d'environnement

3. **Sécurité** :
- Changez `JWT_SECRET` dans `api/.env`
- Désactivez `display_errors` en production
- Utilisez HTTPS
- Configurez les permissions des fichiers

---

## 📝 Notes Importantes

### Corrections Récentes

✅ **CORS** : Correction de l'ordre de chargement dans `api/index.php` - le fichier `.env` est maintenant chargé AVANT l'activation de CORS

✅ **Authentification** : Le système d'inscription/connexion est opérationnel

✅ **API** : Tous les endpoints fonctionnent correctement

### À Faire

- [ ] Implémenter une vraie gestion JWT (actuellement simplifié)
- [ ] Ajouter la gestion des images upload
- [ ] Implémenter le paiement réel (Stripe/PayPal)
- [ ] Ajouter un système de recherche avancée
- [ ] Créer un dashboard admin complet
- [ ] Implémenter l'envoi d'emails (confirmation commande, etc.)
- [ ] Ajouter des tests unitaires

---

## 📄 Licence

Projet personnel - Tous droits réservés

---

## 👨‍💻 Support

Pour toute question ou problème :

1. Consultez la section **Dépannage** ci-dessus
2. Vérifiez les fichiers de logs :
   - Frontend : Console navigateur (F12)
   - Backend : `C:\xampp\apache\logs\error.log`
3. Testez les scripts de diagnostic dans `api/`

---

## 📚 Ressources

- [Next.js Documentation](https://nextjs.org/docs)
- [React Documentation](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Shadcn/UI](https://ui.shadcn.com)
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

**Dernière mise à jour** : Décembre 2024
