# 🚀 Guide d'Installation - API Feminine Aura

Guide pas-à-pas pour installer et configurer l'API Backend.

## Prérequis

- ✅ **XAMPP** (ou WAMP/MAMP) avec :
  - PHP 7.4 ou supérieur
  - MySQL 5.7 ou supérieur
  - Apache avec mod_rewrite activé
- ✅ **Composer** (optionnel, pour dépendances futures)

## 📝 Étape 1 : Configuration de la Base de Données

### 1.1 Démarrer MySQL

Démarrer MySQL depuis le panneau de contrôle XAMPP.

### 1.2 Créer la base de données

Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`) ou utiliser le terminal MySQL :

```bash
# Ouvrir MySQL en ligne de commande
mysql -u root -p
```

Puis exécuter :

```sql
CREATE DATABASE feminine_aura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE feminine_aura;
```

### 1.3 Importer le schéma

**Option A : Via phpMyAdmin**
1. Ouvrir phpMyAdmin
2. Sélectionner la base `feminine_aura`
3. Onglet "Importer"
4. Choisir `api/database/schema.sql`
5. Cliquer sur "Exécuter"
6. Répéter avec `api/database/seed.sql`

**Option B : Via ligne de commande**
```bash
cd c:\xampp\htdocs\Feminine Aura_last\api\database
mysql -u root -p feminine_aura < schema.sql
mysql -u root -p feminine_aura < seed.sql
```

### 1.4 Vérifier l'import

```sql
USE feminine_aura;
SHOW TABLES;
SELECT COUNT(*) FROM produit;
```

Vous devriez voir 13 tables et au moins 14 produits.

## 📝 Étape 2 : Configuration des Variables d'Environnement

### 2.1 Copier le fichier .env

```bash
cd c:\xampp\htdocs\Feminine Aura_last\api
copy .env.example .env
```

### 2.2 Modifier le fichier .env

Ouvrir `.env` et configurer selon votre installation :

```env
# Configuration de la base de données
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=           # Laisser vide si pas de mot de passe (XAMPP par défaut)
DB_CHARSET=utf8mb4

# Configuration de l'application
APP_ENV=development    # development ou production
APP_DEBUG=true         # true en dev, false en prod

# URL de votre frontend Next.js
APP_URL=http://localhost:3000

# Configuration CORS (ajouter vos URLs frontend)
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
```

## 📝 Étape 3 : Configuration Apache

### 3.1 Vérifier mod_rewrite

**Sur Windows/XAMPP :**

1. Ouvrir `C:\xampp\apache\conf\httpd.conf`
2. Chercher la ligne :
   ```apache
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Retirer le `#` si présent :
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Redémarrer Apache depuis XAMPP

### 3.2 Permettre .htaccess

Dans le même fichier `httpd.conf`, chercher :

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride None
```

Changer `None` en `All` :

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
```

### 3.3 Redémarrer Apache

Arrêter et redémarrer Apache depuis le panneau XAMPP.

## 📝 Étape 4 : Tester l'Installation

### 4.1 Tester la page d'accueil de l'API

Ouvrir dans le navigateur :
```
http://localhost/api/
```

Vous devriez voir une réponse JSON avec :
```json
{
  "success": true,
  "message": "Bienvenue sur l'API Feminine Aura",
  "data": {
    "name": "Feminine Aura API",
    "version": "1.0.0",
    ...
  }
}
```

### 4.2 Tester les produits

```
http://localhost/api/produits
```

Devrait retourner la liste des produits.

### 4.3 Tester l'authentification

**Via navigateur ou Postman :**

```
POST http://localhost/api/auth/login
Content-Type: application/json

{
  "email": "client1@example.com",
  "password": "password123"
}
```

Devrait retourner un token et les informations utilisateur.

## 📝 Étape 5 : Connexion avec Next.js

### 5.1 Créer un fichier de configuration API

Dans votre projet Next.js, créer `lib/api.ts` :

```typescript
const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost/api';

export const api = {
  // Authentification
  async login(email: string, password: string) {
    const res = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    return res.json();
  },

  // Produits
  async getProduits(filters = {}) {
    const params = new URLSearchParams(filters);
    const res = await fetch(`${API_URL}/produits?${params}`);
    return res.json();
  },

  async getProduit(id: number) {
    const res = await fetch(`${API_URL}/produits/${id}`);
    return res.json();
  },

  // Panier
  async getPanier(token: string) {
    const res = await fetch(`${API_URL}/panier`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    return res.json();
  },

  async addToPanier(token: string, produitId: number, quantite: number) {
    const res = await fetch(`${API_URL}/panier/add`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ id_produit: produitId, quantite })
    });
    return res.json();
  }
};
```

### 5.2 Ajouter la variable d'environnement

Dans `.env.local` de Next.js :

```env
NEXT_PUBLIC_API_URL=http://localhost/api
```

## 🔍 Dépannage

### Erreur "404 Not Found"

- ✅ Vérifier que mod_rewrite est activé
- ✅ Vérifier que `.htaccess` existe dans `/api/`
- ✅ Vérifier `AllowOverride All` dans httpd.conf
- ✅ Redémarrer Apache

### Erreur de connexion à la base de données

- ✅ Vérifier que MySQL est démarré
- ✅ Vérifier les credentials dans `.env`
- ✅ Vérifier que la base `feminine_aura` existe
- ✅ Tester la connexion : `mysql -u root -p`

### Erreur CORS

- ✅ Vérifier les origines autorisées dans `.env`
- ✅ Vérifier que le frontend utilise la bonne URL
- ✅ Vérifier les en-têtes CORS dans `.htaccess`

### Page blanche / Erreur 500

- ✅ Activer l'affichage des erreurs dans `php.ini` :
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```
- ✅ Vérifier les logs Apache : `C:\xampp\apache\logs\error.log`
- ✅ Vérifier que tous les fichiers PHP sont corrects

## 📊 Comptes de Test

Utilisez ces comptes pour tester :

**Administrateur :**
- Email : `admin@feminineaura.com`
- Mot de passe : `password123`

**Client 1 :**
- Email : `client1@example.com`
- Mot de passe : `password123`

**Client 2 :**
- Email : `client2@example.com`
- Mot de passe : `password123`

## ✅ Checklist de Vérification

- [ ] MySQL démarré
- [ ] Base de données `feminine_aura` créée
- [ ] Schéma et données importés
- [ ] Fichier `.env` configuré
- [ ] mod_rewrite activé
- [ ] AllowOverride configuré
- [ ] Apache redémarré
- [ ] Page d'accueil API accessible
- [ ] Endpoints testés
- [ ] CORS configuré pour Next.js

## 🎉 Prochaines Étapes

Une fois l'installation terminée :

1. Tester tous les endpoints avec Postman
2. Intégrer l'API dans le frontend Next.js
3. Implémenter JWT (optionnel mais recommandé)
4. Configurer l'upload d'images
5. Mettre en place le système de paiement

## 📞 Besoin d'Aide ?

Si vous rencontrez des problèmes :
1. Vérifier les logs PHP et Apache
2. Activer le mode debug dans `.env`
3. Consulter la documentation dans `README.md`
```
