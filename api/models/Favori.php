<?php

require_once __DIR__ . '/BaseModel.php';

class Favori extends BaseModel {
    protected $table = 'favori';

    protected function getPrimaryKey() {
        return 'id_favori';
    }

    /**
     * Récupère tous les favoris d'une utilisatrice avec détails des produits
     * @param int $userId
     * @return array
     */
    public function getByUserWithProducts($userId) {
        $sql = "SELECT f.*, p.nom, p.prix, p.image_url, p.couleur, p.taille
                FROM {$this->table} f
                LEFT JOIN produit p ON f.id_produit = p.id_produit
                WHERE f.id_utilisatrice = :user_id
                AND p.actif = 1
                ORDER BY f.date_ajout DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Ajoute un produit aux favoris
     * @param int $userId
     * @param int $produitId
     * @return int|bool ID du favori créé ou false si déjà existant
     */
    public function addFavorite($userId, $produitId) {
        // Vérifier si le favori existe déjà
        if ($this->isFavorite($userId, $produitId)) {
            return false;
        }

        return $this->create([
            'id_utilisatrice' => $userId,
            'id_produit' => $produitId
        ]);
    }

    /**
     * Supprime un produit des favoris
     * @param int $userId
     * @param int $produitId
     * @return bool
     */
    public function removeFavorite($userId, $produitId) {
        $sql = "DELETE FROM {$this->table}
                WHERE id_utilisatrice = :user_id AND id_produit = :produit_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Vérifie si un produit est dans les favoris
     * @param int $userId
     * @param int $produitId
     * @return bool
     */
    public function isFavorite($userId, $produitId) {
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
     * Toggle favori (ajoute si n'existe pas, supprime sinon)
     * @param int $userId
     * @param int $produitId
     * @return array Statut et message
     */
    public function toggleFavorite($userId, $produitId) {
        if ($this->isFavorite($userId, $produitId)) {
            $this->removeFavorite($userId, $produitId);
            return ['action' => 'removed', 'message' => 'Produit retiré des favoris'];
        } else {
            $this->addFavorite($userId, $produitId);
            return ['action' => 'added', 'message' => 'Produit ajouté aux favoris'];
        }
    }
}
