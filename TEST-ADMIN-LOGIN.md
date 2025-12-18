# 🧪 Guide de Test : Connexion Admin

## 🎯 Objectif

Tester la connexion au dashboard administrateur après les corrections CORS.

---

## ⚙️ Pré-requis

### 1. Serveurs démarrés

#### XAMPP
- [ ] Ouvrir **XAMPP Control Panel**
- [ ] **Apache** : Cliquer sur "Start" (doit être vert)
- [ ] **MySQL** : Cliquer sur "Start" (doit être vert)

#### Next.js
```bash
# Dans le terminal du projet
cd "c:\xampp\htdocs\Feminine Aura_last"
npm run dev
```

Attendez voir : `✓ Ready on http://localhost:3000`

### 2. Variables d'environnement

Vérifier que le fichier `.env.local` contient :
```env
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api
NODE_ENV=development
```

---

## 🔍 Étapes de Test

### Étape 1 : Ouvrir la page de connexion admin

1. Ouvrir le navigateur (Chrome, Firefox, ou Edge)
2. Aller sur : `http://localhost:3000/admin`
3. La page de connexion admin doit s'afficher avec :
   - Logo avec icône shield
   - Fond dégradé noir/gris
   - Formulaire avec 2 champs (Email, Mot de passe)
   - Bouton "Se connecter" rose

### Étape 2 : Ouvrir la console du navigateur

**Appuyer sur F12** (ou clic droit → Inspecter)

Aller dans l'onglet **Console**

### Étape 3 : Entrer les identifiants

Dans le formulaire :
- **Email** : `admin@feminineaura.com`
- **Mot de passe** : `password123`

### Étape 4 : Cliquer sur "Se connecter"

Observez la console du navigateur.

---

## ✅ Résultats Attendus

### Logs dans la console (si succès)

Vous devriez voir apparaître dans l'ordre :

```
🔐 Tentative de connexion admin...
{
  email: "admin@feminineaura.com",
  API_URL: "http://localhost/Feminine Aura_last/api"
}

📡 Réponse API reçue:
{
  status: 200,
  statusText: "OK",
  headers: { ... }
}

✅ Données JSON reçues:
{
  success: true,
  message: "Connexion réussie",
  data: { user: {...}, token: "..." }
}

✅ Connexion admin réussie:
{
  id: "1",
  email: "admin@feminineaura.com"
}
```

### Comportement attendu

1. ✅ Pas d'erreur dans la console
2. ✅ **Redirection automatique** vers `/admin/dashboard`
3. ✅ Dashboard s'affiche avec :
   - Header avec email admin et bouton déconnexion
   - 4 cartes de statistiques (Revenus, Commandes, Produits, Clients)
   - Onglets (Produits, Commandes, Catégories)
   - Liste des produits avec bouton "Ajouter un produit"

---

## ❌ Erreurs Possibles et Solutions

### Erreur 1 : "Impossible de contacter le serveur"

**Message console** :
```
❌ Impossible de contacter l'API
Vérifications nécessaires:
1. XAMPP est démarré (Apache + MySQL)
2. API accessible sur: http://localhost/Feminine Aura_last/api
3. Pas de blocage CORS
```

**Solutions** :
1. Vérifier que XAMPP Apache est bien démarré (vert)
2. Tester l'API directement : `http://localhost/Feminine%20Aura_last/api`
3. Vérifier les logs Apache : `xampp/apache/logs/error.log`

### Erreur 2 : "Timeout: Le serveur ne répond pas"

**Message console** :
```
❌ Timeout: L'API ne répond pas (>10s)
```

**Solutions** :
1. Redémarrer Apache dans XAMPP
2. Vérifier que le port 80 n'est pas bloqué par un firewall
3. Tester avec curl :
```bash
curl http://localhost/Feminine%20Aura_last/api
```

### Erreur 3 : "Identifiants incorrects"

**Message console** :
```
❌ Connexion refusée: Email ou mot de passe incorrect
```

**Solutions** :
1. Vérifier l'email : `admin@feminineaura.com` (pas d'espace, pas de faute)
2. Vérifier le mot de passe : `password123` (tout en minuscules)
3. Réinitialiser le mot de passe admin :
```
http://localhost/Feminine%20Aura_last/api/fix-admin-password.php
```

### Erreur 4 : "Accès non autorisé"

**Message console** :
```
❌ Rôle non autorisé: client
```

**Solution** :
Le compte utilisé n'est pas un admin. Utilisez le compte `admin@feminineaura.com`.

### Erreur 5 : Erreur CORS (rouge dans la console)

**Message navigateur** :
```
Access to fetch at 'http://localhost/...' from origin 'http://localhost:3000'
has been blocked by CORS policy
```

**Solutions** :
1. Vérifier que `.htaccess` ne contient PAS de headers CORS
2. Vérifier que `api/.env` contient `APP_ENV=development`
3. Redémarrer Apache
4. Vider le cache du navigateur (Ctrl+Shift+R)

---

## 🧪 Tests Supplémentaires

### Test 1 : Vérifier l'API directement

Ouvrir dans le navigateur :
```
http://localhost/Feminine%20Aura_last/api
```

Devrait afficher :
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

### Test 2 : Diagnostic CORS complet

Ouvrir dans le navigateur :
```
http://localhost/Feminine%20Aura_last/api/test-cors.php
```

