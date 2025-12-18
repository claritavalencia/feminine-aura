<?php
/**
 * Script de test pour vérifier la connexion admin
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Test Connexion Admin</h1>";
echo "<style>
    body { font-family: Arial; max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { background: #d4edda; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #cce5ff; color: #004085; padding: 15px; margin: 10px 0; border-radius: 5px; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
</style>";

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->connect();

    echo "<div class='success'>✅ Connexion à la base réussie</div>";

    // Chercher le compte admin
    $stmt = $db->prepare("SELECT * FROM utilisatrice WHERE email = :email");
    $stmt->execute(['email' => 'admin@feminineaura.com']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "<div class='success'>✅ Compte admin trouvé</div>";
        echo "<div class='info'>";
        echo "<h3>Détails du compte :</h3>";
        echo "<strong>ID:</strong> " . $admin['id_utilisatrice'] . "<br>";
        echo "<strong>Email:</strong> " . $admin['email'] . "<br>";
        echo "<strong>Rôle:</strong> " . $admin['role'] . "<br>";
        echo "<strong>Date création:</strong> " . $admin['date_creation'] . "<br>";
        echo "<strong>Hash du mot de passe:</strong> <code style='font-size: 10px;'>" . substr($admin['mot_de_passe'], 0, 50) . "...</code><br>";
        echo "</div>";

        // Tester le mot de passe
        $testPassword = 'password123';
        echo "<div class='info'><h3>Test du mot de passe 'password123' :</h3></div>";

        if (password_verify($testPassword, $admin['mot_de_passe'])) {
            echo "<div class='success'>✅ ✅ ✅ Le mot de passe est CORRECT !</div>";
        } else {
            echo "<div class='error'>❌ Le mot de passe ne correspond PAS</div>";

            // Essayer de créer un nouveau hash
            echo "<div class='info'>";
            echo "<h3>Hash attendu pour 'password123' :</h3>";
            $newHash = password_hash($testPassword, PASSWORD_BCRYPT);
            echo "<code style='font-size: 10px;'>$newHash</code><br>";
            echo "<p><strong>Recommandation :</strong> Réexécutez install-db.php pour recréer les comptes</p>";
            echo "</div>";
        }

    } else {
        echo "<div class='error'>❌ Aucun compte admin trouvé avec cet email</div>";
        echo "<div class='info'>";
        echo "<p><strong>Solution :</strong> Exécutez <a href='install-db.php'>install-db.php</a> pour créer la base et les comptes de test</p>";
        echo "</div>";
    }

    // Lister tous les utilisateurs
    echo "<div class='info'>";
    echo "<h3>Liste de tous les utilisateurs :</h3>";
    $stmt = $db->query("SELECT id_utilisatrice, email, role FROM utilisatrice");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($users, true) . "</pre>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
}

// Test de connexion via API
echo "<hr>";
echo "<h2>Test de connexion via l'API</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/Feminine%20Aura_last/api/auth/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'admin@feminineaura.com',
    'mot_de_passe' => 'password123'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<div class='info'>";
echo "<h3>Réponse de l'API (HTTP $httpCode) :</h3>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";
echo "</div>";

$jsonResponse = json_decode($response, true);
if ($jsonResponse && isset($jsonResponse['success'])) {
    if ($jsonResponse['success']) {
        echo "<div class='success'>✅ ✅ ✅ L'API de connexion fonctionne parfaitement !</div>";
    } else {
        echo "<div class='error'>❌ L'API a retourné : " . $jsonResponse['message'] . "</div>";
    }
}
?>
