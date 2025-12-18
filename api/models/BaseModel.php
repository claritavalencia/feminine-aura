<?php

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les enregistrements
     * @param array $conditions Conditions WHERE optionnelles
     * @param int $limit Limite de résultats
     * @param int $offset Offset pour pagination
     * @return array
     */
    public function getAll($conditions = [], $limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($conditions)) {
            $sql .= " WHERE " . $this->buildWhereClause($conditions);
        }

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        // Bind des conditions
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        // Bind limit et offset si nécessaire
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère un enregistrement par ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $primaryKey = $this->getPrimaryKey();
        $sql = "SELECT * FROM {$this->table} WHERE {$primaryKey} = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Crée un nouvel enregistrement
     * @param array $data
     * @return int ID de l'enregistrement créé
     */
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ":{$field}";
        }, $fields);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour un enregistrement
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $primaryKey = $this->getPrimaryKey();
        $fields = [];

        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = :{$field}";
        }

        $sql = "UPDATE {$this->table}
                SET " . implode(', ', $fields) . "
                WHERE {$primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute();
    }

    /**
     * Supprime un enregistrement
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $primaryKey = $this->getPrimaryKey();
        $sql = "DELETE FROM {$this->table} WHERE {$primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Compte le nombre d'enregistrements
     * @param array $conditions Conditions WHERE optionnelles
     * @return int
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($conditions)) {
            $sql .= " WHERE " . $this->buildWhereClause($conditions);
        }

        $stmt = $this->db->prepare($sql);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        $result = $stmt->fetch();

        return (int)$result['total'];
    }

    /**
     * Construit la clause WHERE à partir d'un tableau de conditions
     * @param array $conditions
     * @return string
     */
    protected function buildWhereClause($conditions) {
        $clauses = [];

        foreach (array_keys($conditions) as $key) {
            $clauses[] = "{$key} = :{$key}";
        }

        return implode(' AND ', $clauses);
    }

    /**
     * Retourne le nom de la clé primaire de la table
     * @return string
     */
    abstract protected function getPrimaryKey();
}
