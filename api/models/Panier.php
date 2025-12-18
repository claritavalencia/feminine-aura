<?php

require_once __DIR__ . '/BaseModel.php';

class Panier extends BaseModel {
    protected $table = 'panier';

    protected function getPrimaryKey() {
        return 'id_panier';
    }

    /**
     * Récupère ou crée le panier d'une utilisatrice
     * @param int $userId
     * @return array
     */
    public function getOrCreateForUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE id_utilisatrice = :user_id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $panier = $stmt->fetch();

        if (!$panier) {
            // Créer le panier s'il n'existe pas
            $panierId = $this->create(['id_utilisatrice' => $userId]);
            return $this->getById($panierId);
        }

        return $panier;
    }

    /**
     * Récupère le panier avec tous ses articles
     * @param int $userId
     * @return array
     */
    public function getWithItems($userId) {
        $panier = $this->getOrCreateForUser($userId);

        $sql = "SELECT lp.*, p.nom, p.prix, p.image_url, p.stock, p.actif
                FROM ligne_panier lp
                LEFT JOIN produit p ON lp.id_produit = p.id_produit
                WHERE lp.id_panier = :panier_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':panier_id', $panier['id_panier'], PDO::PARAM_INT);
        $stmt->execute();

        $panier['items'] = $stmt->fetchAll();

        // Calculer le total
        $panier['total'] = 0;
        foreach ($panier['items'] as $item) {
            $panier['total'] += $item['prix_unitaire'] * $item['quantite'];
        }

        return $panier;
    }

    /**
     * Ajoute un produit au panier
     * @param int $panierId
     * @param int $produitId
     * @param int $quantite
     * @param float $prixUnitaire
     * @return bool
     */
    public function addItem($panierId, $produitId, $quantite, $prixUnitaire) {
        // Vérifier si le produit existe déjà dans le panier
        $sql = "SELECT * FROM ligne_panier
                WHERE id_panier = :panier_id AND id_produit = :produit_id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':panier_id', $panierId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);
        $stmt->execute();

        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Mettre à jour la quantité
            $sql = "UPDATE ligne_panier
                    SET quantite = quantite + :quantite
                    WHERE id_ligne_panier = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':id', $existingItem['id_ligne_panier'], PDO::PARAM_INT);

            return $stmt->execute();
        } else {
            // Ajouter une nouvelle ligne
            $sql = "INSERT INTO ligne_panier (id_panier, id_produit, quantite, prix_unitaire)
                    VALUES (:panier_id, :produit_id, :quantite, :prix_unitaire)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':panier_id', $panierId, PDO::PARAM_INT);
            $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':prix_unitaire', $prixUnitaire);

            return $stmt->execute();
        }
    }

    /**
     * Met à jour la quantité d'un article dans le panier
     * @param int $panierId
     * @param int $produitId
     * @param int $quantite
     * @return bool
     */
    public function updateItemQuantity($panierId, $produitId, $quantite) {
        if ($quantite <= 0) {
            return $this->removeItem($panierId, $produitId);
        }

        $sql = "UPDATE ligne_panier
                SET quantite = :quantite
                WHERE id_panier = :panier_id AND id_produit = :produit_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
        $stmt->bindValue(':panier_id', $panierId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Supprime un article du panier
     * @param int $panierId
     * @param int $produitId
     * @return bool
     */
    public function removeItem($panierId, $produitId) {
        $sql = "DELETE FROM ligne_panier
                WHERE id_panier = :panier_id AND id_produit = :produit_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':panier_id', $panierId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Vide le panier
     * @param int $panierId
     * @return bool
     */
    public function clear($panierId) {
        $sql = "DELETE FROM ligne_panier WHERE id_panier = :panier_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':panier_id', $panierId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
