# 🔧 Correction du Problème CORS - Admin Login

## 🎯 Problème Identifié

**Erreur**: `Failed to fetch` lors de la tentative de connexion admin depuis le navigateur

**Cause**: Configuration CORS trop restrictive qui bloquait les requêtes depuis `localhost:3000`

---

## ✅ Solutions Appliquées

### 1. **Amélioration de la Configuration CORS** (`api/config/cors.php`)

#### Modification apportée:
- Ajout d'une gestion spéciale pour l'environnement de développement
- Autorisation automatique de toutes les origines `localhost` en mode développement
- Meilleure gestion des origines vides

```php
// En mode développement, autoriser toutes les origines localhost
$isDevelopment = ($_ENV['APP_ENV'] ?? 'production') === 'development';

if ($isDevelopment && (empty($origin) || strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false)) {
    // En développement, accepter toutes les requêtes localhost
    header("Access-Control-Allow-Origin: " . ($origin ?: 'http://localhost:3000'));
}
```

**Ligne modifiée**: [cors.php:20-22](api/config/cors.php#L20-L22)

---

### 2. **Correction du Fetch dans AdminContext** (`app/context/admin-context.tsx`)

#### Modifications apportées:
- Ajout de `mode: 'cors'` pour activer explicitement CORS
- Ajout de `credentials: 'include'` pour envoyer les cookies (requis quand `Access-Control-Allow-Credentials: true`)
- Ajout du header `Accept: 'application/json'`
- Ajout d'une vérification `response.ok` avant de parser le JSON

```typescript
const response = await fetch(
  `${process.env.NEXT_PUBLIC_API_URL}/auth/login`,
  {
    method: 'POST',
    mode: 'cors',                    // ✅ Nouveau
    credentials: 'include',          // ✅ Nouveau
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',  // ✅ Nouveau
    },
    body: JSON.stringify({
      email,
      mot_de_passe: password,
    }),
  }
);

if (!response.ok) {                  // ✅ Nouveau
  throw new Error(`HTTP error! status: ${response.status}`);
}
```

**Ligne modifiée**: [admin-context.tsx:45-65](app/context/admin-context.tsx#L45-L65)

---

## 🧪 Script de Diagnostic Créé

### **test-cors.php** - Outil de diagnostic CORS

**Emplacement**: `api/test-cors.php`

**Fonctionnalités**:
- ✅ Affiche les headers CORS actuellement envoyés
- ✅ Affiche les informations de la requête (Origin, Method, etc.)
- ✅ Affiche les variables d'environnement (.env)
- ✅ **3 boutons de test en direct**:
  1. 🔐 **Tester Login Admin** - Test de connexion complète
  2. 🔍 **Tester Requête OPTIONS** - Test du preflight CORS
  3. 📦 **Tester GET /produits** - Test d'une requête GET simple

**Utilisation**:
```bash
# Ouvrir dans le navigateur
http://localhost/Feminine%20Aura_last/api/test-cors.php

# Cliquer sur les boutons pour tester
# Observer la console du navigateur (F12 → Console)
# Vérifier l'onglet Network pour les headers CORS
```

---

## 📋 Checklist de Vérification

### Avant de tester:

- [x] Fichier `.env` contient `APP_ENV=development`
- [x] Fichier `.env` contient `CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001`
- [x] Le serveur XAMPP est démarré (Apache + MySQL)
- [x] Next.js dev server est démarré (`npm run dev`)

### Tests à effectuer:

1. **Test avec le script de diagnostic**:
   - [ ] Ouvrir `http://localhost/Feminine%20Aura_last/api/test-cors.php`
   - [ ] Cliquer sur "🔐 Tester Login Admin"
   - [ ] Vérifier que le résultat est ✅ Succès (pas ❌ Erreur)
   - [ ] Vérifier dans la console qu'il n'y a pas d'erreur CORS

2. **Test depuis la page admin**:
   - [ ] Ouvrir `http://localhost:3000/admin`
   - [ ] Entrer les identifiants:
     - Email: `admin@feminineaura.com`
     - Mot de passe: `password123`
   - [ ] Cliquer sur "Se connecter"
   - [ ] Vérifier la redirection vers `/admin/dashboard`

3. **Test du dashboard**:
   - [ ] Vérifier l'affichage des statistiques
   - [ ] Vérifier l'affichage de la liste des produits
   - [ ] Tester l'ajout d'un produit
   - [ ] Tester la modification d'un produit
   - [ ] Tester la suppression d'un produit

---

## 🔍 Debugging

### Si le problème persiste:

#### 1. Vérifier les Headers CORS dans la console

Ouvrir la console (F12) → Onglet **Network** → Cliquer sur la requête `login` → Vérifier:

**Response Headers** (devrait contenir):
```
Access-Control-Allow-Origin: http://localhost:3000
Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS
Access-Control-Allow-Headers: Content-Type,Authorization,X-Requested-With
Access-Control-Allow-Credentials: true
```

**Request Headers** (devrait contenir):
```
Origin: http://localhost:3000
Content-Type: application/json
```

#### 2. Vérifier le fichier .env

```bash
# Dans le dossier api/
cat .env

# Doit contenir:
APP_ENV=development
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
```

#### 3. Vérifier que le .env est bien chargé

```php
# Ajouter temporairement dans api/index.php après le require de database.php
var_dump($_ENV['APP_ENV']);
var_dump($_ENV['CORS_ALLOWED_ORIGINS']);
exit;
```

#### 4. Tester avec curl (devrait fonctionner)

```bash
curl -X POST "http://localhost/Feminine%20Aura_last/api/auth/login" \
  -H "Content-Type: application/json" \
  -H "Origin: http://localhost:3000" \
  -d '{"email":"admin@feminineaura.com","mot_de_passe":"password123"}' \
  -v
```

Vérifier que la réponse contient les headers CORS.

---

## 🎨 Pourquoi ces modifications ?

### 1. Mode développement permissif

En développement, on veut tester rapidement sans être bloqué par CORS. La modification permet d'autoriser automatiquement toutes les requêtes `localhost` quand `APP_ENV=development`.

**En production**, cette permission ne s'appliquera PAS, et seules les origines listées dans `CORS_ALLOWED_ORIGINS` seront autorisées.

### 2. Credentials: include

Quand le serveur envoie `Access-Control-Allow-Credentials: true`, le navigateur **exige** que le client envoie `credentials: 'include'` dans le fetch. Sinon, le navigateur bloque la requête.

C'est nécessaire pour:
- Envoyer les cookies
- Permettre l'authentification basée sur les sessions
- Respecter les règles CORS strictes

### 3. Mode: 'cors'

Indique explicitement au navigateur d'utiliser le mode CORS. Même si c'est souvent la valeur par défaut pour les requêtes cross-origin, l'expliciter évite les ambiguïtés.

---

## 🚀 Prochaines Étapes

Après avoir vérifié que la connexion admin fonctionne:

1. **Tester toutes les fonctionnalités du dashboard**:
   - CRUD des produits
   - Visualisation des commandes
   - Gestion des catégories

2. **Améliorer la sécurité** (pour production):
   - Implémenter de vrais tokens JWT (pas juste base64)
   - Ajouter une expiration des tokens
   - Mettre en place le refresh token
   - Changer `CORS_ALLOWED_ORIGINS` pour n'autoriser que le domaine de production

3. **Ajouter les endpoints manquants**:
   - `/api/avis` pour les avis clients
   - `/api/commandes` pour les commandes réelles
   - Upload d'images (pas juste URL)

---

## 📚 Ressources

- [MDN - CORS](https://developer.mozilla.org/fr/docs/Web/HTTP/CORS)
- [MDN - Fetch API](https://developer.mozilla.org/fr/docs/Web/API/Fetch_API)
- [PHP - header()](https://www.php.net/manual/fr/function.header.php)

---

**Date de correction**: 2024-12-14
**Fichiers modifiés**:
- `api/config/cors.php` (ligne 20-22)
- `app/context/admin-context.tsx` (ligne 45-65)

**Fichiers créés**:
- `api/test-cors.php` (outil de diagnostic)
- `CORRECTION-CORS-ADMIN.md` (ce document)
