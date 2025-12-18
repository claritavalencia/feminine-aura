<?php

require_once __DIR__ . '/BaseModel.php';

class Categorie extends BaseModel {
    protected $table = 'categorie';

    protected function getPrimaryKey() {
        return 'id_categorie';
    }

    /**
     * Récupère une catégorie par slug
     * @param string $slug
     * @return array|false
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Récupère toutes les catégories avec le nombre de produits
     * @return array
     */
    public function getAllWithProductCount() {
        $sql = "SELECT c.*, COUNT(p.id_produit) as nombre_produits
                FROM {$this->table} c
                LEFT JOIN produit p ON c.id_categorie = p.id_categorie AND p.actif = 1
                GROUP BY c.id_categorie
                ORDER BY c.nom ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
