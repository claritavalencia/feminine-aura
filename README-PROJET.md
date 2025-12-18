# Feminine Aura - Boutique de Lingerie

**Version:** 1.0.0
**Statut:** Base fonctionnelle - Corrections appliquées
**Tech Stack:** Next.js 16 (Frontend) + PHP/MySQL (Backend API)

---

## 📋 QUICK START

### Prérequis
- XAMPP (Apache + MySQL) installé et démarré
- Node.js installé
- Base de données créée et peuplée

### Démarrage rapide

**1. Backend (API PHP)**
```bash
# Démarrer XAMPP
# - Apache sur port 80
# - MySQL sur port 3306

# Vérifier que l'API fonctionne
curl "http://localhost/Feminine%20Aura_last/api"
```

**2. Frontend (Next.js)**
```bash
# Dans le dossier du projet
npm run dev

# Ouvrir dans le navigateur
http://localhost:3000
```

---

## 🎯 STATUT DU PROJET

### ✅ Fonctionnalités implémentées
- [x] Backend API REST complet (PHP)
- [x] Base de données avec 14 produits
- [x] Frontend Next.js avec TypeScript
- [x] Contextes React (Auth, Cart, Favorites)
- [x] Affichage des produits depuis l'API
- [x] Ajout au panier (localStorage + API)
- [x] Gestion des favoris (localStorage + API)
- [x] Pages principales (Boutique, Produit, Panier, Favoris, etc.)

### 🔧 Corrections récentes appliquées
- [x] **Correction:** Affichage des 14 produits API au lieu des 8 mock
- [x] **Correction:** Erreur 404 sur le bouton favoris
- [x] **Correction:** Propagation d'événements sur les boutons

### 🚧 À tester
- [ ] Vérifier les 14 produits s'affichent
- [ ] Tester le bouton favoris (sans 404)
- [ ] Tester le bouton panier (sans 404)
- [ ] Tester l'authentification
- [ ] Tester le panier connecté (API)
- [ ] Tester les favoris connectés (API)

---

## 📁 STRUCTURE DU PROJET

```
Feminine Aura_last/
├── 📂 api/                          # Backend PHP
│   ├── config/                      # Configuration DB & CORS
│   ├── controllers/                 # Controllers REST API
│   ├── models/                      # Modèles de données
│   ├── utils/                       # Utilitaires
│   └── index.php                    # Point d'entrée API
│
├── 📂 app/                          # Frontend Next.js
│   ├── context/                     # React Contexts
│   │   ├── auth-context.tsx        # Authentification
│   │   ├── cart-context.tsx        # Panier
│   │   └── favorites-context.tsx   # Favoris
│   ├── boutique/                    # Page boutique
│   ├── product/[id]/               # Page produit dynamique
│   ├── cart/                        # Page panier
│   ├── favorites/                   # Page favoris
│   ├── auth/                        # Page connexion/inscription
│   └── ...
│
├── 📂 components/                   # Composants React
│   ├── product-card.tsx            # ✅ Corrigé
│   ├── header.tsx
│   ├── footer.tsx
│   └── ui/                         # Composants shadcn/ui
│
├── 📂 lib/
│   └── api.ts                      # Client API HTTP
│
├── 📂 public/                       # Images & assets
│
├── 📄 .env.local                    # Variables d'environnement
├── 📄 package.json
├── 📄 tsconfig.json
│
└── 📚 DOCUMENTATION/
    ├── CHECKLIST-SETUP.md          # ✅ Checklist complète
    ├── DIAGNOSTIC.md               # ✅ Analyse des problèmes
    └── CORRECTIONS-APPLIQUEES.md   # ✅ Résumé des corrections
```

---

## 🔗 ENDPOINTS API

### Base URL
```
http://localhost/Feminine Aura_last/api
```

### Produits
- `GET /produits` - Liste tous les produits ✅
- `GET /produits/{id}` - Récupère un produit
- `GET /produits?categorie={id}` - Filtre par catégorie

### Authentification
- `POST /auth/login` - Connexion
- `POST /auth/register` - Inscription
- `POST /auth/logout` - Déconnexion
- `GET /auth/me` - Profil (require token)

### Panier (require auth)
- `GET /panier` - Voir le panier
- `POST /panier/add` - Ajouter un article
- `PUT /panier/update` - Modifier quantité
- `DELETE /panier/remove` - Supprimer article
- `DELETE /panier/clear` - Vider panier

### Favoris (require auth)
- `GET /favoris` - Liste favoris
- `POST /favoris/toggle` - Toggle favori
- `DELETE /favoris` - Supprimer favori

### Catégories
- `GET /categories` - Toutes les catégories
- `GET /categories/{id}` - Une catégorie

### Commandes (require auth)
- `GET /commandes` - Toutes les commandes
- `GET /commandes/{id}` - Une commande
- `POST /commandes` - Créer commande

---

## 🧪 TESTS

### Test rapide de l'API
```bash
# Vérifier que l'API répond
curl "http://localhost/Feminine%20Aura_last/api"

# Tester les produits (devrait retourner 14)
curl "http://localhost/Feminine%20Aura_last/api/produits"

# Tester un produit spécifique
curl "http://localhost/Feminine%20Aura_last/api/produits/1"
```

### Test Frontend (Browser Console)
```javascript
// Vérifier localStorage
localStorage.getItem('feminine-aura-cart')
localStorage.getItem('feminine-aura-favorites')

// Test direct API
fetch('http://localhost/Feminine%20Aura_last/api/produits')
  .then(r => r.json())
  .then(d => console.log(d))
```

