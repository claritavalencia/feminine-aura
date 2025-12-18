# 🎀 Feminine Aura - Checklist de Configuration et Tests

## 📋 État Actuel du Projet

### ✅ Backend PHP API (COMPLET)
- [x] Base de données MySQL configurée
- [x] 14 produits en base de données
- [x] API REST fonctionnelle sur `http://localhost/Feminine Aura_last/api`
- [x] Endpoints testés et opérationnels
- [x] CORS configuré pour Next.js
- [x] Modèles : Produit, Categorie, Panier, Favori, Commande, Utilisatrice, Avis
- [x] Controllers : Auth, Produit, Categorie, Panier, Favori, Commande

### ✅ Frontend Next.js (EN COURS)
- [x] Next.js 16 avec Turbopack
- [x] TypeScript configuré
- [x] Tailwind CSS configuré
- [x] Contextes React créés (Auth, Cart, Favorites)
- [x] Composants UI (shadcn/ui)
- [x] Pages principales créées

---

## 🚨 PROBLÈMES IDENTIFIÉS

### 1️⃣ **Produits mock affichés au lieu des produits API** ⚠️
**Statut:** NON RÉSOLU
**Description:** La boutique affiche 8 produits mock au lieu des 14 produits de l'API
**Fichier:** `app/boutique/page.tsx`
**Cause probable:**
- L'API est accessible mais les produits mock sont utilisés par défaut
- Possible problème de CORS ou de fetch côté client

**Solution à tester:**
```typescript
// Vérifier dans la console du navigateur si l'API est appelée
// Check Network tab pour voir les requêtes
```

### 2️⃣ **Erreur 404 sur les favoris** ⚠️
**Statut:** NON RÉSOLU
**Description:** Clic sur le bouton favori génère une erreur 404
**Cause probable:**
- Le composant ProductCard redirige vers une mauvaise URL
- Possible problème avec le contexte Favorites

**Solution:**
- Le bouton favori ne devrait PAS rediriger mais juste toggle l'état
- Vérifier que le onClick du bouton Heart utilise `e.preventDefault()`

### 3️⃣ **URL encodée dans .env.local** ⚠️
**Statut:** IDENTIFIÉ
**Fichier:** `.env.local`
**Problème:** `NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api`
**Impact:** L'espace encodé peut causer des problèmes

---

## 🔧 CHECKLIST DE RÉPARATION

### Phase 1: Backend (API PHP)
- [x] Base de données créée et peuplée
- [x] API accessible via `http://localhost/Feminine Aura_last/api`
- [x] Test endpoint produits: ✅ Fonctionne (14 produits retournés)
- [ ] Test endpoint favoris (nécessite authentification)
- [ ] Test endpoint panier (nécessite authentification)
- [ ] Test endpoint auth/login
- [ ] Test endpoint auth/register

**Commandes de test:**
```bash
# Test API produits
curl "http://localhost/Feminine%20Aura_last/api/produits"

# Test API catégories
curl "http://localhost/Feminine%20Aura_last/api/categories"

# Test API home
curl "http://localhost/Feminine%20Aura_last/api"
```

### Phase 2: Frontend Next.js

#### A. Configuration de base
- [x] Next.js installé et configuré
- [x] Variables d'environnement dans `.env.local`
- [ ] **CORRIGER:** URL API sans espace encodé
- [x] Serveur dev qui tourne sur port 3000

#### B. Contextes React
- [x] AuthContext créé
- [x] CartContext créé
- [x] FavoritesContext créé
- [ ] **TESTER:** Vérifier que les contextes se chargent correctement
- [ ] **TESTER:** Vérifier localStorage pour utilisateur non connecté

#### C. Intégration API
- [x] Fichier `lib/api.ts` créé avec toutes les fonctions
- [ ] **VÉRIFIER:** Console browser pour les requêtes API
- [ ] **CORRIGER:** Boutique doit charger les produits depuis l'API
- [ ] **TESTER:** getProducts() dans la console browser

#### D. Pages et Composants
- [x] Page boutique (`app/boutique/page.tsx`)
- [x] Page produit (`app/product/[id]/page.tsx`)
- [x] ProductCard avec hooks Cart et Favorites
- [ ] **CORRIGER:** Bouton favori ne doit pas rediriger (404)
- [ ] **TESTER:** Ajout au panier fonctionne
- [ ] **TESTER:** Toggle favori fonctionne

---

## 🎯 PLAN D'ACTION IMMÉDIAT

### Étape 1: Déboguer l'affichage des produits
1. Ouvrir la console navigateur (F12)
2. Aller sur `/boutique`
3. Vérifier les logs API:
   - `🔵 API Request: ...`
   - `🟢 API Response status: 200`
   - `📦 API Data: ...`
4. Si pas de logs → problème fetch côté client
5. Si logs présents → problème transformation des données

### Étape 2: Corriger l'erreur 404 favoris
1. Vérifier que le bouton Heart a `e.preventDefault()`
2. Vérifier que le Link parent ne capture pas le clic
3. Tester le toggle sans redirection

### Étape 3: Tests d'intégration
1. Test ajout au panier (non connecté → localStorage)
2. Test ajout aux favoris (non connecté → localStorage)
3. Test connexion utilisateur
4. Test ajout au panier (connecté → API)
5. Test ajout aux favoris (connecté → API)

---

## 📊 ENDPOINTS API DISPONIBLES

### Authentification
- `POST /api/auth/login` - Connexion
- `POST /api/auth/register` - Inscription
- `POST /api/auth/logout` - Déconnexion
- `GET /api/auth/me` - Profil utilisateur (require auth)

