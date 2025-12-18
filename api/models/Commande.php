<?php

require_once __DIR__ . '/BaseModel.php';

class Commande extends BaseModel {
    protected $table = 'commande';

    protected function getPrimaryKey() {
        return 'id_commande';
    }

    /**
     * Récupère une commande avec ses lignes et informations utilisateur
     * @param int $id
     * @return array|false
     */
    public function getByIdWithDetails($id) {
        // Récupérer la commande
        $sql = "SELECT c.*, u.email, p.mode_paiement, p.statut_paiement
                FROM {$this->table} c
                LEFT JOIN utilisatrice u ON c.id_utilisatrice = u.id_utilisatrice
                LEFT JOIN paiement p ON c.id_paiement = p.id_paiement
                WHERE c.id_commande = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $commande = $stmt->fetch();

        if (!$commande) {
            return false;
        }

        // Récupérer les lignes de commande
        $sql = "SELECT lc.*, pr.nom, pr.image_url
                FROM ligne_commande lc
                LEFT JOIN produit pr ON lc.id_produit = pr.id_produit
                WHERE lc.id_commande = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $commande['lignes'] = $stmt->fetchAll();

        return $commande;
    }

    /**
     * Récupère toutes les commandes d'une utilisatrice
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getByUser($userId, $limit = null, $offset = 0) {
        $sql = "SELECT c.*, p.statut_paiement
                FROM {$this->table} c
                LEFT JOIN paiement p ON c.id_paiement = p.id_paiement
                WHERE c.id_utilisatrice = :user_id
                ORDER BY c.date_commande DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Crée une commande complète avec ses lignes
     * @param array $commandeData Données de la commande
     * @param array $lignes Lignes de commande
     * @return int ID de la commande créée
     */
    public function createWithLines($commandeData, $lignes) {
        try {
            $this->db->beginTransaction();

            // Générer le numéro de commande
            $commandeData['numero_commande'] = $this->generateOrderNumber();

            // Créer la commande
            $commandeId = $this->create($commandeData);

            // Créer les lignes de commande
            $sqlLine = "INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire, sous_total)
                        VALUES (:id_commande, :id_produit, :quantite, :prix_unitaire, :sous_total)";

            $stmtLine = $this->db->prepare($sqlLine);

            foreach ($lignes as $ligne) {
                $stmtLine->execute([
                    ':id_commande' => $commandeId,
                    ':id_produit' => $ligne['id_produit'],
                    ':quantite' => $ligne['quantite'],
                    ':prix_unitaire' => $ligne['prix_unitaire'],
                    ':sous_total' => $ligne['quantite'] * $ligne['prix_unitaire']
                ]);
            }

            $this->db->commit();
            return $commandeId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Génère un numéro de commande unique
     * @return string
     */
    private function generateOrderNumber() {
        $prefix = 'CMD';
        $year = date('Y');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Récupère une commande par numéro
     * @param string $numeroCommande
     * @return array|false
     */
    public function getByNumber($numeroCommande) {
        $sql = "SELECT * FROM {$this->table} WHERE numero_commande = :numero LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':numero', $numeroCommande);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Met à jour le statut d'une commande
     * @param int $commandeId
     * @param string $statut
     * @return bool
     */
    public function updateStatus($commandeId, $statut) {
        $validStatuts = ['en_attente', 'confirmee', 'en_preparation', 'expediee', 'livree', 'annulee'];

        if (!in_array($statut, $validStatuts)) {
            return false;
        }

        return $this->update($commandeId, ['statut_commande' => $statut]);
    }
}
