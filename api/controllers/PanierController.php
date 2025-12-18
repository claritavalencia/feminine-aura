<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Panier.php';
require_once __DIR__ . '/../models/Produit.php';

class PanierController extends BaseController {
    private $panierModel;
    private $produitModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->panierModel = new Panier($db);
        $this->produitModel = new Produit($db);
    }

    /**
     * Route le traitement selon la méthode HTTP
     */
    public function handleRequest($action = null) {
        // Vérifier l'authentification
        $user = $this->authenticate();
        if (!$user) {
            Response::unauthorized('Authentification requise');
        }

        switch ($this->requestMethod) {
            case 'GET':
                $this->getCart($user['id_utilisatrice']);
                break;

            case 'POST':
                if ($action === 'add') {
                    $this->addItem($user['id_utilisatrice']);
                } else {
                    Response::notFound('Action non trouvée');
                }
                break;

            case 'PUT':
            case 'PATCH':
                if ($action === 'update') {
                    $this->updateItem($user['id_utilisatrice']);
                } else {
                    Response::notFound('Action non trouvée');
                }
                break;

            case 'DELETE':
                if ($action === 'remove') {
                    $this->removeItem($user['id_utilisatrice']);
                } elseif ($action === 'clear') {
                    $this->clearCart($user['id_utilisatrice']);
                } else {
                    Response::notFound('Action non trouvée');
                }
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    /**
     * GET /api/panier - Récupère le panier de l'utilisateur
     */
    private function getCart($userId) {
        try {
            $panier = $this->panierModel->getWithItems($userId);

            Response::success($panier);

        } catch (Exception $e) {
            error_log("Erreur récupération panier: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération du panier');
        }
    }

    /**
     * POST /api/panier/add - Ajoute un produit au panier
     */
    private function addItem($userId) {
        try {
            // Valider les données
            $errors = $this->validateRequired(['id_produit', 'quantite']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');
            $quantite = (int)$this->getParam('quantite');

            // Vérifier que le produit existe
            $produit = $this->produitModel->getById($produitId);
            if (!$produit || !$produit['actif']) {
                Response::notFound('Produit non trouvé ou non disponible');
            }

            // Vérifier le stock
            if (!$this->produitModel->checkStock($produitId, $quantite)) {
                Response::error('Stock insuffisant', 400);
            }

            // Récupérer ou créer le panier
            $panier = $this->panierModel->getOrCreateForUser($userId);

            // Ajouter l'article
            $this->panierModel->addItem(
                $panier['id_panier'],
                $produitId,
                $quantite,
                $produit['prix']
            );

            // Retourner le panier mis à jour
            $panierUpdated = $this->panierModel->getWithItems($userId);

            Response::success($panierUpdated, 'Article ajouté au panier');

        } catch (Exception $e) {
            error_log("Erreur ajout au panier: " . $e->getMessage());
            Response::serverError('Erreur lors de l\'ajout au panier');
        }
    }

    /**
     * PUT /api/panier/update - Met à jour la quantité d'un article
     */
    private function updateItem($userId) {
        try {
            // Valider les données
            $errors = $this->validateRequired(['id_produit', 'quantite']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');
            $quantite = (int)$this->getParam('quantite');

            // Vérifier le stock si augmentation
            if ($quantite > 0) {
                if (!$this->produitModel->checkStock($produitId, $quantite)) {
                    Response::error('Stock insuffisant', 400);
                }
            }

            // Récupérer le panier
            $panier = $this->panierModel->getOrCreateForUser($userId);

            // Mettre à jour l'article
            $this->panierModel->updateItemQuantity(
                $panier['id_panier'],
                $produitId,
                $quantite
            );

            // Retourner le panier mis à jour
            $panierUpdated = $this->panierModel->getWithItems($userId);

            Response::success($panierUpdated, 'Panier mis à jour');

        } catch (Exception $e) {
            error_log("Erreur mise à jour panier: " . $e->getMessage());
            Response::serverError('Erreur lors de la mise à jour du panier');
        }
    }

    /**
     * DELETE /api/panier/remove - Supprime un article du panier
     */
    private function removeItem($userId) {
        try {
            // Valider les données
            $errors = $this->validateRequired(['id_produit']);
            if ($errors) {
                Response::validationError($errors);
            }

            $produitId = $this->getParam('id_produit');

            // Récupérer le panier
            $panier = $this->panierModel->getOrCreateForUser($userId);

            // Supprimer l'article
            $this->panierModel->removeItem($panier['id_panier'], $produitId);

            // Retourner le panier mis à jour
            $panierUpdated = $this->panierModel->getWithItems($userId);

            Response::success($panierUpdated, 'Article retiré du panier');

        } catch (Exception $e) {
            error_log("Erreur suppression article panier: " . $e->getMessage());
            Response::serverError('Erreur lors de la suppression de l\'article');
        }
    }

    /**
     * DELETE /api/panier/clear - Vide le panier
     */
    private function clearCart($userId) {
        try {
            $panier = $this->panierModel->getOrCreateForUser($userId);
            $this->panierModel->clear($panier['id_panier']);

            Response::success(null, 'Panier vidé');

        } catch (Exception $e) {
            error_log("Erreur vidage panier: " . $e->getMessage());
            Response::serverError('Erreur lors du vidage du panier');
        }
    }
}
