<?php
/**
 * Script pour corriger UNIQUEMENT le mot de passe admin
 * Sans toucher aux autres données
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Correction du mot de passe admin</h1>";
echo "<style>
    body { font-family: Arial; max-width: 800px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
    .success { background: #d4edda; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #28a745; }
    .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #dc3545; }
    .info { background: #cce5ff; color: #004085; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff; }
    .warning { background: #fff3cd; color: #856404; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #ffc107; }
    h1 { color: #F34792; }
</style>";

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->connect();

    echo "<div class='success'>✅ Connexion à la base réussie</div>";

    // Vérifier si le compte admin existe
    $stmt = $db->prepare("SELECT * FROM utilisatrice WHERE email = :email");
    $stmt->execute(['email' => 'admin@feminineaura.com']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "<div class='info'>📧 Compte admin trouvé : {$admin['email']}</div>";

        // Générer le nouveau hash pour 'password123'
        $newPassword = 'password123';
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

        echo "<div class='info'>🔐 Génération du nouveau hash...</div>";

        // Mettre à jour le mot de passe
        $updateStmt = $db->prepare("UPDATE utilisatrice SET mot_de_passe = :password WHERE email = :email");
        $updateStmt->execute([
            'password' => $newHash,
            'email' => 'admin@feminineaura.com'
        ]);

        echo "<div class='success'>✅ ✅ ✅ Mot de passe admin mis à jour avec succès !</div>";

        // Vérifier que ça fonctionne
        $verifyStmt = $db->prepare("SELECT * FROM utilisatrice WHERE email = :email");
        $verifyStmt->execute(['email' => 'admin@feminineaura.com']);
        $updatedAdmin = $verifyStmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($newPassword, $updatedAdmin['mot_de_passe'])) {
            echo "<div class='success'>✅ Vérification OK : Le mot de passe fonctionne !</div>";

            echo "<div class='info'>";
            echo "<h3>Vous pouvez maintenant vous connecter avec :</h3>";
            echo "<p><strong>Email :</strong> admin@feminineaura.com</p>";
            echo "<p><strong>Mot de passe :</strong> password123</p>";
            echo "<hr>";
            echo "<p><a href='http://localhost:3000/admin' style='display: inline-block; background: #F34792; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Aller à la page de connexion admin</a></p>";
            echo "</div>";
        } else {
            echo "<div class='error'>❌ Erreur : Le mot de passe ne correspond toujours pas</div>";
        }

    } else {
        echo "<div class='error'>❌ Aucun compte admin trouvé</div>";
        echo "<div class='warning'>";
        echo "<p>Le compte admin n'existe pas dans la base.</p>";
        echo "<p><strong>Solution :</strong> Vous devez exécuter <a href='install-db.php'>install-db.php</a> pour créer la base et les comptes</p>";
        echo "</div>";
    }

    // Mettre à jour aussi les comptes clients avec le même mot de passe
    echo "<hr>";
    echo "<h2>Mise à jour des comptes clients</h2>";

    $clientEmails = ['client1@example.com', 'client2@example.com'];
    foreach ($clientEmails as $clientEmail) {
        $stmt = $db->prepare("SELECT * FROM utilisatrice WHERE email = :email");
        $stmt->execute(['email' => $clientEmail]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $updateStmt = $db->prepare("UPDATE utilisatrice SET mot_de_passe = :password WHERE email = :email");
            $updateStmt->execute([
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'email' => $clientEmail
            ]);
            echo "<div class='success'>✅ Mot de passe mis à jour pour : $clientEmail</div>";
        }
    }

    echo "<div class='success'>";
    echo "<h2>🎉 Tous les mots de passe ont été réinitialisés !</h2>";
    echo "<p>Tous les comptes utilisent maintenant le mot de passe : <strong>password123</strong></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
