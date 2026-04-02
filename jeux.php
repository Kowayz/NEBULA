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
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M9 8h6M12 6v4"/></svg>
        +200 jeux inclus
      </span>
      <span class="catalogue-stat-sep"></span>
      <span class="catalogue-stat">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2"/></svg>
        4K · 144 FPS
      </span>
      <span class="catalogue-stat-sep"></span>
      <span class="catalogue-stat">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        Latence &lt; 20 ms
      </span>
    </div>
  </div>
</div>

<!-- ── Filter bar ────────────────────────────────────────────── -->
<div class="filter-bar">
  <div class="filter-genres" id="filterGenres">
    <button class="filter-btn active" data-genre="tous">Tous</button>
  </div>

  <div class="search-input-wrap">
    <span class="search-icon">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </span>
    <input type="text" id="searchInput" placeholder="Rechercher un jeu…">
  </div>
</div>

<!-- ── Catalogue grid ────────────────────────────────────────── -->
<div class="catalogue-section">
  <div class="cat-section-head">
    <div>
      <div class="cat-section-badge cat-section-badge--stream">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        Inclus dans l'abonnement
      </div>
    </div>
    <div>
      <div class="cat-section-title">Tous les jeux</div>
      <div class="cat-section-sub">Jouez instantanément, sans téléchargement</div>
    </div>
    <div class="cat-section-count"><span id="gameCount">…</span> jeux</div>
  </div>

  <div class="catalogue-grid" id="catalogueGrid">
    <!-- Skeleton cards -->
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
    <div class="catalogue-card-skeleton"></div>
  </div>

  <div id="noResults" class="no-results" style="display:none">
    Aucun jeu ne correspond à votre recherche.
  </div>
</div>

<?php require 'includes/footer.php'; ?>
