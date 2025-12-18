<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Favori.php';

class FavoriController extends BaseController {
    private $favoriModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->favoriModel = new Favori($db);
    }

    /**
     * Route le traitement
     */
    public function handleRequest($action = null) {
        // Vérifier l'authentification
        $user = $this->authenticate();
        if (!$user) {
            Response::unauthorized('Authentification requise');
        }

        switch ($this->requestMethod) {
            case 'GET':
                $this->getAll($user['id_utilisatrice']);
                break;

            case 'POST':
                if ($action === 'toggle') {
                    $this->toggle($user['id_utilisatrice']);
                } else {
                    $this->add($user['id_utilisatrice']);
                }
                break;

            case 'DELETE':
                $this->remove($user['id_utilisatrice']);
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    /**
     * GET /api/favoris - Récupère tous les favoris
     */
    private function getAll($userId) {
        try {
            $favoris = $this->favoriModel->getByUserWithProducts($userId);

            Response::success($favoris);

        } catch (Exception $e) {
            error_log("Erreur récupération favoris: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération des favoris');
        }
    }

    /**
     * POST /api/favoris - Ajoute un favori
     */
    private function add($userId) {
        try {
            $errors = $this->validateRequired(['id_produit']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');
            $result = $this->favoriModel->addFavorite($userId, $produitId);

            if (!$result) {
                Response::error('Ce produit est déjà dans vos favoris', 409);
            }

            Response::created(null, 'Produit ajouté aux favoris');

        } catch (Exception $e) {
            error_log("Erreur ajout favori: " . $e->getMessage());
            Response::serverError('Erreur lors de l\'ajout aux favoris');
        }
    }

    /**
     * POST /api/favoris/toggle - Toggle favori
     */
    private function toggle($userId) {
        try {
            $errors = $this->validateRequired(['id_produit']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');
            $result = $this->favoriModel->toggleFavorite($userId, $produitId);

            Response::success($result, $result['message']);

        } catch (Exception $e) {
            error_log("Erreur toggle favori: " . $e->getMessage());
            Response::serverError('Erreur lors de l\'opération');
        }
    }

    /**
     * DELETE /api/favoris - Supprime un favori
     */
    private function remove($userId) {
        try {
            $errors = $this->validateRequired(['id_produit']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');
            $this->favoriModel->removeFavorite($userId, $produitId);

            Response::success(null, 'Produit retiré des favoris');

        } catch (Exception $e) {
            error_log("Erreur suppression favori: " . $e->getMessage());
            Response::serverError('Erreur lors de la suppression du favori');
        }
    }
}
