<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Produit.php';

class ProduitController extends BaseController {
    private $produitModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->produitModel = new Produit($db);
    }

    /**
     * Route le traitement selon la méthode HTTP
     * @param string|null $id ID du produit (optionnel)
     */
    public function handleRequest($id = null) {
        switch ($this->requestMethod) {
            case 'GET':
                if ($id) {
                    $this->getOne($id);
                } else {
                    $this->getAll();
                }
                break;

            case 'POST':
                $this->create();
                break;

            case 'PUT':
            case 'PATCH':
                if ($id) {
                    $this->update($id);
                } else {
                    Response::error('ID produit requis pour la mise à jour', 400);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $this->delete($id);
                } else {
                    Response::error('ID produit requis pour la suppression', 400);
                }
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    /**
     * GET /api/produits - Liste tous les produits avec filtres
     */
    private function getAll() {
        try {
            // Récupérer les filtres
            $filters = [
                'categorie' => $this->getParam('categorie'),
                'couleur' => $this->getParam('couleur'),
                'taille' => $this->getParam('taille'),
                'tissu' => $this->getParam('tissu'),
                'prix_min' => $this->getParam('prix_min'),
                'prix_max' => $this->getParam('prix_max'),
                'recherche' => $this->getParam('recherche')
            ];

            // Paramètres de tri
            $orderBy = $this->getParam('order_by', 'date_creation');
            $orderDir = $this->getParam('order_dir', 'DESC');

            // Pagination
            $pagination = $this->getPaginationParams();

            // Récupérer les produits
            $produits = $this->produitModel->getAllWithFilters(
                $filters,
                $orderBy,
                $orderDir,
                $pagination['limit'],
                $pagination['offset']
            );

            // Compter le total pour la pagination
            $total = $this->produitModel->count(['actif' => 1]);

            Response::success([
                'produits' => $produits,
                'pagination' => [
                    'page' => $pagination['page'],
                    'limit' => $pagination['limit'],
                    'total' => $total,
                    'pages' => ceil($total / $pagination['limit'])
                ]
            ]);

        } catch (Exception $e) {
            error_log("Erreur récupération produits: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération des produits');
        }
    }

    /**
     * GET /api/produits/{id} - Récupère un produit par ID
     */
    private function getOne($id) {
        try {
            $produit = $this->produitModel->getByIdWithCategory($id);

            if (!$produit) {
                Response::notFound('Produit non trouvé');
            }

            // Récupérer la note moyenne
            $rating = $this->produitModel->getAverageRating($id);
            $produit['rating'] = $rating;

            // Récupérer les produits similaires
            $produit['similaires'] = $this->produitModel->getSimilarProducts(
                $id,
                $produit['id_categorie'],
                3
            );

            Response::success($produit);

        } catch (Exception $e) {
            error_log("Erreur récupération produit: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération du produit');
        }
    }

    /**
     * POST /api/produits - Crée un nouveau produit (admin)
     */
    private function create() {
        try {
            // Vérifier l'authentification admin
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            // Valider les données
            $required = ['id_categorie', 'nom', 'prix'];
            $errors = $this->validateRequired($required);

            if ($errors) {
                Response::validationError($errors);
            }

            // Créer le produit
            $data = [
                'id_categorie' => $this->getParam('id_categorie'),
                'nom' => $this->getParam('nom'),
                'description' => $this->getParam('description', ''),
                'prix' => $this->getParam('prix'),
                'stock' => $this->getParam('stock', 0),
                'couleur' => $this->getParam('couleur'),
                'taille' => $this->getParam('taille'),
                'tissu' => $this->getParam('tissu'),
                'image_url' => $this->getParam('image_url'),
                'actif' => $this->getParam('actif', 1)
            ];

            $produitId = $this->produitModel->create($data);
            $produit = $this->produitModel->getById($produitId);

            Response::created($produit, 'Produit créé avec succès');

        } catch (Exception $e) {
            error_log("Erreur création produit: " . $e->getMessage());
            Response::serverError('Erreur lors de la création du produit');
        }
    }

    /**
     * PUT/PATCH /api/produits/{id} - Met à jour un produit (admin)
     */
    private function update($id) {
        try {
            // Vérifier l'authentification admin
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            // Vérifier que le produit existe
            $produit = $this->produitModel->getById($id);
            if (!$produit) {
                Response::notFound('Produit non trouvé');
            }

            // Préparer les données de mise à jour
            $data = [];
            $allowedFields = ['id_categorie', 'nom', 'description', 'prix', 'stock', 'couleur', 'taille', 'tissu', 'image_url', 'actif'];

            foreach ($allowedFields as $field) {
                $value = $this->getParam($field);
                if ($value !== null) {
                    $data[$field] = $value;
                }
            }

            if (empty($data)) {
                Response::error('Aucune donnée à mettre à jour', 400);
            }

            $this->produitModel->update($id, $data);
            $produitUpdated = $this->produitModel->getById($id);

            Response::success($produitUpdated, 'Produit mis à jour avec succès');

        } catch (Exception $e) {
            error_log("Erreur mise à jour produit: " . $e->getMessage());
            Response::serverError('Erreur lors de la mise à jour du produit');
        }
    }

    /**
     * DELETE /api/produits/{id} - Supprime un produit (admin)
     */
    private function delete($id) {
        try {
            // Vérifier l'authentification admin
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            // Vérifier que le produit existe
            $produit = $this->produitModel->getById($id);
            if (!$produit) {
                Response::notFound('Produit non trouvé');
            }

            // Soft delete: mettre actif à 0 au lieu de supprimer
            $this->produitModel->update($id, ['actif' => 0]);

            Response::success(null, 'Produit supprimé avec succès');

        } catch (Exception $e) {
            error_log("Erreur suppression produit: " . $e->getMessage());
            Response::serverError('Erreur lors de la suppression du produit');
        }
    }
}
