<?php
// includes/db.php

$host = 'localhost';
$dbname = 'nebula_db';
$username = 'root'; // Par défaut sur WAMP/XAMPP
$password = '';     // Par défaut sur WAMP/XAMPP (parfois 'root' sur MAMP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // On active les erreurs pour le débogage (indispensable en dev)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>