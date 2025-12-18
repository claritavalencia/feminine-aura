<?php

require_once __DIR__ . '/../utils/Response.php';

abstract class BaseController {
    protected $db;
    protected $requestMethod;
    protected $requestUri;
    protected $requestData;

    public function __construct($db) {
        $this->db = $db;
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->requestData = $this->parseRequestData();
    }

    /**
     * Parse les données de la requête (JSON ou form-data)
     * @return array
     */
    protected function parseRequestData() {
        $data = [];

        // Pour les requêtes POST, PUT, PATCH
        if (in_array($this->requestMethod, ['POST', 'PUT', 'PATCH'])) {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (strpos($contentType, 'application/json') !== false) {
                // Données JSON
                $input = file_get_contents('php://input');
                $data = json_decode($input, true) ?? [];
            } else {
                // Form data
                $data = $_POST;
            }
        } elseif ($this->requestMethod === 'GET') {
            $data = $_GET;
        }

        return $data;
    }

    /**
     * Valide les champs requis
     * @param array $required Champs requis
     * @param array $data Données à valider
     * @return array|null Erreurs de validation ou null si valide
     */
    protected function validateRequired($required, $data = null) {
        $data = $data ?? $this->requestData;
        $errors = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = "Le champ {$field} est requis";
            }
        }

        return empty($errors) ? null : $errors;
    }

    /**
     * Récupère un paramètre de la requête
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getParam($key, $default = null) {
        return $this->requestData[$key] ?? $default;
    }

    /**
     * Récupère les paramètres de pagination
     * @return array
     */
    protected function getPaginationParams() {
        $page = max(1, (int)$this->getParam('page', 1));
        $limit = min(100, max(1, (int)$this->getParam('limit', 20)));
        $offset = ($page - 1) * $limit;

        return [
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * Vérifie l'authentification (à implémenter avec JWT)
     * @return array|false Données utilisateur ou false
     */
    protected function authenticate() {
        // TODO: Implémenter JWT authentication
        // Pour l'instant, retourne un utilisateur fictif en mode dev
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($authHeader)) {
            return false;
        }

        // Extraire le token
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];

            // TODO: Valider le JWT token
            // Pour dev, simuler un utilisateur
            if ($_ENV['APP_ENV'] === 'development') {
                return [
                    'id_utilisatrice' => 1,
                    'email' => 'test@example.com',
                    'role' => 'client'
                ];
            }
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur est admin
     * @param array $user
     * @return bool
     */
    protected function isAdmin($user) {
        return isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Retourne une réponse de méthode non autorisée
     */
    protected function methodNotAllowed() {
        Response::methodNotAllowed("Méthode {$this->requestMethod} non autorisée pour cette route");
    }
}
