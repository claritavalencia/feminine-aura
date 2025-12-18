<?php

class CORS {

    /**
     * Configure les en-têtes CORS pour permettre les requêtes cross-origin
     */
    public static function enable() {
        // Charger les origines autorisées depuis .env
        $allowedOrigins = explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? 'http://localhost:3000');
        $allowedMethods = $_ENV['CORS_ALLOWED_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS';
        $allowedHeaders = $_ENV['CORS_ALLOWED_HEADERS'] ?? 'Content-Type,Authorization,X-Requested-With';

        // Récupérer l'origine de la requête
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        // En mode développement, autoriser toutes les origines localhost
        $isDevelopment = ($_ENV['APP_ENV'] ?? 'production') === 'development';

        if ($isDevelopment && (empty($origin) || strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false)) {
            // En développement, accepter toutes les requêtes localhost
            header("Access-Control-Allow-Origin: " . ($origin ?: 'http://localhost:3000'));
        } elseif (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        } elseif (in_array('*', $allowedOrigins)) {
            header("Access-Control-Allow-Origin: *");
        }

        header("Access-Control-Allow-Methods: $allowedMethods");
        header("Access-Control-Allow-Headers: $allowedHeaders");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Credentials: true");

        // Gérer les requêtes OPTIONS (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    /**
     * Définit le type de contenu JSON
     */
    public static function setJsonHeader() {
        header('Content-Type: application/json; charset=UTF-8');
    }
}
