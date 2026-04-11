<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Logout inline — plus besoin de logout.php
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: /NEBULA/index.php');
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');

$pageCSS = $pageCSS ?? [];
$pageJS  = $pageJS  ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Nebula' : 'Nebula — Cloud Gaming' ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'Jouez à vos jeux préférés en streaming haute qualité, sur tous vos appareils.') ?>">

  <link rel="icon" type="image/png" href="/NEBULA/public/assets/img/favicon.png">
  <link rel="apple-touch-icon" href="/NEBULA/public/assets/img/favicon.png">

  <link rel="stylesheet" href="/NEBULA/css/base.css">

  <?php foreach ($pageCSS as $sheet): ?>
  <link rel="stylesheet" href="/NEBULA/css/<?= htmlspecialchars($sheet) ?>.css">
  <?php endforeach; ?>
</head>
<body>

<nav class="navbar" id="navbar">

  <!-- Brand -->
  <a class="navbar-brand" href="/NEBULA/index.php">
    <img class="logo-icon" src="/NEBULA/public/assets/img/favicon.png" alt="Nebula">
    <span class="brand-name">Nebula</span>
  </a>

  <!-- Desktop nav links -->
  <ul class="navbar-links" id="navLinks">
    <li><a href="/NEBULA/jeux.php"  <?= $currentPage==='jeux'   ? 'class="active"':'' ?>>Jeux</a></li>
    <li><a href="/NEBULA/offres.php"<?= $currentPage==='offres' ? 'class="active"':'' ?>>Tarifs</a></li>
    <li><a href="/NEBULA/configurateur.php"<?= $currentPage==='configurateur' ? 'class="active"':'' ?>>Configurateur</a></li>
    <li><a href="/NEBULA/boutique.php"<?= $currentPage==='boutique'? 'class="active"':'' ?>>Boutique</a></li>
  </ul>

  <!-- Desktop actions -->
  <div class="navbar-actions" id="navActions">
    <a href="/NEBULA/panier.php" class="navbar-cart" aria-label="Panier">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="18" height="18" class="icon-img">
      <?php if (!empty($_SESSION['panier'])): ?>
        <span class="navbar-cart-dot"></span>
      <?php endif; ?>
    </a>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="/NEBULA/dashboard.php" class="btn btn-ghost btn-sm">Mon espace</a>
      <a href="?logout=1"             class="btn btn-outline btn-sm">Déconnexion</a>
    <?php else: ?>
      <a href="/NEBULA/auth.php"               class="btn btn-ghost btn-sm">Se connecter</a>
      <a href="/NEBULA/auth.php?tab=register"  class="btn btn-primary btn-sm">Commencer</a>
    <?php endif; ?>
  </div>

</nav>

