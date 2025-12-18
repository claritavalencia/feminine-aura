# ⚠️ CHECKLIST AVANT DE POUSSER SUR GIT

**À lire AVANT de faire `git push` pour la première fois !**

---

## 🔴 FICHIERS À SUPPRIMER ABSOLUMENT

### 1. Fichiers d'environnement (contiennent des secrets)

```bash
# À SUPPRIMER avant git add
.env.local           # Frontend (à la racine)
api/.env             # Backend (dans api/)
```

✅ **À GARDER** : `.env.example` et `api/.env.example` (templates vides)

---

### 2. Dossier node_modules/ (très volumineux)

```bash
# À SUPPRIMER avant git add
node_modules/        # ~500 MB !
```

✅ Le `.gitignore` empêche déjà son commit, mais vérifiez quand même.

---

### 3. Dossier .next/ (build Next.js)

```bash
# À SUPPRIMER avant git add
.next/               # Cache de build
```

✅ Sera régénéré avec `npm run dev`

---

### 4. Fichiers de logs

```bash
# À SUPPRIMER
*.log
npm-debug.log*
logs/
```

---

### 5. Fichiers système (Windows/Mac/Linux)

```bash
# À SUPPRIMER
Thumbs.db            # Windows
.DS_Store            # macOS
Desktop.ini          # Windows
```

---

### 6. Fichiers de brouillon/notes personnelles

```bash
# À SUPPRIMER si présents
NOTES-PERSO.md
TODO-PRIVE.md
MES-NOTES.txt
BROUILLON-*.md
```

---

### 7. Fichiers de test temporaires

```bash
# À SUPPRIMER si vous les avez créés
test-cors.php
test-api.php
debug.php
```

---

## 🟢 FICHIERS À ABSOLUMENT GARDER

### Documentation
- ✅ `README.md`
- ✅ `README-RAPPORT.md` (rapport académique complet)
- ✅ `INSTALLATION.md` (guide installation)
- ✅ `docs/database/MCD.md`
- ✅ `docs/database/MLD.md`
- ✅ `docs/database/DICTIONNAIRE-DONNEES.md`

### Configuration
- ✅ `.gitignore` (pour ignorer les fichiers sensibles)
- ✅ `.env.example` (template frontend)
- ✅ `api/.env.example` (template backend)
- ✅ `package.json`
- ✅ `tsconfig.json`
- ✅ `tailwind.config.ts`
- ✅ `next.config.ts`

### Base de données
- ✅ `api/database/schema.sql` (structure)
- ✅ `api/database/seed.sql` (données de démo)

### Code source
- ✅ Tout le dossier `app/` (frontend)
- ✅ Tout le dossier `components/`
- ✅ Tout le dossier `lib/`
- ✅ Tout le dossier `api/` (sauf `.env` et `vendor/`)
- ✅ Dossier `public/` (images, assets)

---

## 📋 COMMANDES À EXÉCUTER AVANT LE PUSH

### 1. Vérifier que .gitignore est bien configuré

```bash
# Afficher le contenu du .gitignore
cat .gitignore

# Doit contenir au minimum :
# node_modules/
# .next/
# .env
# .env.local
# api/.env
# *.log
```

### 2. Vérifier les fichiers qui seront commités

```bash
# Afficher ce qui SERA committé
git status

# Afficher ce qui est IGNORÉ
git status --ignored
```

**⚠️ Vérifiez que :**
- ❌ `.env.local` n'apparaît PAS
- ❌ `api/.env` n'apparaît PAS
- ❌ `node_modules/` n'apparaît PAS
- ✅ Uniquement votre code source apparaît

### 3. Nettoyer les fichiers suivis par erreur

Si vous avez déjà committé `.env` ou `node_modules/` :

```bash
# Retirer du tracking Git (SANS supprimer localement)
git rm --cached .env.local
git rm --cached api/.env
git rm --cached -r node_modules/
git rm --cached -r .next/

# Commit la suppression
git commit -m "Remove sensitive files from tracking"
```

### 4. Tester l'installation depuis zéro (optionnel mais recommandé)

```bash
# Cloner dans un autre dossier
git clone [URL-DE-VOTRE-REPO] test-install
cd test-install

# Suivre INSTALLATION.md
cp .env.example .env.local
cp api/.env.example api/.env
npm install
# ... etc

# Si ça marche, vous êtes prêt !
```

