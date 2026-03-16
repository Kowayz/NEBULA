<?php
require 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Mon Bouquet';
$pageCSS   = ['configurateur'];
$pageJS    = ['configurateur'];

require 'includes/header.php';
?>

<div class="config-page">

<!-- ── Hero ──────────────────────────────────────────────────── -->
<div class="config-hero">
  <div class="config-hero-orb config-hero-orb-a"></div>
  <div class="config-hero-orb config-hero-orb-b"></div>
  <div class="config-hero-inner">
    <div class="config-hero-tag">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
      Configurateur
    </div>
    <h1 class="config-hero-title">Composez votre <span class="gradient-text">bouquet</span></h1>
    <p class="config-hero-sub">Payez uniquement pour ce que vous utilisez. Sélectionnez vos genres favoris, votre qualité de streaming et les options qui vous correspondent.</p>
  </div>
</div>

<!-- ── Main layout ───────────────────────────────────────────── -->
<div class="config-layout">
  <div class="config-steps-col">

    <!-- ── Step 1 : Genres ───────────────────────────────────── -->
    <div class="config-step-card">
      <div class="config-step-header">
        <div class="config-step-num">1</div>
        <div>
          <div class="config-step-title">Vos genres favoris</div>
          <div class="config-step-sub">Sélectionnez au moins un genre pour personnaliser votre catalogue</div>
        </div>
        <div class="config-step-count">0 / 10</div>
      </div>

      <div class="merch-select-grid" id="genreGrid">
        <?php
        $genres = [
          ['id'=>'action',    'label'=>'Action',    'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 17.5L3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M2 15l7 7"/></svg>'],
          ['id'=>'rpg',       'label'=>'RPG',       'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'],
          ['id'=>'fps',       'label'=>'FPS',       'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>'],
          ['id'=>'sport',     'label'=>'Sport',     'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M4.93 4.93l14.14 14.14"/></svg>'],
          ['id'=>'course',    'label'=>'Course',    'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2h-2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>'],
          ['id'=>'aventure',  'label'=>'Aventure',  'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>'],
          ['id'=>'strategie', 'label'=>'Stratégie', 'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>'],
          ['id'=>'simulation','label'=>'Simulation','svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'],
          ['id'=>'horreur',   'label'=>'Horreur',   'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'],
          ['id'=>'indie',     'label'=>'Indie',     'svg'=>'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>'],
        ];
        foreach ($genres as $g): ?>
        <label class="merch-select-card genre-chip" for="genre_<?= $g['id'] ?>">
          <input type="checkbox" id="genre_<?= $g['id'] ?>" name="genres[]"
                 value="<?= htmlspecialchars($g['id']) ?>" class="genre-checkbox" hidden>
          <div class="merch-select-img" style="background:linear-gradient(135deg,rgba(124,58,237,.18),rgba(12,6,28,.85))">
            <div class="merch-select-icon"><?= $g['svg'] ?></div>
          </div>
          <div class="merch-select-name"><?= htmlspecialchars($g['label']) ?></div>
          <div class="merch-select-btn">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Step 2 : Qualité ───────────────────────────────────── -->
    <div class="config-step-card">
      <div class="config-step-header">
        <div class="config-step-num">2</div>
        <div>
          <div class="config-step-title">Qualité de streaming</div>
          <div class="config-step-sub">Choisissez la résolution adaptée à votre connexion et votre écran</div>
        </div>
      </div>

      <div class="plan-grid" id="qualityGrid">
        <?php
        $qualities = [
          ['id'=>'hd',  'label'=>'HD',         'res'=>'720p',  'desc'=>'Idéale pour les connexions limitées', 'price'=>4.99,  'tag'=>'Économique', 'badge'=>null,
           'perks'=>['HD 720p','Jusqu\'à 60 FPS','Bande passante réduite','Compatible 4G/fibre']],
          ['id'=>'fhd', 'label'=>'Full HD',     'res'=>'1080p', 'desc'=>'Meilleur rapport qualité / débit',   'price'=>9.99,  'tag'=>'Recommandé', 'badge'=>'Recommandé',
           'perks'=>['Full HD 1080p','Jusqu\'à 120 FPS','HDR basique','Connexion standard']],
          ['id'=>'4k',  'label'=>'4K Ultra HD', 'res'=>'2160p', 'desc'=>"L'expérience visuelle ultime",      'price'=>19.99, 'tag'=>'Premium',    'badge'=>null,
           'perks'=>['4K Ultra HD','144 FPS','HDR10 + Dolby Vision','Ray tracing RTX']],
        ];
        foreach ($qualities as $q): ?>
        <div class="cfg-plan-wrap">
          <?php if ($q['badge']): ?>
            <div class="cfg-plan-badge">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <?= htmlspecialchars($q['badge']) ?>
            </div>
          <?php endif; ?>
          <label class="cfg-plan-card <?= $q['badge'] ? 'has-badge' : '' ?>" for="quality_<?= $q['id'] ?>">
            <input type="radio" id="quality_<?= $q['id'] ?>" name="quality"
                   value="<?= $q['id'] ?>" data-price="<?= $q['price'] ?>"
                   class="quality-radio" hidden <?= $q['id'] === 'fhd' ? 'checked' : '' ?>>
            <div class="cfg-plan-header">
              <div class="cfg-plan-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              </div>
              <div>
                <div class="cfg-plan-name"><?= htmlspecialchars($q['label']) ?></div>
                <div class="cfg-plan-desc"><?= htmlspecialchars($q['res']) ?> · <?= htmlspecialchars($q['desc']) ?></div>
              </div>
            </div>
            <div class="cfg-plan-price-wrap">
              <span class="cfg-plan-price"><?= number_format($q['price'], 2, ',', '') ?></span>
              <span class="cfg-plan-period">€/mois</span>
            </div>
            <ul class="cfg-plan-perks">
              <?php foreach ($q['perks'] as $perk): ?>
                <li>
                  <svg class="cfg-perk-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  <?= htmlspecialchars($perk) ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="cfg-plan-cta">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              Sélectionner
            </div>
          </label>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Step 3 : Options ──────────────────────────────────── -->
    <div class="config-step-card">
      <div class="config-step-header">
        <div class="config-step-num">3</div>
        <div>
          <div class="config-step-title">Options supplémentaires</div>
          <div class="config-step-sub">Enrichissez votre expérience avec des fonctionnalités à la carte</div>
        </div>
      </div>

      <div class="options-list" id="optionsList">
        <?php
        $options = [
          ['id'=>'raytracing',  'label'=>'Ray Tracing',           'desc'=>'Éclairage et reflets photoréalistes en temps réel',  'price'=>5,
           'svg'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>'],
          ['id'=>'savecloud',   'label'=>'Sauvegardes illimitées', 'desc'=>'Historique complet et stockage cloud sans limite',    'price'=>3,
           'svg'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 109 20h9a5 5 0 000-10z"/></svg>'],
          ['id'=>'support',     'label'=>'Support prioritaire',    'desc'=>'Réponse garantie en moins de 2h, 7j/7',               'price'=>4,
           'svg'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/></svg>'],
          ['id'=>'multidevice', 'label'=>'Multi-appareils',        'desc'=>'Jouez sur 2 appareils simultanément',                 'price'=>6,
           'svg'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>'],
        ];
        foreach ($options as $opt): ?>
        <label class="option-row" for="opt_<?= $opt['id'] ?>">
          <input type="checkbox" id="opt_<?= $opt['id'] ?>" name="options[]"
                 value="<?= htmlspecialchars($opt['id']) ?>" data-price="<?= $opt['price'] ?>"
                 class="option-checkbox" hidden>
          <div class="option-icon-wrap"><?= $opt['svg'] ?></div>
          <div class="option-info">
            <div class="option-name"><?= htmlspecialchars($opt['label']) ?></div>
            <div class="option-desc"><?= htmlspecialchars($opt['desc']) ?></div>
          </div>
          <div class="option-price">+<?= $opt['price'] ?>,00 <span class="option-price-unit">€/mois</span></div>
          <div class="option-switch-track">
            <div class="option-switch-thumb"></div>
          </div>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /.config-steps-col -->

  <!-- ── Sticky summary sidebar ──────────────────────────────── -->
  <aside class="config-summary-col">
    <div class="config-summary-card">
      <div class="config-summary-glow"></div>

      <div class="config-summary-head">
        <h3>Mon bouquet</h3>
        <span class="config-summary-badge">Personnalisé</span>
      </div>

      <div class="config-summary-body">
        <div class="summary-block">
          <div class="summary-block-label">Genres sélectionnés</div>
          <div id="summaryGenres">
            <span class="summary-empty">Aucun genre sélectionné</span>
          </div>
        </div>

        <div class="config-summary-sep"></div>

        <div class="summary-block">
          <div class="summary-block-label">Qualité de streaming</div>
          <div class="summary-line">
            <span class="summary-line-name" id="summaryQualityName">Full HD (1080p)</span>
            <span class="summary-line-price" id="summaryQualityPrice">9,99 €</span>
          </div>
        </div>

        <div class="config-summary-sep"></div>

        <div class="summary-block" id="summaryOptionsSection" style="display:none">
          <div class="summary-block-label">Options</div>
          <div id="summaryOptionsList"></div>
        </div>
      </div>

      <div class="config-summary-total-wrap">
        <div class="summary-total-row">
          <span class="summary-total-label">Total mensuel</span>
          <span class="summary-total-price" id="summaryTotal">9,99 €</span>
        </div>
        <div class="summary-total-note">TVA incluse · Sans engagement</div>
      </div>

      <div class="config-summary-actions">
        <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full" id="summaryOrderBtn">
          Commander mon bouquet
        </a>
        <div class="config-trust">
          <span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Paiement sécurisé
          </span>
          <span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 100-6.5L1 10"/></svg>
            Résiliation en 1 clic
          </span>
        </div>
      </div>
    </div>
  </aside>

</div><!-- /.config-layout -->

</div><!-- /.config-page -->

<?php require 'includes/footer.php'; ?>
