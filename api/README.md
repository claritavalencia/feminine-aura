# API Feminine Aura - Documentation

Backend API REST en PHP pour la boutique de lingerie **Feminine Aura**.

## 🚀 Installation

### 1. Configuration de la base de données

```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données et importer le schéma
source api/database/schema.sql
source api/database/seed.sql
```

### 2. Configuration des variables d'environnement

Copier `.env.example` vers `.env` et configurer :

```bash
cp api/.env.example api/.env
```

Modifier les valeurs dans `.env` :
```env
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=votre_mot_de_passe
```

### 3. Configuration Apache

Assurer que `mod_rewrite` est activé :

```bash
# Sur Linux/Mac
sudo a2enmod rewrite
sudo service apache2 restart

# Sur Windows avec XAMPP
# mod_rewrite est généralement activé par défaut
```

### 4. Tester l'API

Accéder à : `http://localhost/api/`

Vous devriez voir la page d'accueil de l'API avec la liste des endpoints.

## 📋 Endpoints Disponibles

### Authentification

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| POST | `/api/auth/register` | Inscription utilisateur | Non |
| POST | `/api/auth/login` | Connexion | Non |
| POST | `/api/auth/logout` | Déconnexion | Oui |
| GET | `/api/auth/me` | Utilisateur connecté | Oui |

**Exemple de requête de connexion :**
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "client1@example.com", "password": "password123"}'
```

### Produits

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/produits` | Liste des produits | Non |
| GET | `/api/produits/{id}` | Détail d'un produit | Non |
| POST | `/api/produits` | Créer un produit | Admin |
| PUT | `/api/produits/{id}` | Modifier un produit | Admin |
| DELETE | `/api/produits/{id}` | Supprimer un produit | Admin |

**Filtres disponibles (GET /api/produits) :**
- `categorie` : ID de catégorie
- `couleur` : Couleur du produit
- `taille` : Taille (XS, S, M, L, XL)
- `tissu` : Type de tissu
- `prix_min` : Prix minimum
- `prix_max` : Prix maximum
- `recherche` : Recherche dans nom/description
- `order_by` : Colonne de tri (prix, nom, date_creation)
- `order_dir` : Direction (ASC/DESC)
- `page` : Numéro de page
- `limit` : Nombre de résultats par page

**Exemple :**
```bash
curl "http://localhost/api/produits?categorie=1&prix_max=80&page=1&limit=10"
```

### Catégories

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/categories` | Liste des catégories | Non |
| GET | `/api/categories/{id}` | Détail d'une catégorie | Non |
| POST | `/api/categories` | Créer une catégorie | Admin |
| PUT | `/api/categories/{id}` | Modifier une catégorie | Admin |
| DELETE | `/api/categories/{id}` | Supprimer une catégorie | Admin |

### Panier

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/panier` | Récupérer le panier | Oui |
| POST | `/api/panier/add` | Ajouter au panier | Oui |
| PUT | `/api/panier/update` | Mettre à jour quantité | Oui |
| DELETE | `/api/panier/remove` | Retirer un article | Oui |
| DELETE | `/api/panier/clear` | Vider le panier | Oui |

**Exemple d'ajout au panier :**
```bash
curl -X POST http://localhost/api/panier/add \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -d '{"id_produit": 1, "quantite": 2}'
```

### Favoris

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/favoris` | Liste des favoris | Oui |
| POST | `/api/favoris` | Ajouter aux favoris | Oui |
| POST | `/api/favoris/toggle` | Toggle favori | Oui |
| DELETE | `/api/favoris` | Retirer des favoris | Oui |

### Commandes

| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/commandes` | Liste des commandes | Oui |
| GET | `/api/commandes/{id}` | Détail d'une commande | Oui |
| POST | `/api/commandes` | Créer une commande | Oui |
| PUT | `/api/commandes/{id}` | Mettre à jour statut | Admin |

**Exemple de création de commande :**
```bash
curl -X POST http://localhost/api/commandes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -d '{
    "adresse_livraison": "15 Rue de la Paix",
    "ville": "Paris",
    "code_postal": "75001",
    "telephone": "+33612345678"
  }'
```

## 🔒 Authentification

L'API utilise des tokens Bearer pour l'authentification.

1. **Connexion** : `POST /api/auth/login`
   - Retourne un token dans la réponse

2. **Utiliser le token** : Inclure dans les en-têtes
   ```
   Authorization: Bearer VOTRE_TOKEN
   ```

3. **Token expiré** : Se reconnecter pour obtenir un nouveau token

## 📊 Format des Réponses

Toutes les réponses sont au format JSON :

**Succès :**
```json
{
  "success": true,
  "message": "Message de succès",
  "data": { ... }
}
```

**Erreur :**
```json
{
  "success": false,
  "message": "Message d'erreur",
  "errors": { ... }
}
```

## 🔧 Codes HTTP

- `200` : OK
- `201` : Créé
- `400` : Mauvaise requête
- `401` : Non authentifié
- `403` : Accès interdit
- `404` : Non trouvé
- `405` : Méthode non autorisée
- `422` : Erreur de validation
- `500` : Erreur serveur

## 📁 Structure du Projet

```
api/
├── config/
│   ├── database.php      # Configuration DB
│   └── cors.php          # Configuration CORS
├── controllers/
│   ├── BaseController.php
│   ├── AuthController.php
│   ├── ProduitController.php
│   ├── CategorieController.php
│   ├── PanierController.php
│   ├── FavoriController.php
│   └── CommandeController.php
├── models/
│   ├── BaseModel.php
│   ├── Utilisatrice.php
│   ├── Produit.php
│   ├── Categorie.php
│   ├── Commande.php
│   ├── Panier.php
│   ├── Favori.php
│   └── Avis.php
├── utils/
│   └── Response.php      # Réponses JSON standardisées
├── database/
│   ├── schema.sql        # Schéma de la base
│   └── seed.sql          # Données de test
├── .env.example          # Variables d'environnement
├── .htaccess             # Réécriture d'URL
├── index.php             # Point d'entrée
└── README.md             # Documentation

## 🧪 Tests

### Utilisateurs de test

**Admin :**
- Email: `admin@feminineaura.com`
- Password: `password123`

**Client :**
- Email: `client1@example.com`
- Password: `password123`

### Collection Postman

Importer la collection Postman pour tester facilement tous les endpoints (à créer).

## 🛠️ Développement

### Mode debug

Dans `.env`, activer le mode debug :
```env
APP_ENV=development
APP_DEBUG=true
```

### Logs

Les erreurs sont loguées dans les logs PHP de votre serveur web.

## 📝 TODO

- [ ] Implémenter JWT authentification (actuellement simplifié)
- [ ] Ajouter système d'upload d'images
- [ ] Implémenter pagination avancée
- [ ] Ajouter rate limiting
- [ ] Tests unitaires
- [ ] Documentation OpenAPI/Swagger

## 📧 Support

Pour toute question : contact@feminineaura.com
```
