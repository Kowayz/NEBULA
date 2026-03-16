<?php
$pageTitle = 'Démo — Essayez Nebula';
$pageCSS   = ['demo'];
$pageJS    = [];
require 'includes/header.php';
?>

<main class="demo-main">

<!-- ══════════════════════════ HERO ══════════════════════════ -->
<section class="demo-hero">
  <div class="demo-hero-bg">
    <div class="demo-hero-orb demo-orb-1"></div>
    <div class="demo-hero-orb demo-orb-2"></div>
    <div class="demo-hero-orb demo-orb-3"></div>
    <div class="demo-hero-grid"></div>
  </div>

  <div class="demo-hero-content">
    <div class="demo-hero-tag">Démo interactive</div>
    <h1 class="demo-hero-title">Jouez en <span class="demo-gradient-text">30 secondes</span></h1>
    <p class="demo-hero-sub">Aucune installation, aucune carte bancaire. Lancez votre première session de cloud gaming directement depuis votre navigateur.</p>

    <div class="demo-stats-bar">
      <div class="demo-stat">
        <span class="demo-stat-value">&lt; 20<span class="demo-hud-unit">ms</span></span>
        <span class="demo-stat-label">Latence</span>
      </div>
      <div class="demo-stat">
        <span class="demo-stat-value">4K</span>
        <span class="demo-stat-label">Résolution</span>
      </div>
      <div class="demo-stat">
        <span class="demo-stat-value">144<span class="demo-hud-unit">fps</span></span>
        <span class="demo-stat-label">Fluidité</span>
      </div>
      <div class="demo-stat">
        <span class="demo-stat-value">+200</span>
        <span class="demo-stat-label">Jeux</span>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════ BROWSER PREVIEW ══════════════════════════ -->
<div class="demo-preview-section">
  <div class="demo-browser">

    <!-- Top bar -->
    <div class="demo-browser-bar">
      <div class="demo-browser-dots">
        <span class="demo-dot demo-dot-red"></span>
        <span class="demo-dot demo-dot-yellow"></span>
        <span class="demo-dot demo-dot-green"></span>
      </div>
      <div class="demo-browser-url">
        <span class="demo-url-icon">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </span>
        nebula.gg/play/elden-ring
      </div>
      <div class="demo-browser-pills">
        <span class="demo-pill">4K · 144 FPS</span>
        <span class="demo-pill demo-pill-green">En ligne</span>
      </div>
    </div>

    <!-- Game viewport -->
    <div class="demo-viewport">
      <div class="demo-game-scene">
        <div class="demo-scene-layer demo-scene-sky"></div>
        <div class="demo-scene-layer demo-scene-mid"></div>
        <div class="demo-scene-layer demo-scene-fg"></div>
        <div class="demo-scene-layer demo-scene-scanlines"></div>
        <div class="demo-scene-layer demo-scene-vignette"></div>
      </div>

      <!-- HUD top-left -->
      <div class="demo-hud demo-hud-tl">
        <div class="demo-hud-pill">
          <span class="demo-hud-dot"></span>
          Connecté — Paris
        </div>
      </div>

      <!-- HUD top-right -->
      <div class="demo-hud demo-hud-tr">
        <div class="demo-hud-stat">
          <span class="demo-hud-stat-val demo-val-green">12<span class="demo-hud-unit">ms</span></span>
          <span class="demo-hud-stat-key">LATENCE</span>
        </div>
        <div class="demo-hud-stat">
          <span class="demo-hud-stat-val">144<span class="demo-hud-unit">fps</span></span>
          <span class="demo-hud-stat-key">FPS</span>
        </div>
        <div class="demo-hud-stat">
          <span class="demo-hud-stat-val">4K</span>
          <span class="demo-hud-stat-key">QUALITÉ</span>
        </div>
      </div>

      <!-- HUD bottom-left -->
      <div class="demo-hud demo-hud-bl">
        <div class="demo-hud-bar">
          <svg class="demo-hud-bar-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M9 8h6M12 6v4"/></svg>
          DualSense détecté
        </div>
      </div>

      <!-- Play button overlay -->
      <div class="demo-play-center">
        <div class="demo-play-rings">
          <div class="demo-play-ring demo-ring-1"></div>
          <div class="demo-play-ring demo-ring-2"></div>
        </div>
        <a href="/NEBULA/auth.php?tab=register" class="demo-play-btn">
          <svg class="demo-play-icon" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        </a>
        <span class="demo-play-label">Démarrer la démo</span>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="demo-browser-bottom">
      <div class="demo-bottom-info">
        <span class="demo-bottom-dot"></span>
        <span class="demo-bottom-game">Elden Ring</span>
        <span class="demo-bottom-genre">Action-RPG · FromSoftware</span>
      </div>
      <div class="demo-bottom-actions">
        <div class="demo-icon-btn" title="Paramètres">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
        </div>
        <div class="demo-icon-btn" title="Plein écran">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
        </div>
        <div class="demo-icon-btn" title="Son">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 010 14.14M15.54 8.46a5 5 0 010 7.07"/></svg>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ══════════════════════════ STEPS ══════════════════════════ -->
<section class="demo-steps-section">
  <div class="demo-steps-inner">
    <div class="demo-section-tag">Comment démarrer</div>
    <h2 class="demo-section-title">En 3 étapes, vous jouez</h2>

    <div class="demo-steps-row">
      <?php
      $steps = [
        [
          'title' => 'Créer un compte',
          'desc'  => "Inscrivez-vous en moins d'une minute avec votre e-mail. Aucune carte bancaire requise pour commencer.",
          'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>',
        ],
        [
          'title' => 'Choisir un jeu',
          'desc'  => 'Parcourez +200 titres disponibles instantanément. Filtrez par genre, popularité ou nouveautés.',
          'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M9 8h6M12 6v4"/></svg>',
        ],
        [
          'title' => 'Jouer immédiatement',
          'desc'  => "Le jeu démarre en quelques secondes dans votre navigateur. Aucun téléchargement, aucune installation.",
          'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
        ],
      ];
      foreach ($steps as $i => $step): ?>
      <div class="demo-step">
        <?php if ($i < count($steps) - 1): ?>
          <div class="demo-step-line"></div>
        <?php endif; ?>
        <div class="demo-step-num"><?= $i + 1 ?></div>
        <div class="demo-step-icon"><?= $step['icon'] ?></div>
        <h3 class="demo-step-title"><?= htmlspecialchars($step['title']) ?></h3>
        <p class="demo-step-desc"><?= htmlspecialchars($step['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════ CTA ══════════════════════════ -->
<section class="demo-cta">
  <div class="demo-cta-glow"></div>
  <div class="demo-cta-grid"></div>
  <div class="demo-cta-content">
    <h2 class="demo-cta-title">Prêt pour votre <span class="demo-gradient-text">première session</span> ?</h2>
    <p class="demo-cta-sub">Rejoignez des milliers de joueurs qui jouent déjà sans télécharger quoi que ce soit.</p>
    <div class="demo-cta-actions">
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        Commencer gratuitement
      </a>
      <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Parcourir le catalogue</a>
    </div>
  </div>
</section>

</main>

<?php require 'includes/footer.php'; ?>
