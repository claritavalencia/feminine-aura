<?php
/**
 * Script de test pour diagnostiquer les problèmes d'inscription
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test d'inscription - Feminine Aura</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #F34792; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .test-section { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic du système d'inscription</h1>";

echo "<div class='test-section'>";
echo "<h2>1️⃣ Vérification de la configuration</h2>";

// Test 1: Vérifier les extensions PHP
echo "<h3>Extensions PHP:</h3>";
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='success'>✅ Extension '$ext' : activée</div>";
    } else {
        echo "<div class='error'>❌ Extension '$ext' : MANQUANTE</div>";
    }
}

// Test 2: Vérifier le fichier .env
echo "<h3>Configuration .env:</h3>";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "<div class='success'>✅ Fichier .env trouvé</div>";
    $envContent = file_get_contents($envFile);
    echo "<pre>" . htmlspecialchars($envContent) . "</pre>";
} else {
    echo "<div class='error'>❌ Fichier .env non trouvé</div>";
}

echo "</div>";

// Test 3: Connexion à la base de données
echo "<div class='test-section'>";
echo "<h2>2️⃣ Test de connexion à la base de données</h2>";

try {
    require_once __DIR__ . '/config/database.php';
    $database = new Database();
    $db = $database->connect();
    echo "<div class='success'>✅ Connexion à la base de données réussie</div>";

    // Vérifier la table utilisatrice
    $stmt = $db->query("SHOW TABLES LIKE 'utilisatrice'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✅ Table 'utilisatrice' existe</div>";

        // Afficher la structure
        $stmt = $db->query("DESCRIBE utilisatrice");
        $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Structure de la table utilisatrice:</h3>";
        echo "<pre>" . print_r($structure, true) . "</pre>";

        // Compter les utilisateurs
        $stmt = $db->query("SELECT COUNT(*) as count FROM utilisatrice");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='info'>📊 Nombre d'utilisateurs dans la base : {$count['count']}</div>";

        // Lister les emails existants
        $stmt = $db->query("SELECT id_utilisatrice, email, role, date_creation FROM utilisatrice ORDER BY date_creation DESC LIMIT 5");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($users) > 0) {
            echo "<h3>Derniers utilisateurs:</h3>";
            echo "<pre>" . print_r($users, true) . "</pre>";
        }
    } else {
        echo "<div class='error'>❌ Table 'utilisatrice' n'existe pas</div>";
        echo "<div class='warning'>⚠️ Exécutez le script install-db.php pour créer la base de données</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div>";

// Test 4: Test du modèle Utilisatrice
echo "<div class='test-section'>";
echo "<h2>3️⃣ Test du modèle Utilisatrice</h2>";

try {
    require_once __DIR__ . '/models/Utilisatrice.php';
    $userModel = new Utilisatrice($db);
    echo "<div class='success'>✅ Modèle Utilisatrice chargé</div>";

    // Test: vérifier si un email existe
    $testEmail = 'test-diagnostic-' . time() . '@example.com';
    $exists = $userModel->emailExists($testEmail);
    echo "<div class='info'>📧 Test emailExists('$testEmail') : " . ($exists ? "existe" : "n'existe pas") . "</div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur lors du chargement du modèle : " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div>";

// Test 5: Test d'inscription complète
echo "<div class='test-section'>";
echo "<h2>4️⃣ Test d'inscription complète</h2>";

$testEmail = 'test-inscription-' . time() . '@example.com';
$testPassword = 'TestPassword123!';

echo "<div class='info'>📝 Tentative d'inscription avec:<br>
Email: $testEmail<br>
Mot de passe: $testPassword</div>";

try {
    // Vérifier si l'email existe déjà
    if ($userModel->emailExists($testEmail)) {
        echo "<div class='warning'>⚠️ L'email existe déjà dans la base</div>";
    } else {
        echo "<div class='success'>✅ L'email est disponible</div>";

        // Tenter l'inscription
        $userId = $userModel->register($testEmail, $testPassword, 'client');

        if ($userId) {
            echo "<div class='success'>✅ INSCRIPTION RÉUSSIE ! ID: $userId</div>";

            // Vérifier que l'utilisateur est bien créé
            $newUser = $userModel->getById($userId);
            echo "<h3>Utilisateur créé:</h3>";
            unset($newUser['mot_de_passe']); // Ne pas afficher le mot de passe hashé
            echo "<pre>" . print_r($newUser, true) . "</pre>";

            // Tester la connexion
            echo "<h3>Test de connexion:</h3>";
            $loginResult = $userModel->login($testEmail, $testPassword);
            if ($loginResult) {
                echo "<div class='success'>✅ CONNEXION RÉUSSIE !</div>";
                echo "<pre>" . print_r($loginResult, true) . "</pre>";
            } else {
                echo "<div class='error'>❌ Échec de la connexion</div>";
            }

        } else {
            echo "<div class='error'>❌ L'inscription a échoué (retour: false/0)</div>";
        }
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur lors de l'inscription : " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<div class='error'>Stack trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></div>";
}

echo "</div>";

// Test 6: Test via l'API
echo "<div class='test-section'>";
echo "<h2>5️⃣ Test via l'API REST</h2>";

$apiTestEmail = 'api-test-' . time() . '@example.com';
$apiTestPassword = 'ApiTest123!';

echo "<div class='info'>📡 Test de l'endpoint /api/auth/register</div>";

// Simuler une requête POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];

// Données à envoyer
$postData = json_encode([
    'email' => $apiTestEmail,
    'password' => $apiTestPassword
]);

echo "<h3>Données envoyées:</h3>";
echo "<pre>" . htmlspecialchars($postData) . "</pre>";

// Capturer la sortie de l'API
ob_start();

try {
    // Simuler l'appel API
    $ch = curl_init();
    $apiUrl = 'http://localhost/Feminine%20Aura_last/api/auth/register';

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "<h3>Réponse de l'API (Code HTTP: $httpCode):</h3>";

    if ($error) {
        echo "<div class='error'>❌ Erreur CURL : " . htmlspecialchars($error) . "</div>";
    } else {
        echo "<pre>" . htmlspecialchars($response) . "</pre>";

        $jsonResponse = json_decode($response, true);
        if ($jsonResponse) {
            if (isset($jsonResponse['success']) && $jsonResponse['success']) {
                echo "<div class='success'>✅ L'API fonctionne correctement !</div>";
            } else {
                echo "<div class='error'>❌ L'API a retourné une erreur : " .
                     htmlspecialchars($jsonResponse['message'] ?? 'Erreur inconnue') . "</div>";
            }
        }
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur lors du test API : " . htmlspecialchars($e->getMessage()) . "</div>";
}

ob_end_clean();

echo "</div>";

// Résumé final
echo "<div class='test-section'>";
echo "<h2>📋 Résumé</h2>";
echo "<div class='info'>
    <h3>Pour tester manuellement l'inscription:</h3>
    <ol>
        <li>Utilisez Postman ou curl pour envoyer une requête POST à:<br>
            <code>http://localhost/Feminine%20Aura_last/api/auth/register</code></li>
        <li>Avec le body JSON:<br>
            <pre>{
  \"email\": \"nouveau@example.com\",
  \"password\": \"motdepasse123\"
}</pre></li>
        <li>Header: <code>Content-Type: application/json</code></li>
    </ol>

    <h3>Commande CURL pour tester:</h3>
    <pre>curl -X POST http://localhost/Feminine%20Aura_last/api/auth/register \\
  -H \"Content-Type: application/json\" \\
  -d '{\"email\":\"test@example.com\",\"password\":\"password123\"}'</pre>
</div>";

echo "</div>";

echo "</body></html>";
?>
