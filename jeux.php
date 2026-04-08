<?php
$pageTitle = 'Bibliothèque de jeux';
$pageCSS   = ['jeux'];
$pageJS    = ['catalogue'];
require 'includes/header.php';
?>

<!-- ── Hero ──────────────────────────────────────────────────── -->
<div class="catalogue-hero">
  <div class="catalogue-hero-orb catalogue-hero-orb-a"></div>
  <div class="catalogue-hero-orb catalogue-hero-orb-b"></div>
  <div class="catalogue-hero-inner">
    <div class="catalogue-hero-tag">Catalogue</div>
    <h1 class="catalogue-hero-title">Bibliothèque de <span class="gradient-text">jeux</span></h1>
    <p class="catalogue-hero-sub">+200 jeux disponibles instantanément. Nouveautés ajoutées chaque mois, sans téléchargement.</p>
    <div class="catalogue-hero-stats">
      <span class="catalogue-stat">
        <img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">
        +200 jeux inclus
      </span>
      <span class="catalogue-stat-sep"></span>
      <span class="catalogue-stat">
        <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="20" height="20" class="icon-img">
        4K · 144 FPS
      </span>
      <span class="catalogue-stat-sep"></span>
      <span class="catalogue-stat">
        <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="22" height="22" class="icon-img">
        Latence &lt; 20 ms
      </span>
    </div>
  </div>
</div>

<!-- ── Filter bar ────────────────────────────────────────────── -->
<div class="filter-bar">
  <div class="filter-select-wrap">
    <select id="filterGenres" class="filter-select">
      <option value="tous">Tous les jeux</option>
      <option value="action">Action & Aventure</option>
      <option value="rpg">RPG & Stratégie</option>
      <option value="shooter">FPS & Shooter</option>
      <option value="course">Course & Sports</option>
      <option value="simulation">Simulation</option>
    </select>
  </div>

  <div class="search-input-wrap">
    <span class="search-icon">
      <img src="/NEBULA/public/assets/img/icons/nav/loupe.png" alt="icon" width="16" height="16" class="icon-img" style="opacity:0.7">
    </span>
    <input type="text" id="searchInput" placeholder="Rechercher un jeu…">
  </div>
</div>

<!-- ── Catalogue grid ────────────────────────────────────────── -->
<div class="catalogue-section">
  <!-- Section Inclus -->
  <div id="sectionIncluded">
    <div class="cat-section-head">
      <div>
        <div class="cat-section-badge cat-section-badge--stream">
          <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
          Inclus dans l'abonnement
        </div>
      </div>
      <div>
        <div class="cat-section-title">Tous les jeux</div>
        <div class="cat-section-sub">Jouez instantanément, sans téléchargement</div>
      </div>
      <div class="cat-section-count"><span id="countIncluded">…</span> jeux</div>
    </div>

    <div class="catalogue-grid" id="gridIncluded">
      <!-- Skeleton cards -->
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
    </div>
  </div>

  <hr id="catDivider" style="border:none; border-top:1px solid var(--border); margin: 3rem 0;">

  <!-- Section Achat -->
  <div id="sectionPurchase">
    <div class="cat-section-head">
      <div>
        <div class="cat-section-badge" style="color:var(--text-muted); border-color:var(--border);">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="16" height="16" class="icon-img" style="opacity:0.7">
          À l'achat
        </div>
      </div>
      <div>
        <div class="cat-section-title">Boutique Nebula</div>
      </div>
      <div class="cat-section-count"><span id="countPurchase">…</span> jeux</div>
    </div>

    <div class="catalogue-grid catalogue-grid--purchase" id="gridPurchase">
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
      <div class="catalogue-card-skeleton"></div>
    </div>
  </div>

  <div id="noResults" class="no-results" style="display:none">
    Aucun jeu ne correspond à votre recherche.
  </div>
</div>

<?php require 'includes/footer.php'; ?>