---

## 📚 DOCUMENTATION DÉTAILLÉE

Pour plus de détails, consultez:

1. **[CHECKLIST-SETUP.md](CHECKLIST-SETUP.md)**
   - Checklist complète du projet
   - Plan d'action détaillé
   - Tests à effectuer
   - Structure complète

2. **[DIAGNOSTIC.md](DIAGNOSTIC.md)**
   - Analyse des problèmes identifiés
   - Solutions détaillées
   - Commandes de debug
   - Guide de dépannage

3. **[CORRECTIONS-APPLIQUEES.md](CORRECTIONS-APPLIQUEES.md)**
   - Résumé des corrections appliquées
   - Tests de validation
   - Prochaines étapes

---

## 🎨 FONCTIONNALITÉS PRINCIPALES

### Pour les utilisateurs non connectés
- ✅ Navigation dans la boutique
- ✅ Visualisation des produits
- ✅ Ajout au panier (localStorage)
- ✅ Ajout aux favoris (localStorage)
- ✅ Filtres par prix/taille/couleur
- ✅ Recherche de produits

### Pour les utilisateurs connectés
- ✅ Tout ce qui précède +
- ✅ Panier synchronisé (API + localStorage)
- ✅ Favoris synchronisés (API + localStorage)
- 🚧 Création de commandes
- 🚧 Historique des commandes
- 🚧 Profil utilisateur éditable
- 🚧 Système d'avis produits

---

## 🚀 DÉVELOPPEMENT

### Commandes utiles

```bash
# Démarrer le serveur de développement
npm run dev

# Build de production
npm run build

# Lancer en production
npm start

# Linter
npm run lint
```

### Variables d'environnement

Fichier `.env.local`:
```bash
NEXT_PUBLIC_API_URL=http://localhost/Feminine%20Aura_last/api
NODE_ENV=development
```

---

## 🐛 DÉPANNAGE

### Problème: Les produits ne s'affichent pas
1. Vérifier que XAMPP Apache est démarré
2. Vérifier que l'API répond: `curl "http://localhost/Feminine%20Aura_last/api/produits"`
3. Ouvrir la console navigateur (F12) et vérifier les logs
4. Faire un hard refresh (Ctrl+Shift+R)

### Problème: Erreur 404 sur les boutons
1. Vérifier que les corrections ont été appliquées
2. Vérifier que `e.stopPropagation()` est présent dans product-card.tsx
3. Redémarrer le serveur Next.js

### Problème: LocalStorage ne fonctionne pas
1. Vérifier dans la console: `localStorage.getItem('feminine-aura-cart')`
2. Vérifier que le contexte est bien chargé (React DevTools)
3. Clear localStorage et réessayer: `localStorage.clear()`

---

## 📊 BASE DE DONNÉES

### Produits en base
- **Total:** 14 produits
- **Catégories:** 3 (Soutiens-gorge, Culottes, Ensembles)

### Produits disponibles
1. Femina satin - 74.99€
2. Sweet tissu - 74.99€
3. Love brush - 70.99€
4. Love mood - 74.99€
5. Love Kit - 75.00€
6. Elegance - 72.20€
7. Premium collection - 73.00€
8. Classique - 75.00€
9. Soutien-gorge Rouge Passion - 39.99€
10. Soutien-gorge Rose Douceur - 35.99€
11. Soutien-gorge Noir Élégance - 42.99€
12. Culotte Rouge Charme - 19.99€
13. Culotte Rose Confort - 15.99€
14. String Noir Séduction - 22.99€

---

## 🎯 PROCHAINES ÉTAPES

### Priorité Haute
1. ✅ Corriger l'affichage des produits API
2. ✅ Corriger l'erreur 404 favoris
3. 🧪 Tester toutes les fonctionnalités
4. 🚧 Implémenter l'authentification complète
5. 🚧 Finaliser la page panier
6. 🚧 Finaliser la page favoris

### Priorité Moyenne
- Processus de checkout complet
- Page profil utilisateur
- Historique des commandes
- Système d'avis produits
- Filtres avancés

### Priorité Basse
- Lookbook interactif
- Newsletter
- Animations et transitions
- Optimisations SEO
- PWA (Progressive Web App)

---

## 👥 CONTRIBUTION

Ce projet est en développement actif. Les principales tâches sont documentées dans:
- [CHECKLIST-SETUP.md](CHECKLIST-SETUP.md)
- [DIAGNOSTIC.md](DIAGNOSTIC.md)
- [CORRECTIONS-APPLIQUEES.md](CORRECTIONS-APPLIQUEES.md)

---

## 📝 NOTES

### Dernières modifications
- **2025-12-02:** Corrections appliquées (produits API + erreur 404)
- **2025-12-02:** Documentation complète créée
- **2025-12-01:** Base de données créée et peuplée
- **2025-12-01:** API REST implémentée
- **2025-12-01:** Frontend Next.js initialisé

### Environnement de développement
- **OS:** Windows (XAMPP)
- **Node.js:** v18+
- **PHP:** 7.4+
- **MySQL:** 5.7+
- **Next.js:** 16.0.3

---

## 📞 RESSOURCES

- **API Doc:** http://localhost/Feminine Aura_last/api
- **Frontend:** http://localhost:3000
- **Next.js Docs:** https://nextjs.org/docs
- **React Docs:** https://react.dev
- **Tailwind CSS:** https://tailwindcss.com

---

**Happy Coding!** 🚀✨
