<?php

/**
 * Point d'entrée principal de l'API Feminine Aura
 *
 * Architecture REST avec routing manuel
 * Toutes les requêtes passent par ce fichier
 */

// Activer le reporting d'erreurs en mode développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// IMPORTANT: Charger le .env AVANT CORS pour avoir accès aux variables
require_once __DIR__ . '/config/database.php';

// Configuration CORS (maintenant $_ENV est chargé)
require_once __DIR__ . '/config/cors.php';
CORS::enable();
CORS::setJsonHeader();

// Charger les controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProduitController.php';
require_once __DIR__ . '/controllers/CategorieController.php';
require_once __DIR__ . '/controllers/PanierController.php';
require_once __DIR__ . '/controllers/FavoriController.php';
require_once __DIR__ . '/controllers/CommandeController.php';

// Charger les utilitaires
require_once __DIR__ . '/utils/Response.php';

/**
 * Classe principale du routeur
 */
class Router {
    private $db;
    private $requestUri;
    private $requestMethod;

    public function __construct() {
        try {
            // Connexion à la base de données
            $database = new Database();
            $this->db = $database->connect();

            // Récupérer la méthode et l'URI
            $this->requestMethod = $_SERVER['REQUEST_METHOD'];
            $this->requestUri = $this->parseUri();

        } catch (Exception $e) {
            error_log("Erreur d'initialisation: " . $e->getMessage());
            Response::serverError('Erreur de connexion à la base de données');
        }
    }

    /**
     * Parse l'URI pour extraire les segments de route
     * @return array
     */
    private function parseUri() {
        $uri = $_SERVER['REQUEST_URI'];

        // Retirer la query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Retirer /Feminine Aura_last/api/ du début (avec ou sans slash final)
        // Gérer l'espace encodé en %20 ou espace normal
        $uri = preg_replace('#^/Feminine(%20| )Aura_last/api/?#', '', $uri);

        // Retirer les slashes de début et fin
        $uri = trim($uri, '/');

        // Si l'URI est vide après nettoyage, retourner un tableau vide
        if ($uri === '' || $uri === 'index.php') {
            return [];
        }

        // Diviser en segments et filtrer les segments vides
        $segments = explode('/', $uri);
        $segments = array_filter($segments, function($segment) {
            return $segment !== '' && $segment !== 'index.php';
        });

        // Réindexer le tableau
        return array_values($segments);
    }

    /**
     * Route la requête vers le bon controller
     */
    public function route() {
        $segments = $this->requestUri;

        // DEBUG - AFFICHER DANS LA REPONSE
        $debugInfo = [
            'uri_brute' => $_SERVER['REQUEST_URI'],
            'segments' => $segments,
            'nb_segments' => count($segments),
            'empty' => empty($segments)
        ];

        // SI MODE DEBUG, afficher les infos et arrêter
        if (isset($_GET['debug'])) {
            Response::success($debugInfo, 'Debug info');
            return;
        }

        // Route vide = page d'accueil de l'API
        if (empty($segments)) {
            $this->apiHome();
            return;
        }

        $resource = $segments[0];
        $param1 = $segments[1] ?? null;
        $param2 = $segments[2] ?? null;

        try {
            switch ($resource) {
                // Routes d'authentification
                case 'auth':
                    $controller = new AuthController($this->db);
                    $action = $param1 ?? 'login';
                    $controller->handleRequest($action);
                    break;

                // Routes produits
                case 'produits':
                    $controller = new ProduitController($this->db);
                    // Peut être un ID ou null
                    $controller->handleRequest($param1);
                    break;

                // Routes catégories
                case 'categories':
                    $controller = new CategorieController($this->db);
                    $controller->handleRequest($param1);
                    break;

                // Routes panier
                case 'panier':
                    $controller = new PanierController($this->db);
                    // $param1 peut être: add, update, remove, clear
                    $controller->handleRequest($param1);
                    break;

                // Routes favoris
                case 'favoris':
                    $controller = new FavoriController($this->db);
                    $controller->handleRequest($param1);
                    break;

                // Routes commandes
                case 'commandes':
                    $controller = new CommandeController($this->db);
                    $controller->handleRequest($param1);
                    break;

                // Route non trouvée
                default:
                    Response::notFound('Route non trouvée');
            }

        } catch (Exception $e) {
            error_log("Erreur de routing: " . $e->getMessage());
            Response::serverError('Une erreur est survenue');
        }
    }

    /**
     * Page d'accueil de l'API - Documentation des routes
     */
    private function apiHome() {
        $apiInfo = [
            'name' => 'Feminine Aura API',
            'version' => '1.0.0',
            'description' => 'API REST pour la boutique de lingerie Feminine Aura',
            'endpoints' => [
                'auth' => [
                    'POST /api/auth/login' => 'Connexion utilisateur',
                    'POST /api/auth/register' => 'Inscription utilisateur',
                    'POST /api/auth/logout' => 'Déconnexion',
                    'GET /api/auth/me' => 'Récupérer l\'utilisateur connecté'
                ],
                'produits' => [
                    'GET /api/produits' => 'Liste tous les produits (avec filtres)',
                    'GET /api/produits/{id}' => 'Récupère un produit',
                    'POST /api/produits' => 'Crée un produit (admin)',
                    'PUT /api/produits/{id}' => 'Met à jour un produit (admin)',
                    'DELETE /api/produits/{id}' => 'Supprime un produit (admin)'
                ],
                'categories' => [
                    'GET /api/categories' => 'Liste toutes les catégories',
                    'GET /api/categories/{id}' => 'Récupère une catégorie',
                    'POST /api/categories' => 'Crée une catégorie (admin)',
                    'PUT /api/categories/{id}' => 'Met à jour une catégorie (admin)',
                    'DELETE /api/categories/{id}' => 'Supprime une catégorie (admin)'
                ],
                'panier' => [
                    'GET /api/panier' => 'Récupère le panier',
                    'POST /api/panier/add' => 'Ajoute un article au panier',
                    'PUT /api/panier/update' => 'Met à jour la quantité',
                    'DELETE /api/panier/remove' => 'Supprime un article',
                    'DELETE /api/panier/clear' => 'Vide le panier'
                ],
                'favoris' => [
                    'GET /api/favoris' => 'Liste tous les favoris',
                    'POST /api/favoris' => 'Ajoute un favori',
                    'POST /api/favoris/toggle' => 'Toggle favori',
                    'DELETE /api/favoris' => 'Supprime un favori'
                ],
                'commandes' => [
                    'GET /api/commandes' => 'Liste toutes les commandes',
                    'GET /api/commandes/{id}' => 'Récupère une commande',
                    'POST /api/commandes' => 'Crée une commande',
                    'PUT /api/commandes/{id}' => 'Met à jour le statut (admin)'
                ]
            ],
            'authentication' => 'Bearer Token (JWT)',
            'documentation' => 'https://github.com/votre-repo/api-docs'
        ];

        Response::success($apiInfo, 'Bienvenue sur l\'API Feminine Aura');
    }
}

// Initialiser et router
$router = new Router();
$router->route();
