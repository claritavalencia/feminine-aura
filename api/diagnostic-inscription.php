<?php
/**
 * Script de diagnostic COMPLET pour l'inscription
 * Affiche TOUTES les erreurs en détail
 */

// Activer TOUS les logs d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Inscription - Feminine Aura</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success {
            color: #155724;
            background: #d4edda;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            color: #721c24;
            background: #f8d7da;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            color: #004085;
            background: #cce5ff;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #007bff;
        }
        .warning {
            color: #856404;
            background: #fff3cd;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #ffc107;
        }
        h1 {
            color: #F34792;
            border-bottom: 3px solid #F34792;
            padding-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-top: 0;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .step {
            font-weight: bold;
            color: #F34792;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic Complet d'Inscription</h1>

<?php

echo '<div class="section">';
echo '<h2><span class="step">Étape 1:</span> Configuration PHP</h2>';

// Vérifier PHP
$phpVersion = phpversion();
echo "<div class='info'>📌 Version PHP: <strong>$phpVersion</strong></div>";

// Extensions requises
$extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
$allExtOk = true;
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='success'>✅ Extension <code>$ext</code> : Activée</div>";
    } else {
        echo "<div class='error'>❌ Extension <code>$ext</code> : MANQUANTE</div>";
        $allExtOk = false;
    }
}

echo '</div>';

// Connexion à la base
echo '<div class="section">';
echo '<h2><span class="step">Étape 2:</span> Connexion Base de Données</h2>';

try {
    require_once __DIR__ . '/config/database.php';

    echo "<div class='info'>📁 Chargement du fichier database.php...</div>";

    $database = new Database();
    echo "<div class='success'>✅ Classe Database instanciée</div>";

    $db = $database->connect();
    echo "<div class='success'>✅ Connexion à MySQL réussie</div>";

    // Vérifier la base de données
    $stmt = $db->query("SELECT DATABASE() as dbname");
    $dbInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='info'>📊 Base de données active: <strong>{$dbInfo['dbname']}</strong></div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ ERREUR DE CONNEXION:<br>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Fichier:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Ligne:</strong> " . $e->getLine() . "<br>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
    die('</div></body></html>');
}

echo '</div>';

// Vérifier la table utilisatrice
echo '<div class="section">';
echo '<h2><span class="step">Étape 3:</span> Structure de la Table</h2>';