---

## 🚀 WORKFLOW GIT RECOMMANDÉ

### Première fois (initialiser le dépôt)

```bash
# 1. Initialiser Git (si pas encore fait)
git init

# 2. Ajouter le remote (votre repo GitHub)
git remote add origin https://github.com/[USERNAME]/feminine-aura.git

# 3. Vérifier ce qui sera committé
git status

# 4. Ajouter tous les fichiers (le .gitignore fait le tri)
git add .

# 5. Vérifier à nouveau (sécurité)
git status

# 6. Commit initial
git commit -m "Initial commit: Feminine Aura e-commerce project"

# 7. Pousser sur GitHub
git push -u origin main
```

### Commits suivants

```bash
# 1. Vérifier les changements
git status

# 2. Ajouter les fichiers modifiés
git add .

# 3. Commit avec message clair
git commit -m "Description du changement"

# 4. Pousser
git push
```

---

## 🎓 PARTAGER AVEC VOTRE PROFESSEUR

### Option 1 : Repo Public

```bash
# Sur GitHub :
# Settings → Danger Zone → Change visibility → Make public
```

**Puis partager** :
```
Lien du repo : https://github.com/[USERNAME]/feminine-aura
```

### Option 2 : Repo Privé + Accès

```bash
# Sur GitHub :
# Settings → Collaborators → Add people
# Entrer le nom d'utilisateur GitHub de votre prof
```

**Envoyer à votre prof** :
```
Bonjour [Nom du professeur],

Voici le lien vers mon projet Feminine Aura :
https://github.com/[USERNAME]/feminine-aura

J'ai ajouté votre compte GitHub (@[PROF-USERNAME]) comme collaborateur.

Guide d'installation : INSTALLATION.md
Rapport complet : README-RAPPORT.md

Comptes de test :
- Admin : admin@feminineaura.com / password123
- Client : marie.dupont@example.com / password123

Merci pour votre évaluation !

Cordialement,
[Votre Nom]
```

---

## ✅ CHECKLIST FINALE AVANT PUSH

- [ ] `.gitignore` créé et configuré
- [ ] `.env.local` NON présent dans `git status`
- [ ] `api/.env` NON présent dans `git status`
- [ ] `node_modules/` NON présent
- [ ] `.next/` NON présent
- [ ] `.env.example` et `api/.env.example` PRÉSENTS
- [ ] `README-RAPPORT.md` complet et à jour
- [ ] `INSTALLATION.md` créé
- [ ] `schema.sql` et `seed.sql` présents dans `api/database/`
- [ ] Tous les fichiers de documentation présents
- [ ] Test du repo cloné dans un autre dossier réussi

---

## 🔐 SÉCURITÉ : VÉRIFICATION DES SECRETS

Avant de pousser, vérifiez qu'aucun secret n'est présent :

```bash
# Rechercher des patterns de secrets
grep -r "password123" . --exclude-dir=node_modules --exclude-dir=.git
grep -r "DB_PASSWORD=" . --exclude-dir=node_modules --exclude-dir=.git
grep -r "SECRET" . --exclude-dir=node_modules --exclude-dir=.git
```

**Si vous trouvez :**
- Dans `.env` ou `.env.local` → Normal, mais vérifiez qu'ils sont dans `.gitignore`
- Dans le code source → ⚠️ PROBLÈME ! Utilisez des variables d'environnement

---

## 📞 En Cas de Problème

Si vous avez accidentellement poussé des fichiers sensibles :

### Supprimer un fichier de l'historique Git

```bash
# ATTENTION : Change l'historique Git, à utiliser AVANT le push si possible
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch .env.local" \
  --prune-empty --tag-name-filter cat -- --all

git push origin --force --all
```

**OU utiliser BFG Repo-Cleaner** (plus rapide) :
```bash
# https://rtyley.github.io/bfg-repo-cleaner/
bfg --delete-files .env.local
git push origin --force --all
```

---

**Bonne chance pour votre partage Git ! 🚀**

*N'oubliez pas : une fois qu'un fichier est poussé sur Git, il est TRÈS difficile de le supprimer complètement. Mieux vaut prévenir que guérir !*
