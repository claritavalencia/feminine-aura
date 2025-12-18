# 📦 Guide d'Installation - Feminine Aura

Guide complet pour installer et tester le projet en local.

---

## ⚙️ Prérequis

Assurez-vous d'avoir installé :

- **Node.js 18+** → [Télécharger](https://nodejs.org/)
- **XAMPP 8.1+** (Apache + MySQL + PHP) → [Télécharger](https://www.apachefriends.org/)
- **Git** (pour cloner le projet) → [Télécharger](https://git-scm.com/)
- **Navigateur moderne** (Chrome, Firefox, Edge recommandés)

---

## 🚀 Installation Étape par Étape

### 1️⃣ Cloner le Projet

```bash
# Option A : Via HTTPS
git clone https://github.com/[VOTRE-USERNAME]/feminine-aura.git

# Option B : Via SSH (si configuré)
git clone git@github.com:[VOTRE-USERNAME]/feminine-aura.git

# Se placer dans le dossier
cd feminine-aura
```

**OU**

Télécharger le ZIP depuis GitHub et extraire dans `c:\xampp\htdocs\`

---

### 2️⃣ Installer les Dépendances Frontend

```bash
# À la racine du projet
npm install
```

Cela va installer (~200 packages) :
- Next.js 16
- React 19
- TypeScript
- Tailwind CSS
- Et toutes les dépendances

⏱️ **Temps estimé** : 2-5 minutes selon votre connexion

---

### 3️⃣ Configurer la Base de Données

#### A. Démarrer XAMPP

1. Ouvrir **XAMPP Control Panel**
2. Cliquer sur **Start** pour :
   - **Apache** (port 80)
   - **MySQL** (port 3306)
3. Vérifier que les voyants deviennent **verts**

#### B. Créer la Base de Données

**Option 1 : Script Automatique** (recommandé)

Ouvrir dans le navigateur :
```
http://localhost/[NOM-DU-DOSSIER]/api/database/install-db.php
```

Le script va :
- Créer la base `feminine_aura`
- Créer les 13 tables
- Insérer les données de démonstration (14 produits, 4 catégories, comptes de test)

**Option 2 : Manuelle (phpMyAdmin)**

1. Ouvrir **phpMyAdmin** : `http://localhost/phpmyadmin`
2. Cliquer sur **Nouvelle base de données**
3. Nom : `feminine_aura`
4. Interclassement : `utf8mb4_unicode_ci`
5. Cliquer **Créer**
6. Onglet **Importer**
7. Importer dans cet ordre :
   - `api/database/schema.sql` (structure)
   - `api/database/seed.sql` (données)

---

### 4️⃣ Configurer les Variables d'Environnement

#### A. Backend (API PHP)

```bash
# Dans le dossier api/
cp .env.example .env
```

Éditer `api/.env` :

```env
DB_HOST=localhost
DB_NAME=feminine_aura
DB_USER=root
DB_PASSWORD=              # Laisser vide par défaut (XAMPP)
DB_CHARSET=utf8mb4

APP_ENV=development
APP_DEBUG=true

CORS_ALLOWED_ORIGINS=http://localhost:3000
```

#### B. Frontend (Next.js)

```bash
# À la racine du projet
cp .env.example .env.local
```

Éditer `.env.local` :

```env
# IMPORTANT : Adapter le chemin selon votre installation
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api

NODE_ENV=development
```

**⚠️ Attention** : Remplacez `Feminine%20Aura_last` par le nom exact de votre dossier dans `htdocs`.

---

### 5️⃣ Vérifier l'Installation

#### Tester l'API Backend

```bash
curl http://localhost/[NOM-DOSSIER]/api
```

**Réponse attendue** :
```json
{
  "success": true,
  "message": "API Feminine Aura - Bienvenue",
  "version": "1.0.0"
}
```

#### Tester les Produits

```bash
curl http://localhost/[NOM-DOSSIER]/api/produits
```

**Réponse attendue** : JSON avec 14 produits

---

### 6️⃣ Lancer l'Application

```bash
# À la racine du projet
npm run dev
```

**Réponse attendue** :
```
✓ Ready in 2.5s
○ Local: http://localhost:3000
```

Ouvrir dans le navigateur : **http://localhost:3000**

Vous devriez voir :
- ✅ La page d'accueil avec 14 produits en grille
- ✅ Header avec menu
- ✅ Footer
- ✅ Aucune erreur dans la console (F12)

---

## 🧪 Tests Rapides

### 1. Vérifier que tout fonctionne

**Console navigateur (F12)** :
```javascript
// Vous devriez voir ces logs :
🔵 API Request: http://localhost/.../api/produits
🟢 API Response status: 200
📦 API Data: {success: true, ...}
```

### 2. Tester un Compte Admin

```
URL : http://localhost:3000/admin
Email : admin@feminineaura.com
Mot de passe : password123
```

**Vous devriez** :
- ✅ Vous connecter sans erreur
- ✅ Voir le dashboard admin
- ✅ Voir 4 cartes statistiques
- ✅ Voir les onglets Produits/Commandes/Catégories

### 3. Tester un Compte Client

```
URL : http://localhost:3000/auth/login
Email : marie.dupont@example.com
Mot de passe : password123
```

**Vous devriez** :
- ✅ Vous connecter
- ✅ Voir votre email dans le header
- ✅ Pouvoir ajouter au panier/favoris

---

## 🐛 Problèmes Courants

### Problème : "Failed to fetch"

**Cause** : CORS mal configuré ou API inaccessible

**Solutions** :
1. Vérifier que Apache est démarré dans XAMPP
2. Tester l'API directement : `curl http://localhost/[DOSSIER]/api`
3. Vérifier `CORS_ALLOWED_ORIGINS` dans `api/.env`
4. Vider le cache : Ctrl+Shift+R

### Problème : "NEXT_PUBLIC_API_URL is not defined"

**Cause** : Fichier `.env.local` manquant ou mal configuré

**Solution** :
1. Vérifier que `.env.local` existe à la racine
2. Vérifier qu'il contient `NEXT_PUBLIC_API_URL=...`
3. Redémarrer Next.js : Ctrl+C puis `npm run dev`

### Problème : "Database connection failed"

**Cause** : MySQL non démarré ou mauvaise config

**Solutions** :
1. Vérifier MySQL démarré dans XAMPP
2. Vérifier `api/.env` :
   - `DB_HOST=localhost`
   - `DB_NAME=feminine_aura`
   - `DB_USER=root`
   - `DB_PASSWORD=` (vide)

### Problème : Aucun produit ne s'affiche

**Diagnostic** :
```bash
# 1. Tester API
curl http://localhost/[DOSSIER]/api/produits

# 2. Vérifier DB
# PhpMyAdmin → feminine_aura → produit
# Doit contenir 14 lignes
```

**Solution** :
- Si API retourne vide → Relancer `install-db.php`
- Si API retourne erreur → Vérifier logs Apache (XAMPP)

---

## 📚 Ressources Utiles

### Documentation Projet

- **README principal** : [README.md](README.md)
- **Rapport académique** : [README-RAPPORT.md](README-RAPPORT.md)
- **Documentation BDD** :
  - [MCD](docs/database/MCD.md)
  - [MLD](docs/database/MLD.md)
  - [Dictionnaire](docs/database/DICTIONNAIRE-DONNEES.md)

### Endpoints API

Documentation complète : **Section 5** du [README-RAPPORT.md](README-RAPPORT.md#5-fonctionnalités-et-interactions)

### Comptes de Test

**Admin** :
- Email : `admin@feminineaura.com`
- Password : `password123`

**Clients** :
- Email : `marie.dupont@example.com` / Password : `password123`
- Email : `sophie.martin@example.com` / Password : `password123`

---

## 🎯 Checklist Installation Complète

- [ ] XAMPP installé (Apache + MySQL démarrés)
- [ ] Node.js 18+ installé
- [ ] Projet cloné/téléchargé
- [ ] `npm install` exécuté (node_modules créé)
- [ ] Base de données créée (13 tables, 14 produits)
- [ ] `api/.env` configuré
- [ ] `.env.local` configuré
- [ ] API testée (curl ou navigateur)
- [ ] `npm run dev` lancé
- [ ] Page d'accueil affiche 14 produits
- [ ] Console sans erreurs CORS
- [ ] Login admin fonctionne
- [ ] Login client fonctionne

---

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifier la section **"Problèmes Courants"** ci-dessus
2. Consulter la section **6. Tests et Validation** du [README-RAPPORT.md](README-RAPPORT.md#6-tests-et-validation)
3. Vérifier les logs :
   - Console navigateur (F12)
   - XAMPP → Apache → Logs
   - Terminal Next.js

---

**Bonne installation ! 🚀**

*Si tout fonctionne, vous êtes prêt à explorer l'application.*
