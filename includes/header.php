<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEBULA - Cloud Gaming</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">
                <img src="assets/img/logo_nebula.png" alt="Nebula Logo" class="logo-img">
                <span>Nebula</span>
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Fonctionnalités</a></li> <li><a href="jeux.php">Jeux</a></li>
            <li><a href="offres.php">Tarifs</a></li>
            <li><a href="boutique.php">Matériel</a></li>
            <li><a href="configurateur.php">Configurateur</a></li>
        </ul>

        <div class="nav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="panier.php" class="btn-icon">Panier</a>
                <a href="logout.php" class="btn-secondary">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary-nav">Commencer</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>