try {
    $stmt = $db->query("SHOW TABLES LIKE 'utilisatrice'");

    if ($stmt->rowCount() === 0) {
        echo "<div class='error'>❌ La table 'utilisatrice' n'existe PAS !</div>";
        echo "<div class='warning'>⚠️ Veuillez exécuter: <a href='install-db.php' target='_blank'>install-db.php</a></div>";
        die('</div></body></html>');
    }

    echo "<div class='success'>✅ Table 'utilisatrice' existe</div>";

    // Afficher la structure
    $stmt = $db->query("DESCRIBE utilisatrice");
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Structure de la table:</h3>";
    echo "<table style='width:100%; border-collapse: collapse;'>";
    echo "<tr style='background: #f8f9fa; font-weight: bold;'>";
    echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>Champ</td>";
    echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>Type</td>";
    echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>Null</td>";
    echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>Clé</td>";
    echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>Défaut</td>";
    echo "</tr>";

    foreach ($structure as $field) {
        echo "<tr>";
        echo "<td style='padding: 8px; border: 1px solid #dee2e6;'><code>{$field['Field']}</code></td>";
        echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>{$field['Type']}</td>";
        echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>{$field['Null']}</td>";
        echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>{$field['Key']}</td>";
        echo "<td style='padding: 8px; border: 1px solid #dee2e6;'>{$field['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Compter les utilisateurs
    $stmt = $db->query("SELECT COUNT(*) as count FROM utilisatrice");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='info'>📊 Nombre d'utilisateurs existants: <strong>{$count['count']}</strong></div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ ERREUR: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo '</div>';

// Test du modèle
echo '<div class="section">';
echo '<h2><span class="step">Étape 4:</span> Test du Modèle Utilisatrice</h2>';

try {
    require_once __DIR__ . '/models/BaseModel.php';
    require_once __DIR__ . '/models/Utilisatrice.php';

    echo "<div class='success'>✅ Fichiers de modèle chargés</div>";

    $userModel = new Utilisatrice($db);
    echo "<div class='success'>✅ Instance du modèle créée</div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ ERREUR lors du chargement du modèle:<br>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
    die('</div></body></html>');
}

echo '</div>';

// TEST D'INSCRIPTION RÉEL
echo '<div class="section">';
echo '<h2><span class="step">Étape 5:</span> Test d\'Inscription Réel</h2>';

$testEmail = 'test-diagnostic-' . time() . '@example.com';
$testPassword = 'TestPassword123';

echo "<div class='info'>";
echo "📝 Tentative d'inscription avec:<br>";
echo "<strong>Email:</strong> $testEmail<br>";
echo "<strong>Mot de passe:</strong> $testPassword<br>";
echo "<strong>Longueur mot de passe:</strong> " . strlen($testPassword) . " caractères";
echo "</div>";

try {
    // Étape 1: Vérifier si l'email existe
    echo "<h3>5.1 - Vérification de l'email</h3>";
    $exists = $userModel->emailExists($testEmail);

    if ($exists) {
        echo "<div class='warning'>⚠️ L'email existe déjà (normal si vous exécutez ce script plusieurs fois)</div>";
    } else {
        echo "<div class='success'>✅ L'email est disponible</div>";
    }

    // Étape 2: Tenter l'inscription
    echo "<h3>5.2 - Appel de la méthode register()</h3>";
    echo "<div class='info'>Appel: <code>\$userModel->register('$testEmail', '***', 'client')</code></div>";

    $userId = $userModel->register($testEmail, $testPassword, 'client');

    if ($userId) {
        echo "<div class='success' style='font-size: 18px;'>";
        echo "🎉 🎉 🎉 INSCRIPTION RÉUSSIE !<br>";
        echo "ID de l'utilisateur créé: <strong>$userId</strong>";
        echo "</div>";

        // Étape 3: Récupérer l'utilisateur
        echo "<h3>5.3 - Récupération de l'utilisateur créé</h3>";
        $user = $userModel->getById($userId);

        if ($user) {
            echo "<div class='success'>✅ Utilisateur récupéré avec succès</div>";
            unset($user['mot_de_passe']); // Ne pas afficher le hash
            echo "<pre>" . print_r($user, true) . "</pre>";

            // Étape 4: Test de connexion
            echo "<h3>5.4 - Test de connexion</h3>";
            $loginResult = $userModel->login($testEmail, $testPassword);

            if ($loginResult) {
                echo "<div class='success' style='font-size: 18px;'>";
                echo "✅ ✅ ✅ CONNEXION RÉUSSIE !<br>";
                echo "L'utilisateur peut se connecter avec ses identifiants.";
                echo "</div>";
                echo "<pre>" . print_r($loginResult, true) . "</pre>";
            } else {
                echo "<div class='error'>❌ Échec de la connexion (problème de hash de mot de passe?)</div>";
            }
        } else {
            echo "<div class='error'>❌ Impossible de récupérer l'utilisateur créé</div>";
        }

    } else {
        echo "<div class='error'>❌ La méthode register() a retourné FALSE ou 0</div>";
        echo "<div class='warning'>Cela peut indiquer un problème avec la méthode create() du BaseModel</div>";
    }

} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "❌ ERREUR PDO (Base de données):<br>";
    echo "<strong>Code:</strong> " . $e->getCode() . "<br>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Fichier:</strong> " . $e->getFile() . " (ligne " . $e->getLine() . ")<br>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";

    // Erreurs spécifiques
    if ($e->getCode() == 23000) {
        echo "<div class='warning'>⚠️ Cette erreur indique généralement un doublon (email déjà utilisé)</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "❌ ERREUR GÉNÉRALE:<br>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Fichier:</strong> " . $e->getFile() . " (ligne " . $e->getLine() . ")<br>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo '</div>';

// Résumé final
echo '<div class="section">';
echo '<h2>📋 Résumé et Prochaines Étapes</h2>';

if ($allExtOk && isset($userId) && $userId) {
    echo "<div class='success' style='font-size: 16px;'>";
    echo "<h3>✅ Tout fonctionne parfaitement !</h3>";
    echo "<p>Le système d'inscription est opérationnel. Si vous avez toujours des problèmes depuis le frontend:</p>";
    echo "<ol>";
    echo "<li>Vérifiez que vous utilisez bien l'onglet <strong>\"Inscription\"</strong> et non \"Connexion\"</li>";
    echo "<li>Ouvrez la console du navigateur (F12) pour voir les erreurs JavaScript</li>";
    echo "<li>Vérifiez l'onglet Network pour voir la requête envoyée à l'API</li>";
    echo "<li>Assurez-vous que le serveur XAMPP est bien démarré</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ Il y a un problème</h3>";
    echo "<p>Consultez les erreurs ci-dessus pour identifier le problème.</p>";
    echo "</div>";
}

echo "<div class='info'>";
echo "<h3>🧪 Pour tester manuellement l'API:</h3>";
echo "<p>Utilisez cURL ou Postman avec cette requête:</p>";
echo "<pre>";
echo "curl -X POST \"http://localhost/Feminine%20Aura_last/api/auth/register\" \\\n";
echo "  -H \"Content-Type: application/json\" \\\n";
echo "  -d '{\"email\":\"nouveau@example.com\",\"password\":\"password123\"}'";
echo "</pre>";
echo "</div>";

echo '</div>';

?>

</body>
</html>
