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
    <li><a href="/NEBULA/demo.php"  <?= $currentPage==='demo'   ? 'class="active"':'' ?>>Démo</a></li>
    <li><a href="/NEBULA/faq.php"   <?= $currentPage==='faq'    ? 'class="active"':'' ?>>FAQ</a></li>
  </ul>

  <!-- Desktop actions -->
  <div class="navbar-actions" id="navActions">
    <a href="/NEBULA/panier.php" class="navbar-cart" aria-label="Panier">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
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

  <!-- Hamburger (mobile only) -->
  <button class="navbar-hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>

</nav>

<!-- Mobile panel -->
<div class="navbar-panel" id="navPanel">
  <ul class="navbar-panel-links">
    <li><a href="/NEBULA/jeux.php"  <?= $currentPage==='jeux'   ? 'class="active"':'' ?>>Jeux</a></li>
    <li><a href="/NEBULA/offres.php"<?= $currentPage==='offres' ? 'class="active"':'' ?>>Tarifs</a></li>
    <li><a href="/NEBULA/demo.php"  <?= $currentPage==='demo'   ? 'class="active"':'' ?>>Démo</a></li>
    <li><a href="/NEBULA/faq.php"   <?= $currentPage==='faq'    ? 'class="active"':'' ?>>FAQ</a></li>
  </ul>
  <div class="navbar-panel-actions">
    <a href="/NEBULA/panier.php" class="btn btn-ghost btn-sm" style="display:flex;align-items:center;gap:8px">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Panier<?php if (!empty($_SESSION['panier'])): ?> <span style="background:linear-gradient(135deg,#9f1239,#7c3aed);border-radius:10px;padding:1px 7px;font-size:.65rem;font-weight:700">1</span><?php endif; ?>
    </a>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="/NEBULA/dashboard.php" class="btn btn-outline btn-sm">Mon espace</a>
      <a href="?logout=1"             class="btn btn-ghost btn-sm">Déconnexion</a>
    <?php else: ?>
      <a href="/NEBULA/auth.php"              class="btn btn-ghost btn-sm">Se connecter</a>
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-sm btn-full">Commencer</a>
    <?php endif; ?>
  </div>
</div>

<div class="navbar-overlay" id="navOverlay"></div>

