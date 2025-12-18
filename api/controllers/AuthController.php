<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Utilisatrice.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->userModel = new Utilisatrice($db);
    }

    /**
     * Route le traitement selon l'action
     * @param string $action Action à effectuer (login, register, logout, me)
     */
    public function handleRequest($action) {
        if ($this->requestMethod !== 'POST' && $action !== 'me') {
            $this->methodNotAllowed();
        }

        switch ($action) {
            case 'login':
                $this->login();
                break;

            case 'register':
                $this->register();
                break;

            case 'logout':
                $this->logout();
                break;

            case 'me':
                $this->getCurrentUser();
                break;

            default:
                Response::notFound('Action non trouvée');
        }
    }

    /**
     * POST /api/auth/login - Authentifie un utilisateur
     */
    private function login() {
        try {
            // Récupérer les données - accepter "password" ou "mot_de_passe"
            $email = $this->getParam('email');
            $password = $this->getParam('mot_de_passe') ?? $this->getParam('password');

            // Validation
            if (empty($email)) {
                Response::error('Email requis', 400);
            }

            if (empty($password)) {
                Response::error('Mot de passe requis', 400);
            }

            // Authentifier
            $user = $this->userModel->login($email, $password);

            if (!$user) {
                Response::error('Email ou mot de passe incorrect', 401);
            }

            // Générer un token
            $token = $this->generateToken($user);

            Response::success([
                'user' => $user,
                'token' => $token
            ], 'Connexion réussie');

        } catch (Exception $e) {
            error_log("Erreur login: " . $e->getMessage());
            Response::error('Erreur lors de la connexion: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/auth/register - Enregistre un nouvel utilisateur
     */
    private function register() {
        try {
            // Récupérer les données - accepter "password" ou "mot_de_passe"
            $email = $this->getParam('email');
            $password = $this->getParam('mot_de_passe') ?? $this->getParam('password');

            // Validation minimale
            if (empty($email)) {
                Response::error('Email requis', 400);
            }

            if (empty($password)) {
                Response::error('Mot de passe requis', 400);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Response::error('Email invalide', 400);
            }

            if (strlen($password) < 6) {
                Response::error('Le mot de passe doit contenir au moins 6 caractères', 400);
            }

            // Vérifier si l'email existe déjà
            if ($this->userModel->emailExists($email)) {
                Response::error('Cet email est déjà utilisé', 409);
            }

            // Créer l'utilisateur
            $userId = $this->userModel->register($email, $password, 'client');
            $user = $this->userModel->getById($userId);

            // Supprimer le mot de passe de la réponse
            unset($user['mot_de_passe']);

            // Générer un token
            $token = $this->generateToken($user);

            Response::created([
                'user' => $user,
                'token' => $token
            ], 'Inscription réussie');

        } catch (Exception $e) {
            error_log("Erreur register: " . $e->getMessage());
            Response::error('Erreur lors de l\'inscription: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/auth/logout - Déconnecte l'utilisateur
     */
    private function logout() {
        // Pour JWT, le logout est géré côté client en supprimant le token
        Response::success(null, 'Déconnexion réussie');
    }

    /**
     * GET /api/auth/me - Récupère l'utilisateur connecté
     */
    private function getCurrentUser() {
        try {
            $user = $this->authenticate();

            if (!$user) {
                Response::unauthorized('Non authentifié');
            }

            // Récupérer les infos complètes de l'utilisateur
            $fullUser = $this->userModel->getById($user['id_utilisatrice']);
            unset($fullUser['mot_de_passe']);

            Response::success($fullUser);

        } catch (Exception $e) {
            error_log("Erreur get current user: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération de l\'utilisateur');
        }
    }

    /**
     * Génère un token JWT (simplifié pour l'instant)
     * TODO: Implémenter une vraie génération JWT
     * @param array $user
     * @return string
     */
    private function generateToken($user) {
        // Version simplifiée - à remplacer par une vraie implémentation JWT
        $payload = [
            'id' => $user['id_utilisatrice'],
            'email' => $user['email'],
            'role' => $user['role'],
            'exp' => time() + (int)($_ENV['JWT_EXPIRATION'] ?? 3600)
        ];

        // Pour l'instant, encoder en base64 (NON SÉCURISÉ - juste pour dev)
        return base64_encode(json_encode($payload));
    }
}