### Produits
- `GET /api/produits` - Liste tous les produits ✅ TESTÉ
- `GET /api/produits/{id}` - Un produit spécifique
- `GET /api/produits?categorie={id}` - Filtrer par catégorie

### Catégories
- `GET /api/categories` - Toutes les catégories
- `GET /api/categories/{id}` - Une catégorie

### Panier (require auth)
- `GET /api/panier` - Voir le panier
- `POST /api/panier/add` - Ajouter un article
- `PUT /api/panier/update` - Modifier la quantité
- `DELETE /api/panier/remove` - Supprimer un article
- `DELETE /api/panier/clear` - Vider le panier

### Favoris (require auth)
- `GET /api/favoris` - Liste des favoris
- `POST /api/favoris` - Ajouter un favori
- `POST /api/favoris/toggle` - Toggle favori
- `DELETE /api/favoris` - Supprimer un favori

### Commandes (require auth)
- `GET /api/commandes` - Toutes les commandes
- `GET /api/commandes/{id}` - Une commande
- `POST /api/commandes` - Créer une commande

---

## 🧪 TESTS À EFFECTUER

### Tests Backend (API)
```bash
# 1. Vérifier l'API est accessible
curl "http://localhost/Feminine%20Aura_last/api"

# 2. Tester les produits
curl "http://localhost/Feminine%20Aura_last/api/produits"

# 3. Tester un produit spécifique
curl "http://localhost/Feminine%20Aura_last/api/produits/1"

# 4. Tester les catégories
curl "http://localhost/Feminine%20Aura_last/api/categories"

# 5. Test debug
curl "http://localhost/Feminine%20Aura_last/api/produits?debug=1"
```

### Tests Frontend (Browser Console)
```javascript
// 1. Tester l'appel API directement
fetch('http://localhost/Feminine%20Aura_last/api/produits')
  .then(r => r.json())
  .then(d => console.log(d))

// 2. Vérifier les contextes
// Dans React DevTools, chercher AuthProvider, CartProvider, FavoritesProvider

// 3. Vérifier localStorage
localStorage.getItem('feminine-aura-cart')
localStorage.getItem('feminine-aura-favorites')
localStorage.getItem('feminine-aura-user')
```

---

## 🐛 DEBUG TIPS

### Voir les logs API en temps réel
- Ouvrir: `c:\xampp\htdocs\Feminine Aura_last\api\logs\error.log`
- Ou dans XAMPP Control Panel → Apache → Logs

### Voir les requêtes Next.js
- Terminal où `npm run dev` tourne
- Chercher: `GET /boutique`, `GET /api/produits`

### Tester les contextes React
1. Installer React DevTools (extension Chrome/Firefox)
2. Ouvrir l'onglet Components
3. Chercher `AuthProvider`, `CartProvider`, `FavoritesProvider`
4. Voir les states et props

---

## ✨ FONCTIONNALITÉS À IMPLÉMENTER

### Priorité Haute (Core Features)
- [ ] Affichage des vrais produits de l'API ⚠️
- [ ] Ajout au panier fonctionnel
- [ ] Toggle favoris sans erreur 404 ⚠️
- [ ] Authentification utilisateur
- [ ] Panier synchronisé (LocalStorage + API)
- [ ] Favoris synchronisés (LocalStorage + API)

### Priorité Moyenne
- [ ] Page panier avec gestion quantités
- [ ] Page favoris avec liste
- [ ] Page profil utilisateur
- [ ] Processus de checkout
- [ ] Création de commandes

### Priorité Basse (Nice to Have)
- [ ] Système d'avis produits
- [ ] Recherche avancée
- [ ] Filtres par prix/taille/couleur
- [ ] Lookbook interactif
- [ ] Newsletter
- [ ] Animations et transitions

---

## 📁 STRUCTURE DU PROJET

```
Feminine Aura_last/
├── api/                          # Backend PHP
│   ├── config/
│   │   ├── database.php         # Config DB
│   │   └── cors.php             # CORS headers
│   ├── controllers/             # Controllers API
│   ├── models/                  # Modèles DB
│   ├── utils/                   # Helpers
│   └── index.php                # Router principal
├── app/                         # Frontend Next.js
│   ├── context/                 # React Contexts
│   │   ├── auth-context.tsx
│   │   ├── cart-context.tsx
│   │   └── favorites-context.tsx
│   ├── boutique/               # Page boutique
│   ├── product/[id]/           # Page produit
│   ├── cart/                   # Page panier
│   ├── favorites/              # Page favoris
│   └── ...
├── components/                 # Composants React
│   ├── product-card.tsx       # Carte produit
│   ├── header.tsx             # En-tête
│   └── footer.tsx             # Pied de page
├── lib/
│   └── api.ts                 # Client API
├── .env.local                 # Variables d'environnement
└── package.json
```

---

## 🎬 NEXT STEPS

1. **CORRIGER l'affichage des produits**
   - Ouvrir console navigateur
   - Vérifier les appels API
   - Corriger le chargement si nécessaire

2. **CORRIGER l'erreur 404 favoris**
   - Empêcher la redirection du Link
   - Vérifier le preventDefault

3. **TESTER l'authentification**
   - Créer un compte test
   - Se connecter
   - Vérifier le token dans localStorage

4. **TESTER panier et favoris**
   - Mode déconnecté (localStorage)
   - Mode connecté (API)

---

## 💡 RESSOURCES

- **API Documentation:** `http://localhost/Feminine Aura_last/api`
- **Next.js Docs:** https://nextjs.org/docs
- **React Context:** https://react.dev/reference/react/useContext
- **Tailwind CSS:** https://tailwindcss.com/docs

---

**Dernière mise à jour:** 2025-12-02
**Statut du projet:** 🟡 En développement
**Priorité actuelle:** Corriger affichage produits + erreur 404 favoris
