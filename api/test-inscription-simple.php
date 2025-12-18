<?php
/**
 * Test simple d'inscription
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test d'inscription</h1>";

// Email et mot de passe de test
$testEmail = 'nouveau-' . time() . '@example.com';
$testPassword = 'password123';

echo "<p><strong>Email:</strong> $testEmail</p>";
echo "<p><strong>Mot de passe:</strong> $testPassword</p>";
echo "<hr>";

// Charger les fichiers nécessaires
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Utilisatrice.php';

try {
    // Connexion à la base
    $database = new Database();
    $db = $database->connect();
    echo "<p style='color: green;'> Connexion à la base réussie</p>";

    // Créer l'instance du modèle
    $userModel = new Utilisatrice($db);

    // Vérifier si l'email existe
    echo "<h2>1. Vérification de l'email</h2>";
    if ($userModel->emailExists($testEmail)) {
        echo "<p style='color: orange;'> Cet email existe déjà</p>";
    } else {
        echo "<p style='color: green;'> Email disponible</p>";

        // Tenter l'inscription
        echo "<h2>2. Inscription</h2>";
        $userId = $userModel->register($testEmail, $testPassword, 'client');

        if ($userId) {
            echo "<p style='color: green; font-size: 20px;'> INSCRIPTION RÉUSSIE !</p>";
            echo "<p><strong>ID utilisateur créé:</strong> $userId</p>";

            // Récupérer l'utilisateur
            $user = $userModel->getById($userId);
            unset($user['mot_de_passe']);
            echo "<h3>Données de l'utilisateur:</h3>";
            echo "<pre>" . print_r($user, true) . "</pre>";

            // Test de connexion
            echo "<h2>3. Test de connexion</h2>";
            $loginResult = $userModel->login($testEmail, $testPassword);

            if ($loginResult) {
                echo "<p style='color: green; font-size: 20px;'>✅ ✅ ✅ CONNEXION RÉUSSIE !</p>";
                echo "<pre>" . print_r($loginResult, true) . "</pre>";
            } else {
                echo "<p style='color: red;'> Échec de la connexion</p>";
            }

        } else {
            echo "<p style='color: red;'> Échec de l'inscription</p>";
        }
    }

} catch (Exception $e) {
    echo "<p style='color: red;'> Erreur: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
