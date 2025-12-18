<?php

require_once __DIR__ . '/BaseModel.php';

class Avis extends BaseModel {
    protected $table = 'avis';

    protected function getPrimaryKey() {
        return 'id_avis';
    }

    /**
     * Récupère tous les avis approuvés d'un produit
     * @param int $produitId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getApprovedByProduct($produitId, $limit = null, $offset = 0) {
        $sql = "SELECT a.*, u.email
                FROM {$this->table} a
                LEFT JOIN utilisatrice u ON a.id_utilisatrice = u.id_utilisatrice
                WHERE a.id_produit = :produit_id AND a.approuve = 1
                ORDER BY a.date_avis DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ajoute un avis
     * @param int $userId
     * @param int $produitId
     * @param int $note
     * @param string $commentaire
     * @return int|false
     */
    public function addReview($userId, $produitId, $note, $commentaire = '') {
        // Vérifier si l'utilisateur a déjà laissé un avis
        if ($this->hasUserReviewed($userId, $produitId)) {
            return false;
        }

        // Validation de la note
        if ($note < 1 || $note > 5) {
            return false;
        }

        return $this->create([
            'id_utilisatrice' => $userId,
            'id_produit' => $produitId,
            'note' => $note,
            'commentaire' => $commentaire,
            'approuve' => false // Nécessite approbation admin
        ]);
    }

    /**
     * Vérifie si un utilisateur a déjà laissé un avis pour un produit
     * @param int $userId
     * @param int $produitId
     * @return bool
     */
    public function hasUserReviewed($userId, $produitId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}
                WHERE id_utilisatrice = :user_id AND id_produit = :produit_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Approuve un avis (admin)
     * @param int $avisId
     * @return bool
     */
    public function approve($avisId) {
        return $this->update($avisId, ['approuve' => 1]);
    }

    /**
     * Récupère tous les avis en attente d'approbation
     * @return array
     */
    public function getPendingReviews() {
        $sql = "SELECT a.*, u.email, p.nom as produit_nom
                FROM {$this->table} a
                LEFT JOIN utilisatrice u ON a.id_utilisatrice = u.id_utilisatrice
                LEFT JOIN produit p ON a.id_produit = p.id_produit
                WHERE a.approuve = 0
                ORDER BY a.date_avis DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
