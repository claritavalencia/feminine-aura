<?php

require_once __DIR__ . '/BaseModel.php';

class Produit extends BaseModel {
    protected $table = 'produit';

    protected function getPrimaryKey() {
        return 'id_produit';
    }

    /**
     * Récupère tous les produits actifs avec informations de catégorie
     * @param array $filters Filtres optionnels (categorie, couleur, taille, prix_min, prix_max, recherche)
     * @param string $orderBy Colonne de tri
     * @param string $orderDir Direction du tri (ASC/DESC)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllWithFilters($filters = [], $orderBy = 'date_creation', $orderDir = 'DESC', $limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.nom as categorie_nom, c.slug as categorie_slug
                FROM {$this->table} p
                LEFT JOIN categorie c ON p.id_categorie = c.id_categorie
                WHERE p.actif = 1";

        $params = [];

        // Filtre par catégorie
        if (!empty($filters['categorie'])) {
            $sql .= " AND p.id_categorie = :categorie";
            $params['categorie'] = $filters['categorie'];
        }

        // Filtre par couleur
        if (!empty($filters['couleur'])) {
            $sql .= " AND p.couleur = :couleur";
            $params['couleur'] = $filters['couleur'];
        }

        // Filtre par taille
        if (!empty($filters['taille'])) {
            $sql .= " AND p.taille = :taille";
            $params['taille'] = $filters['taille'];
        }

        // Filtre par tissu
        if (!empty($filters['tissu'])) {
            $sql .= " AND p.tissu = :tissu";
            $params['tissu'] = $filters['tissu'];
        }

        // Filtre par prix minimum
        if (isset($filters['prix_min'])) {
            $sql .= " AND p.prix >= :prix_min";
            $params['prix_min'] = $filters['prix_min'];
        }

        // Filtre par prix maximum
        if (isset($filters['prix_max'])) {
            $sql .= " AND p.prix <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }

        // Recherche par nom ou description
        if (!empty($filters['recherche'])) {
            $sql .= " AND (p.nom LIKE :recherche OR p.description LIKE :recherche)";
            $params['recherche'] = '%' . $filters['recherche'] . '%';
        }

        // Tri
        $allowedOrderBy = ['prix', 'nom', 'date_creation', 'stock'];
        if (in_array($orderBy, $allowedOrderBy)) {
            $orderDir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY p.{$orderBy} {$orderDir}";
        }

        // Pagination
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        // Bind des paramètres
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère un produit avec ses informations de catégorie
     * @param int $id
     * @return array|false
     */
    public function getByIdWithCategory($id) {
        $sql = "SELECT p.*, c.nom as categorie_nom, c.slug as categorie_slug
                FROM {$this->table} p
                LEFT JOIN categorie c ON p.id_categorie = c.id_categorie
                WHERE p.id_produit = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Récupère les produits d'une catégorie
     * @param int $categorieId
     * @param int $limit
     * @return array
     */
    public function getByCategorie($categorieId, $limit = null) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_categorie = :categorie_id AND actif = 1";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categorie_id', $categorieId, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les produits similaires (même catégorie, excluant le produit actuel)
     * @param int $produitId
     * @param int $categorieId
     * @param int $limit
     * @return array
     */
    public function getSimilarProducts($produitId, $categorieId, $limit = 3) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_categorie = :categorie_id
                AND id_produit != :produit_id
                AND actif = 1
                ORDER BY RAND()
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categorie_id', $categorieId, PDO::PARAM_INT);
        $stmt->bindValue(':produit_id', $produitId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Vérifie la disponibilité du stock
     * @param int $produitId
     * @param int $quantite
     * @return bool
     */
    public function checkStock($produitId, $quantite) {
        $produit = $this->getById($produitId);

        return $produit && $produit['stock'] >= $quantite;
    }

    /**
     * Décrémente le stock
     * @param int $produitId
     * @param int $quantite
     * @return bool
     */
    public function decrementStock($produitId, $quantite) {
        $sql = "UPDATE {$this->table}
                SET stock = stock - :quantite
                WHERE id_produit = :id AND stock >= :quantite";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $produitId, PDO::PARAM_INT);
        $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Incrémente le stock
     * @param int $produitId
     * @param int $quantite
     * @return bool
     */
    public function incrementStock($produitId, $quantite) {
        $sql = "UPDATE {$this->table}
                SET stock = stock + :quantite
                WHERE id_produit = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $produitId, PDO::PARAM_INT);
        $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Récupère la note moyenne d'un produit
     * @param int $produitId
     * @return float
     */
    public function getAverageRating($produitId) {
        $sql = "SELECT AVG(note) as moyenne, COUNT(*) as total
                FROM avis
                WHERE id_produit = :id AND approuve = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $produitId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return [
            'moyenne' => $result['moyenne'] ? round($result['moyenne'], 1) : 0,
            'total' => $result['total']
        ];
    }
}
