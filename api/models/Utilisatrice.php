<?php

require_once __DIR__ . '/BaseModel.php';

class Utilisatrice extends BaseModel {
    protected $table = 'utilisatrice';

    protected function getPrimaryKey() {
        return 'id_utilisatrice';
    }

    /**
     * Récupère une utilisatrice par email
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Crée une nouvelle utilisatrice avec mot de passe hashé
     * @param array $data
     * @return int
     */
    public function register($email, $password, $role = 'client') {
        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'email' => $email,
            'mot_de_passe' => $hashedPassword,
            'role' => $role
        ];

        return $this->create($data);
    }

    /**
     * Vérifie les credentials de connexion
     * @param string $email
     * @param string $password
     * @return array|false Retourne l'utilisatrice si authentification réussie, false sinon
     */
    public function login($email, $password) {
        $user = $this->getByEmail($email);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Ne pas retourner le mot de passe
            unset($user['mot_de_passe']);
            return $user;
        }

        return false;
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Met à jour le mot de passe
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        return $this->update($userId, [
            'mot_de_passe' => $hashedPassword
        ]);
    }

    /**
     * Récupère toutes les utilisatrices sans leurs mots de passe
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllSafe($limit = null, $offset = 0) {
        $sql = "SELECT id_utilisatrice, email, role, date_creation, date_modification
                FROM {$this->table}";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
