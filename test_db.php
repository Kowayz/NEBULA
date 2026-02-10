<?php
// Fichier de test pour vérifier la connexion à la base de données

echo "<h2>🔍 Test de connexion MySQL</h2>";

$host = '127.0.0.1';
$port = '3307';  // Port Laragon
$dbname = 'nebula_db';
$username = 'root';
$password = '';

echo "<p><strong>Configuration :</strong></p>";
echo "<ul>";
echo "<li>Host: $host</li>";
echo "<li>Port: $port</li>";
echo "<li>Database: $dbname</li>";
echo "<li>Username: $username</li>";
echo "<li>Password: " . (empty($password) ? "(vide)" : "(défini)") . "</li>";
echo "</ul>";

try {
    // Test 1: Connexion sans spécifier la base
    echo "<p>📡 <strong>Test 1:</strong> Connexion au serveur MySQL...</p>";
    $pdo_test = new PDO("mysql:host=$host;port=$port", $username, $password);
    echo "<p style='color: green;'>✅ Connexion au serveur MySQL réussie !</p>";
    
    // Test 2: Vérifier si la base existe
    echo "<p>📊 <strong>Test 2:</strong> Vérification de l'existence de la base '$dbname'...</p>";
    $stmt = $pdo_test->query("SHOW DATABASES LIKE '$dbname'");
    $db_exists = $stmt->fetch();
    
    if ($db_exists) {
        echo "<p style='color: green;'>✅ La base de données '$dbname' existe !</p>";
        
        // Test 3: Connexion à la base spécifique
        echo "<p>🔗 <strong>Test 3:</strong> Connexion à la base '$dbname'...</p>";
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<p style='color: green;'>✅ Connexion à la base '$dbname' réussie !</p>";
        
        // Test 4: Lister les tables
        echo "<p>📋 <strong>Test 4:</strong> Liste des tables dans '$dbname'...</p>";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (count($tables) > 0) {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucune table trouvée. Importez sql/database.sql !</p>";
        }
        
        echo "<h3 style='color: green;'>🎉 Tout fonctionne parfaitement !</h3>";
        echo "<p><a href='index.php'>→ Retour à l'accueil</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ La base de données '$dbname' n'existe pas !</p>";
        echo "<p><strong>Solution :</strong> Importez le fichier <code>sql/database.sql</code> dans phpMyAdmin</p>";
        echo "<p><a href='http://localhost/phpmyadmin' target='_blank'>→ Ouvrir phpMyAdmin</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<h3>🔐 Problème d'authentification MySQL</h3>";
        echo "<p><strong>Solutions possibles :</strong></p>";
        echo "<ol>";
        echo "<li>Vérifiez que MySQL est bien démarré dans Laragon</li>";
        echo "<li>Le mot de passe root pourrait ne pas être vide. Essayez avec le mot de passe 'root'</li>";
        echo "<li>Ouvrez phpMyAdmin et vérifiez les identifiants</li>";
        echo "</ol>";
    } else {
        echo "<p>Vérifiez que MySQL est démarré dans Laragon</p>";
    }
}
?>
