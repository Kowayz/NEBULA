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
                <img src="assets/img/logo-nebula.png" alt="Logo Nebula" class="logo-img">
                <span>NEBULA</span>
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="jeux.php">Jeux</a></li>
            <li><a href="offres.php">Nos Offres</a></li>
            <li><a href="boutique.php">Boutique</a></li>
            <li><a href="configurateur.php">Configurateur</a></li>
            <li><a href="faq.php">Support</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <div class="nav-actions">
            <a href="panier.php" class="cart-icon">
                <img src="assets/img/icon-cart.png" alt="Panier">
            </a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profil.php" class="btn-primary-nav">Mon Compte</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary-nav">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>