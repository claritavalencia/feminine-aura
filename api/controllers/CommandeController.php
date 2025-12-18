<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/Panier.php';
require_once __DIR__ . '/../models/Produit.php';

class CommandeController extends BaseController {
    private $commandeModel;
    private $panierModel;
    private $produitModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->commandeModel = new Commande($db);
        $this->panierModel = new Panier($db);
        $this->produitModel = new Produit($db);
    }

    /**
     * Route le traitement
     */
    public function handleRequest($id = null) {
        // Vérifier l'authentification
        $user = $this->authenticate();
        if (!$user) {
            Response::unauthorized('Authentification requise');
        }

        switch ($this->requestMethod) {
            case 'GET':
                if ($id) {
                    $this->getOne($user, $id);
                } else {
                    $this->getAll($user);
                }
                break;

            case 'POST':
                $this->create($user);
                break;

            case 'PUT':
            case 'PATCH':
                if ($id) {
                    $this->updateStatus($user, $id);
                } else {
                    Response::error('ID commande requis', 400);
                }
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    /**
     * GET /api/commandes - Liste toutes les commandes de l'utilisateur
     */
    private function getAll($user) {
        try {
            $pagination = $this->getPaginationParams();

            // Admin peut voir toutes les commandes, client seulement les siennes
            if ($this->isAdmin($user)) {
                $commandes = $this->commandeModel->getAll([], $pagination['limit'], $pagination['offset']);
            } else {
                $commandes = $this->commandeModel->getByUser(
                    $user['id_utilisatrice'],
                    $pagination['limit'],
                    $pagination['offset']
                );
            }

            Response::success($commandes);

        } catch (Exception $e) {
            error_log("Erreur récupération commandes: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération des commandes');
        }
    }

    /**
     * GET /api/commandes/{id} - Récupère une commande
     */
    private function getOne($user, $id) {
        try {
            $commande = $this->commandeModel->getByIdWithDetails($id);

            if (!$commande) {
                Response::notFound('Commande non trouvée');
            }

            // Vérifier que l'utilisateur a le droit de voir cette commande
            if (!$this->isAdmin($user) && $commande['id_utilisatrice'] != $user['id_utilisatrice']) {
                Response::forbidden('Accès interdit');
            }

            Response::success($commande);

        } catch (Exception $e) {
            error_log("Erreur récupération commande: " . $e->getMessage());
            Response::serverError('Erreur lors de la récupération de la commande');
        }
    }

    /**
     * POST /api/commandes - Crée une nouvelle commande
     */
    private function create($user) {
        try {
            // Valider les données
            $required = ['adresse_livraison', 'ville', 'code_postal', 'telephone'];
            $errors = $this->validateRequired($required);
            if ($errors) {
                Response::validationError($errors);
            }

            // Récupérer le panier
            $panier = $this->panierModel->getWithItems($user['id_utilisatrice']);

            if (empty($panier['items'])) {
                Response::error('Le panier est vide', 400);
            }

            // Vérifier le stock pour tous les produits
            foreach ($panier['items'] as $item) {
                if (!$this->produitModel->checkStock($item['id_produit'], $item['quantite'])) {
                    Response::error("Stock insuffisant pour {$item['nom']}", 400);
                }
            }

            // Préparer les données de la commande
            $commandeData = [
                'id_utilisatrice' => $user['id_utilisatrice'],
                'total_commande' => $panier['total'],
                'statut_commande' => 'en_attente',
                'adresse_livraison' => $this->getParam('adresse_livraison'),
                'ville' => $this->getParam('ville'),
                'code_postal' => $this->getParam('code_postal'),
                'pays' => $this->getParam('pays', 'France'),
                'telephone' => $this->getParam('telephone')
            ];

            // Préparer les lignes de commande
            $lignes = [];
            foreach ($panier['items'] as $item) {
                $lignes[] = [
                    'id_produit' => $item['id_produit'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire']
                ];
            }

            // Créer la commande avec transaction
            $commandeId = $this->commandeModel->createWithLines($commandeData, $lignes);

            // Décrémenter le stock
            foreach ($panier['items'] as $item) {
                $this->produitModel->decrementStock($item['id_produit'], $item['quantite']);
            }

            // Vider le panier
            $this->panierModel->clear($panier['id_panier']);

            // Récupérer la commande créée
            $commande = $this->commandeModel->getByIdWithDetails($commandeId);

            Response::created($commande, 'Commande créée avec succès');

        } catch (Exception $e) {
            error_log("Erreur création commande: " . $e->getMessage());
            Response::serverError('Erreur lors de la création de la commande');
        }
    }

    /**
     * PUT /api/commandes/{id} - Met à jour le statut d'une commande (admin)
     */
    private function updateStatus($user, $id) {
        try {
            // Vérifier que c'est un admin
            if (!$this->isAdmin($user)) {
                Response::forbidden('Accès réservé aux administrateurs');
            }

            $errors = $this->validateRequired(['statut']);
            if ($errors) {
                Response::validationError($errors);
            }

            $statut = $this->getParam('statut');

            // Vérifier que la commande existe
            $commande = $this->commandeModel->getById($id);
            if (!$commande) {
                Response::notFound('Commande non trouvée');
            }

            // Mettre à jour le statut
            $result = $this->commandeModel->updateStatus($id, $statut);

            if (!$result) {
                Response::error('Statut invalide', 400);
            }

            $commandeUpdated = $this->commandeModel->getByIdWithDetails($id);

            Response::success($commandeUpdated, 'Statut mis à jour');

        } catch (Exception $e) {
            error_log("Erreur mise à jour commande: " . $e->getMessage());
            Response::serverError('Erreur lors de la mise à jour de la commande');
        }
    }
}
