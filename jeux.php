<?php
/* ============================================================
   JEUX.PHP — Page catalogue / bibliothèque de jeux
   Récupère les jeux via l'API IGDB, les sépare en deux
   catégories (inclus dans l'abonnement / à acheter) et
   les affiche dans une grille de cartes.
   ============================================================ */

// -- Configuration de la page (titre, CSS, JS) --
$pageTitle = 'Bibliothèque de jeux';
$pageCSS   = ['jeux'];
$pageJS    = [];

// -- Charger l'API IGDB --
require_once 'api/igdb.php';

// -- Récupérer 120 jeux depuis l'API pour avoir 30 de chaque catégorie --
$games = igdb_get_games(120);

// -- Séparer les jeux : ID impair = inclus, ID pair = à acheter --
$jeuxInclus = [];
$jeuxAchat = [];
foreach ($games as $g) {
    if ($g['id_jeu'] % 2 === 1) {
        $jeuxInclus[] = $g; // Jeu inclus dans l'abonnement
    } else {
        $jeuxAchat[] = $g;  // Jeu à acheter séparément
    }
}

// -- Limiter à 30 jeux par catégorie --
$jeuxInclus = array_slice($jeuxInclus, 0, 30);
$jeuxAchat = array_slice($jeuxAchat, 0, 30);

// -- Attribuer des prix aux jeux à acheter (10 prix en rotation) --
$prixJeux = [29.99, 49.99, 19.99, 59.99, 39.99, 24.99, 44.99, 34.99, 14.99, 54.99];
foreach ($jeuxAchat as $i => $g) {
    $jeuxAchat[$i]['prix'] = $prixJeux[$i % 10]; // Prix cyclique selon l'index
}

// -- Inclure le header commun --
require 'includes/header.php';
?>

<!-- ── Hero du catalogue ────────────────────────────────────── -->
<div class="catalogue-hero">
  <div class="catalogue-hero-inner">
    <div class="catalogue-hero-tag">Catalogue</div>
    <h1 class="catalogue-hero-title">Bibliothèque de <span class="gradient-text">jeux</span></h1>
    <p class="catalogue-hero-sub">+200 jeux disponibles instantanément. Nouveautés ajoutées chaque mois.</p>
  </div>
</div>

<!-- ── Section : Jeux inclus dans l'abonnement ───────────────
     Ces jeux sont jouables directement avec un abonnement Nebula.
     Chaque carte affiche un badge "Inclus" et un bouton "Jouer".
     ──────────────────────────────────────────────────────────── -->
<div class="catalogue-section">
  <div class="cat-section-head">
    <div>
      <div class="cat-section-badge cat-section-badge--stream">
        <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
        Inclus dans l'abonnement
      </div>
    </div>
    <div class="cat-section-count"><?= count($jeuxInclus) ?> jeux</div>
  </div>

  <!-- Grille des cartes de jeux inclus -->
  <div class="catalogue-grid">
    <?php foreach ($jeuxInclus as $g): ?>
    <a href="/NEBULA/produit.php?id=<?= $g['id_jeu'] ?>" class="catalogue-card">
      <div class="catalogue-card-poster">
        <?php if (!empty($g['image_url'])): ?>
          <img src="<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['titre']) ?>">
        <?php else: ?>
          <div class="catalogue-card-placeholder"></div>
        <?php endif; ?>
      </div>
      <div class="catalogue-card-overlay">
        <div class="catalogue-card-title"><?= htmlspecialchars($g['titre']) ?></div>
        <div class="catalogue-play-btn">Jouer</div>
      </div>
      <!-- Badge "Inclus" en haut de la carte -->
      <div class="catalogue-included-badge">Inclus</div>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── Séparateur entre les deux sections ─────────────────── -->
<hr class="catalogue-sep">

<!-- ── Section : Jeux à acheter (à la carte) ─────────────────
     Ces jeux nécessitent un achat séparé.
     Chaque carte affiche un badge prix et un bouton "Acheter".
     ──────────────────────────────────────────────────────────── -->
<div class="catalogue-section">
  <div class="cat-section-head">
    <div>
      <div class="cat-section-badge cat-section-badge--purchase">
        <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="16" height="16" class="icon-img">
        A la carte
      </div>
    </div>
    <div class="cat-section-count"><?= count($jeuxAchat) ?> jeux</div>
  </div>

  <!-- Grille des cartes de jeux à acheter -->
  <div class="catalogue-grid">
    <?php foreach ($jeuxAchat as $g): ?>
    <a href="/NEBULA/produit.php?id=<?= $g['id_jeu'] ?>" class="catalogue-card">
      <div class="catalogue-card-poster">
        <?php if (!empty($g['image_url'])): ?>
          <img src="<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['titre']) ?>">
        <?php else: ?>
          <div class="catalogue-card-placeholder"></div>
        <?php endif; ?>
        <!-- Badge du prix affiché sur l'image -->
        <div class="catalogue-price-badge"><?= number_format($g['prix'], 2) ?> €</div>
      </div>
      <div class="catalogue-card-overlay">
        <div class="catalogue-card-title"><?= htmlspecialchars($g['titre']) ?></div>
        <div class="catalogue-buy-btn">Acheter</div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Message d'erreur si l'API ne retourne aucun jeu -->
  <?php if (empty($games)): ?>
  <p style="text-align:center;color:var(--text-muted);padding:3rem 0;">Impossible de charger le catalogue. Réessayez plus tard.</p>
  <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
