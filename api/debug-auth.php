<?php
/**
 * Script pour débugger EXACTEMENT ce qui se passe lors de l'inscription
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 Debug Authentification</h1>";
echo "<style>
    body { font-family: Arial; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
    .box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { background: #d4edda; border-left: 4px solid #28a745; }
    .error { background: #f8d7da; border-left: 4px solid #dc3545; }
    .info { background: #cce5ff; border-left: 4px solid #007bff; }
    .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
    pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
    h2 { color: #F34792; margin-top: 0; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; }
</style>";

// Simuler une requête d'inscription POST
echo "<div class='box info'>";
echo "<h2>📡 Simulation d'une requête d'inscription</h2>";
echo "<p>On va simuler exactement ce qui se passe quand vous cliquez sur 'S'inscrire'</p>";
echo "</div>";

// 1. Charger les fichiers
echo "<div class='box'>";
echo "<h2>Étape 1: Chargement des fichiers</h2>";

try {
    require_once __DIR__ . '/config/cors.php';
    echo "<div class='success'>✅ cors.php chargé</div>";

    require_once __DIR__ . '/config/database.php';
    echo "<div class='success'>✅ database.php chargé</div>";

    require_once __DIR__ . '/utils/Response.php';
    echo "<div class='success'>✅ Response.php chargé</div>";

    require_once __DIR__ . '/controllers/BaseController.php';
    echo "<div class='success'>✅ BaseController.php chargé</div>";

    require_once __DIR__ . '/controllers/AuthController.php';
    echo "<div class='success'>✅ AuthController.php chargé</div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur: " . $e->getMessage() . "</div>";
    die();
}

echo "</div>";

// 2. Connexion DB
echo "<div class='box'>";
echo "<h2>Étape 2: Connexion à la base de données</h2>";

try {
    $database = new Database();
    $db = $database->connect();
    echo "<div class='success'>✅ Connexion réussie</div>";
} catch (Exception $e) {
    echo "<div class='error'>❌ " . $e->getMessage() . "</div>";
    die();
}

echo "</div>";

// 3. Simuler les données POST
echo "<div class='box'>";
echo "<h2>Étape 3: Simulation des données POST</h2>";

$testEmail = 'debug-test-' . time() . '@example.com';
$testPassword = 'password123';

echo "<div class='info'>";
echo "Email: <code>$testEmail</code><br>";
echo "Password: <code>$testPassword</code>";
echo "</div>";

// Simuler $_SERVER pour POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';

// Simuler le body JSON
$jsonData = json_encode([
    'email' => $testEmail,
    'password' => $testPassword
]);

echo "<h3>Données JSON envoyées:</h3>";
echo "<pre>" . htmlspecialchars($jsonData) . "</pre>";

echo "</div>";

// 4. Créer le controller et capturer ce qui se passe
echo "<div class='box'>";
echo "<h2>Étape 4: Instanciation du AuthController</h2>";

try {
    // IMPORTANT: On capture l'output pour voir ce que le controller fait
    ob_start();

    $controller = new AuthController($db);

    echo "<div class='success'>✅ AuthController créé</div>";

    // Injecter les données dans php://input (simulation)
    // On ne peut pas vraiment le faire, donc on va regarder ce que parse le BaseController

    echo "<h3>Données parsées par BaseController:</h3>";

    // On va créer un petit test pour voir ce qui est parsé
    $reflection = new ReflectionClass($controller);
    $property = $reflection->getProperty('requestData');
    $property->setAccessible(true);
    $parsedData = $property->getValue($controller);

    echo "<pre>";
    print_r($parsedData);
    echo "</pre>";

    if (empty($parsedData)) {
        echo "<div class='warning'>⚠️ ATTENTION: requestData est VIDE! C'est probablement LE PROBLÈME!</div>";
        echo "<div class='info'>Le BaseController n'a pas pu parser les données JSON car php://input est vide dans ce script de test.</div>";
    }

    $output = ob_get_clean();
    echo $output;

} catch (Exception $e) {
    ob_end_clean();
    echo "<div class='error'>❌ " . $e->getMessage() . "</div>";
}

echo "</div>";

// 5. Test DIRECT de la méthode register
echo "<div class='box'>";
echo "<h2>Étape 5: Test DIRECT de l'inscription (sans passer par HTTP)</h2>";

try {
    require_once __DIR__ . '/models/BaseModel.php';
    require_once __DIR__ . '/models/Utilisatrice.php';

    $userModel = new Utilisatrice($db);

    echo "<div class='info'>Test d'inscription directe (contourne le parsing HTTP)</div>";

    // Vérifier si email existe
    if ($userModel->emailExists($testEmail)) {
        echo "<div class='warning'>⚠️ Email existe déjà</div>";
    } else {
        echo "<div class='success'>✅ Email disponible</div>";

        // Tenter l'inscription
        $userId = $userModel->register($testEmail, $testPassword, 'client');

        if ($userId) {
            echo "<div class='success' style='font-size: 18px;'>";
            echo "🎉 INSCRIPTION DIRECTE RÉUSSIE !<br>";
            echo "ID: $userId";
            echo "</div>";

            // Test de connexion
            $loginResult = $userModel->login($testEmail, $testPassword);

            if ($loginResult) {
                echo "<div class='success'>✅ Connexion avec ce compte fonctionne</div>";
            } else {
                echo "<div class='error'>❌ Connexion échoue</div>";
            }
        } else {
            echo "<div class='error'>❌ Inscription échouée</div>";
        }
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";

// 6. Test via CURL (requête HTTP réelle)
echo "<div class='box'>";
echo "<h2>Étape 6: Test via requête HTTP réelle (CURL)</h2>";

$curlTestEmail = 'curl-test-' . time() . '@example.com';
$curlData = json_encode([
    'email' => $curlTestEmail,
    'password' => 'password123'
]);

echo "<div class='info'>Test avec une vraie requête HTTP POST</div>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/Feminine%20Aura_last/api/auth/register');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($curlData)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "<h3>Requête envoyée:</h3>";
echo "<pre>POST /api/auth/register\nContent-Type: application/json\n\n" . htmlspecialchars($curlData) . "</pre>";

echo "<h3>Réponse de l'API (HTTP $httpCode):</h3>";

if ($curlError) {
    echo "<div class='error'>❌ Erreur CURL: $curlError</div>";
} else {
    echo "<pre>" . htmlspecialchars($response) . "</pre>";

    $jsonResponse = json_decode($response, true);

    if ($jsonResponse) {
        if (isset($jsonResponse['success']) && $jsonResponse['success']) {
            echo "<div class='success'>✅ L'API fonctionne! L'inscription par HTTP fonctionne!</div>";
        } else {
            echo "<div class='error'>";
            echo "❌ L'API a retourné une erreur:<br>";
            echo "<strong>Message:</strong> " . ($jsonResponse['message'] ?? 'Inconnu');
            echo "</div>";

            // C'est ICI qu'on voit probablement "Email ou mot de passe incorrect"
            if (isset($jsonResponse['message']) && strpos($jsonResponse['message'], 'Email ou mot de passe incorrect') !== false) {
                echo "<div class='warning'>";
                echo "<h3>🔍 PROBLÈME IDENTIFIÉ!</h3>";
                echo "<p>Le message 'Email ou mot de passe incorrect' vient de la fonction <code>login()</code> et non <code>register()</code>.</p>";
                echo "<p><strong>DIAGNOSTIC:</strong> Le routeur appelle probablement <code>login</code> au lieu de <code>register</code>!</p>";
                echo "<p><strong>Vérifiez:</strong></p>";
                echo "<ul>";
                echo "<li>L'URL appelée doit être: <code>/api/auth/register</code></li>";
                echo "<li>Pas <code>/api/auth/login</code></li>";
                echo "<li>Et surtout pas juste <code>/api/auth</code> (qui par défaut va sur login)</li>";
                echo "</ul>";
                echo "</div>";
            }
        }
    }
}

echo "</div>";

// 7. Diagnostic du routage
echo "<div class='box'>";
echo "<h2>Étape 7: Debug du routage</h2>";

echo "<div class='info'>";
echo "<h3>URLs à tester:</h3>";
echo "<ul>";
echo "<li>✅ CORRECT: <code>http://localhost/Feminine%20Aura_last/api/auth/register</code></li>";
echo "<li>❌ FAUX: <code>http://localhost/Feminine%20Aura_last/api/auth</code> (appelle login par défaut)</li>";
echo "<li>❌ FAUX: <code>http://localhost/Feminine%20Aura_last/api/auth/login</code></li>";
echo "</ul>";
echo "</div>";

echo "<h3>Test du parsing de l'URL:</h3>";

// Simuler différentes URLs
$testUrls = [
    '/Feminine Aura_last/api/auth/register',
    '/Feminine%20Aura_last/api/auth/register',
    '/Feminine Aura_last/api/auth',
    '/Feminine Aura_last/api/auth/login'
];

foreach ($testUrls as $testUrl) {
    $cleanedUri = preg_replace('#^/Feminine(%20| )Aura_last/api/?#', '', $testUrl);
    $cleanedUri = trim($cleanedUri, '/');
    $segments = explode('/', $cleanedUri);

    echo "<div style='margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 5px;'>";
    echo "<strong>URL:</strong> <code>$testUrl</code><br>";
    echo "<strong>Segments:</strong> <code>[" . implode(', ', $segments) . "]</code><br>";
    echo "<strong>Resource:</strong> <code>{$segments[0]}</code><br>";
    echo "<strong>Action:</strong> <code>" . ($segments[1] ?? 'null → login par défaut') . "</code>";

    if (!isset($segments[1]) || $segments[1] === '') {
        echo " <span style='color: red; font-weight: bold;'>⚠️ ATTENTION! Appelle login() au lieu de register()!</span>";
    }

    echo "</div>";
}

echo "</div>";

// Résumé final
echo "<div class='box warning'>";
echo "<h2>📋 RÉSUMÉ DU DIAGNOSTIC</h2>";
echo "<h3>Problème probable:</h3>";
echo "<p>Regardez l'Étape 6 ci-dessus. Si vous voyez 'Email ou mot de passe incorrect', cela signifie que:</p>";
echo "<ol>";
echo "<li>La fonction <code>login()</code> est appelée au lieu de <code>register()</code></li>";
echo "<li>Cela arrive quand l'URL ne contient pas <code>/register</code> à la fin</li>";
echo "<li>Vérifiez dans le code frontend que l'URL est bien: <code>auth/register</code> et pas juste <code>auth</code></li>";
echo "</ol>";
echo "</div>";

?>