1. Cliquer sur "🔐 Tester Login Admin"
2. Observer le résultat :
   - ✅ Si vert : La connexion fonctionne
   - ❌ Si rouge : Voir le message d'erreur détaillé

### Test 3 : Tester avec curl

```bash
curl -X POST "http://localhost/Feminine%20Aura_last/api/auth/login" \
  -H "Content-Type: application/json" \
  -H "Origin: http://localhost:3000" \
  -d "{\"email\":\"admin@feminineaura.com\",\"mot_de_passe\":\"password123\"}"
```

**Résultat attendu** :
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id_utilisatrice": 1,
      "email": "admin@feminineaura.com",
      "role": "admin",
      ...
    },
    "token": "eyJpZCI6MSwiZW1haWw..."
  }
}
```

---

## 📊 Checklist Complète

### Avant de commencer

- [ ] XAMPP Apache démarré (vert)
- [ ] XAMPP MySQL démarré (vert)
- [ ] Next.js dev server lancé (`npm run dev`)
- [ ] `.env.local` existe et contient `NEXT_PUBLIC_API_URL`
- [ ] Navigateur ouvert avec console DevTools (F12)

### Pendant le test

- [ ] Page `/admin` s'affiche correctement
- [ ] Formulaire de connexion visible
- [ ] Identifiants entrés : `admin@feminineaura.com` / `password123`
- [ ] Bouton "Se connecter" cliqué

### Après connexion

- [ ] Pas d'erreur rouge dans la console
- [ ] Logs verts `✅` dans la console
- [ ] Redirection vers `/admin/dashboard`
- [ ] Dashboard s'affiche avec toutes les sections
- [ ] Statistiques visibles (4 cartes)
- [ ] Liste des produits chargée
- [ ] Boutons fonctionnels (Ajouter, Modifier, Supprimer)

---

## 🎯 Fonctionnalités à Tester sur le Dashboard

Une fois connecté, testez :

### 1. Onglet Produits
- [ ] La liste des produits s'affiche
- [ ] Cliquer sur "Ajouter un produit" ouvre le modal
- [ ] Remplir le formulaire et créer un produit
- [ ] Cliquer sur "Modifier" d'un produit (icône crayon)
- [ ] Modifier le produit et sauvegarder
- [ ] Cliquer sur "Supprimer" (icône poubelle)
- [ ] Confirmer la suppression

### 2. Onglet Commandes
- [ ] La liste des commandes s'affiche (données de test)
- [ ] Les badges de statut sont colorés correctement

### 3. Onglet Catégories
- [ ] Les catégories s'affichent en grille
- [ ] Chaque carte montre nom + description + slug

### 4. Déconnexion
- [ ] Cliquer sur "Se déconnecter" en haut à droite
- [ ] Redirection vers `/admin`
- [ ] Plus possible d'accéder au dashboard sans reconnecter

---

## 📸 Captures d'écran Attendues

### 1. Page de connexion (`/admin`)
![Admin Login](Expected: Dark gradient background, shield icon, email/password fields, pink button)

### 2. Dashboard (`/admin/dashboard`)
![Admin Dashboard](Expected: Header with email + logout, 4 stat cards, tabs, product list)

### 3. Console du navigateur (succès)
![Console Success](Expected: Green checkmarks with connection details)

---

## 🆘 Si Rien ne Fonctionne

### 1. Reset complet

```bash
# Arrêter tout
# - XAMPP : Stop Apache + MySQL
# - Next.js : Ctrl+C dans le terminal

# Nettoyer le cache
# - Navigateur : Ctrl+Shift+Delete → Vider le cache
# - localStorage : Console → localStorage.clear()

# Redémarrer dans l'ordre
# 1. XAMPP : Start Apache
# 2. XAMPP : Start MySQL
# 3. Next.js : npm run dev
# 4. Navigateur : F5 sur http://localhost:3000/admin
```

### 2. Vérifier les logs

#### Logs Apache
```
c:\xampp\apache\logs\error.log
```

#### Logs PHP
Ajouter temporairement dans `api/index.php` (ligne 20) :
```php
error_log("=== REQUÊTE API ===");
error_log("Method: " . $_SERVER['REQUEST_METHOD']);
error_log("URI: " . $_SERVER['REQUEST_URI']);
error_log("Origin: " . ($_SERVER['HTTP_ORIGIN'] ?? 'none'));
```

Puis consulter : `c:\xampp\apache\logs\error.log`

### 3. Réinitialiser la base de données

Si le compte admin est corrompu :
```
http://localhost/Feminine%20Aura_last/api/install-db.php
```

⚠️ **ATTENTION** : Cela supprime toutes les données !

---

## ✅ Test Réussi !

Si vous voyez :
- ✅ Redirection vers `/admin/dashboard`
- ✅ Email admin affiché en haut à droite
- ✅ 4 cartes de statistiques
- ✅ Liste des produits

**FÉLICITATIONS ! La connexion admin fonctionne parfaitement !** 🎉

Vous pouvez maintenant :
- Gérer les produits (CRUD complet)
- Visualiser les commandes
- Consulter les catégories
- Vous déconnecter et reconnecter

---

**Fichier créé le** : 2024-12-14
**Version** : 1.0
**Statut** : Prêt pour test
