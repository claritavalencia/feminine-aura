<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Categorie.php';

class CategorieController extends BaseController {
    private $categorieModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->categorieModel = new Categorie($db);
    }

    /**
     * Route le traitement
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
                    Response::error('ID catégorie requis', 400);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $this->delete($id);
                } else {
                    Response::error('ID catégorie requis', 400);
                }
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    /**
     * GET /api/categories - Liste toutes les catégories
     */
    private function getAll() {
        try {
            $categories = $this->categorieModel->getAllWithProductCount();

            Response::success($categories);

        } catch (Exception $e) {
            error_log("Erreur récupération catégories: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération des catégories');
        }
    }

    /**
     * GET /api/categories/{id} - Récupère une catégorie
     */
    private function getOne($id) {
        try {
            $categorie = $this->categorieModel->getById($id);

            if (!$categorie) {
                Response::notFound('Catégorie non trouvée');
            }

            Response::success($categorie);

        } catch (Exception $e) {
            error_log("Erreur récupération catégorie: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération de la catégorie');
        }
    }

    /**
     * POST /api/categories - Crée une catégorie (admin)
     */
    private function create() {
        try {
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            $errors = $this->validateRequired(['nom', 'slug']);
            if ($errors) {
                Response::validationError($errors);
            }

            $data = [
                'nom' => $this->getParam('nom'),
                'slug' => $this->getParam('slug'),
                'description' => $this->getParam('description', '')
            ];

            $categorieId = $this->categorieModel->create($data);
            $categorie = $this->categorieModel->getById($categorieId);

            Response::created($categorie, 'Catégorie créée avec succès');

        } catch (Exception $e) {
            error_log("Erreur création catégorie: " . $e->getMessage());
            Response::serverError('Erreur lors de la création de la catégorie');
        }
    }

    /**
     * PUT /api/categories/{id} - Met à jour une catégorie (admin)
     */
    private function update($id) {
        try {
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            $categorie = $this->categorieModel->getById($id);
            if (!$categorie) {
                Response::notFound('Catégorie non trouvée');
            }

            $data = [];
            $allowedFields = ['nom', 'slug', 'description'];

            foreach ($allowedFields as $field) {
                $value = $this->getParam($field);
                if ($value !== null) {
                    $data[$field] = $value;
                }
            }

            if (empty($data)) {
                Response::error('Aucune donnée à mettre à jour', 400);
            }

            $this->categorieModel->update($id, $data);
            $categorieUpdated = $this->categorieModel->getById($id);

            Response::success($categorieUpdated, 'Catégorie mise à jour');

        } catch (Exception $e) {
            error_log("Erreur mise à jour catégorie: " . $e->getMessage());
            Response::serverError('Erreur lors de la mise à jour de la catégorie');
        }
    }

    /**
     * DELETE /api/categories/{id} - Supprime une catégorie (admin)
     */
    private function delete($id) {
        try {
            $user = $this->authenticate();
            if (!$user || !$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            $categorie = $this->categorieModel->getById($id);
            if (!$categorie) {
                Response::notFound('Catégorie non trouvée');
            }

            $this->categorieModel->delete($id);

            Response::success(null, 'Catégorie supprimée');

        } catch (Exception $e) {
            error_log("Erreur suppression catégorie: " . $e->getMessage());
            Response::serverError('Erreur lors de la suppression de la catégorie');
        }
    }
}
