<?php
/**
 * Script d'installation automatique de la base de données
 * Accéder à : http://localhost/api/install-db.php
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Installation Base de Données - Feminine Aura</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #F34792; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🚀 Installation de la Base de Données Feminine Aura</h1>";

// Configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'feminine_aura';

try {
    // Connexion MySQL
    echo "<div class='info'>📡 Connexion à MySQL...</div>";
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='success'>✅ Connexion réussie !</div>";

    // Vérifier si la base existe avant suppression
    $checkDb = $pdo->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $dbExists = $checkDb->fetch();

    if ($dbExists) {
        echo "<div class='info'>🗑️ Base de données existante détectée, suppression en cours...</div>";
        $pdo->exec("DROP DATABASE $dbname");
        echo "<div class='success'>✅ Base '$dbname' supprimée !</div>";

        // Attendre que MySQL finalise la suppression
        usleep(500000); // 0.5 seconde
    } else {
        echo "<div class='info'>📋 Aucune base existante détectée</div>";
    }

    // Créer la base de données
    echo "<div class='info'>🗄️ Création de la base de données '$dbname'...</div>";
    $pdo->exec("CREATE DATABASE $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Fermer et rouvrir la connexion pour être sûr
    $pdo = null;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<div class='success'>✅ Base de données créée/sélectionnée !</div>";

    // Lire et exécuter le fichier 1_create_tables.sql
    echo "<div class='info'>📋 Création des tables...</div>";
    $sql1 = file_get_contents(__DIR__ . '/database/1_create_tables.sql');

    // Enlever les commentaires et lignes vides
    $sql1 = preg_replace('/^--.*$/m', '', $sql1);
    $sql1 = preg_replace('/^\s*$/m', '', $sql1);

    // Exécuter chaque requête
    $statements = explode(';', $sql1);
    $tableCount = 0;

    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && stripos($statement, 'CREATE TABLE') !== false) {
            $pdo->exec($statement);
            $tableCount++;
        } elseif (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "<div class='success'>✅ $tableCount tables créées avec succès !</div>";

    // Lire et exécuter le fichier 2_insert_data.sql
    echo "<div class='info'>📦 Insertion des données de test...</div>";
    $sql2 = file_get_contents(__DIR__ . '/database/2_insert_data.sql');

    // Enlever les commentaires
    $sql2 = preg_replace('/^--.*$/m', '', $sql2);

    // Enlever la ligne USE feminine_aura qui peut causer des problèmes
    $sql2 = preg_replace('/^USE\s+.*;$/m', '', $sql2);

    // Diviser par point-virgule mais garder les INSERT multi-lignes ensemble
    $sql2 = str_replace("\r\n", "\n", $sql2);

    // Vérifier que la base est bien vide avant insertion
    $checkStmt = $pdo->query("SELECT COUNT(*) as count FROM utilisatrice");
    $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($checkResult['count'] > 0) {
        echo "<div style='color: orange; padding: 15px; background: #fff3cd; border-radius: 5px; margin: 10px 0;'>
            ⚠️ ATTENTION : La base contient déjà {$checkResult['count']} utilisateurs.
            Les données de test ne seront PAS réinsérées pour éviter les doublons.
        </div>";
    } else {
        // Exécuter les insertions ligne par ligne avec meilleure gestion d'erreurs
        $statements = explode(';', $sql2);
        $insertCount = 0;
        $errorCount = 0;

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && strlen($statement) > 5) {
                try {
                    $pdo->exec($statement);
                    if (stripos($statement, 'INSERT') !== false) {
                        $insertCount++;
                    }
                } catch (PDOException $e2) {
                    // Ignorer UNIQUEMENT les doublons (erreur 23000)
                    if ($e2->getCode() == 23000) {
                        $errorCount++;
                    } else {
                        // Pour les autres erreurs, les remonter
                        throw $e2;
                    }
                }
            }
        }

        echo "<div class='success'>✅ $insertCount groupes de données insérés avec succès !</div>";
        if ($errorCount > 0) {
            echo "<div style='color: orange; padding: 10px; background: #fff3cd; border-radius: 5px;'>⚠️ $errorCount doublons ont été ignorés</div>";
        }
    }

    // Vérifications
    echo "<div class='info'>🔍 Vérification de l'installation...</div>";

    // Compter les tables
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '$dbname'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='success'>✅ Nombre de tables : <strong>{$result['count']}</strong></div>";

    // Compter les produits
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM produit");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='success'>✅ Nombre de produits : <strong>{$result['count']}</strong></div>";

    // Compter les utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM utilisatrice");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='success'>✅ Nombre d'utilisateurs : <strong>{$result['count']}</strong></div>";

    // Compter les catégories
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categorie");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='success'>✅ Nombre de catégories : <strong>{$result['count']}</strong></div>";

    // Liste des utilisateurs de test
    echo "<div class='info'>
        <h3>👥 Comptes de test créés :</h3>
        <pre>
<strong>Admin :</strong>
Email : admin@feminineaura.com
Mot de passe : password123

<strong>Client 1 :</strong>
Email : client1@example.com
Mot de passe : password123

<strong>Client 2 :</strong>
Email : client2@example.com
Mot de passe : password123
        </pre>
    </div>";

    echo "<div class='success'>
        <h2>🎉 Installation terminée avec succès !</h2>
        <p>Vous pouvez maintenant :</p>
        <ul>
            <li>✅ Tester l'API : <a href='/api/' target='_blank'>http://localhost/api/</a></li>
            <li>✅ Voir les produits : <a href='/api/produits' target='_blank'>http://localhost/api/produits</a></li>
            <li>✅ Accéder à phpMyAdmin : <a href='/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>
        </ul>
        <p><strong>⚠️ IMPORTANT :</strong> Pour des raisons de sécurité, supprimez ce fichier install-db.php après l'installation !</p>
    </div>";

} catch (PDOException $e) {
    echo "<div class='error'>
        <h3>❌ Erreur lors de l'installation</h3>
        <p><strong>Message :</strong> " . $e->getMessage() . "</p>
        <p><strong>Vérifiez que :</strong></p>
        <ul>
            <li>MySQL est démarré dans XAMPP</li>
            <li>Les identifiants sont corrects (user='root', password='')</li>
            <li>Les fichiers SQL existent dans /api/database/</li>
        </ul>
    </div>";
}

echo "</body></html>";
?>
