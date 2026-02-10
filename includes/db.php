<?php
// includes/db.php

$host = '127.0.0.1';
$port = '3307';  // Port MySQL de Laragon (3307 au lieu de 3306 par défaut)
$dbname = 'nebula_db';
$username = 'root';
$password = '';  // Mot de passe vide par défaut sur Laragon

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Message d'erreur plus détaillé pour le débogage
    die("❌ Erreur de connexion à la base de données<br><br>" . 
        "<strong>Message:</strong> " . $e->getMessage() . "<br><br>" .
        "<strong>Solutions possibles:</strong><br>" .
        "1. Vérifiez que MySQL est démarré dans Laragon<br>" .
        "2. Vérifiez que la base 'nebula_db' existe dans phpMyAdmin<br>" .
        "3. Importez le fichier sql/database.sql dans phpMyAdmin");
}
?>