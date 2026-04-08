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
      <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
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
          ['id'=>'action',    'label'=>'Action',    'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'rpg',       'label'=>'RPG',       'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">'],
          ['id'=>'fps',       'label'=>'FPS',       'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'sport',     'label'=>'Sport',     'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'course',    'label'=>'Course',    'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'aventure',  'label'=>'Aventure',  'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'strategie', 'label'=>'Stratégie', 'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'simulation','label'=>'Simulation','svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'horreur',   'label'=>'Horreur',   'svg'=>'<img src="/NEBULA/public/assets/img/icons/nav/oeil-ouvert.png" alt="icon" width="18" height="18" class="icon-img eye-icon">'],
          ['id'=>'indie',     'label'=>'Indie',     'svg'=>'<img src="/NEBULA/public/assets/img/icons/ecommerce/composant-cpu.png" alt="icon" width="26" height="26" class="icon-img">'],
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
            <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">
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
              <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
              <?= htmlspecialchars($q['badge']) ?>
            </div>
          <?php endif; ?>
          <label class="cfg-plan-card <?= $q['badge'] ? 'has-badge' : '' ?>" for="quality_<?= $q['id'] ?>">
            <input type="radio" id="quality_<?= $q['id'] ?>" name="quality"
                   value="<?= $q['id'] ?>" data-price="<?= $q['price'] ?>"
                   class="quality-radio" hidden <?= $q['id'] === 'fhd' ? 'checked' : '' ?>>
            <div class="cfg-plan-header">
              <div class="cfg-plan-icon">
                <img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">
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
                  <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">
                  <?= htmlspecialchars($perk) ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="cfg-plan-cta">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">
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
           'svg'=>'<img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'savecloud',   'label'=>'Sauvegardes illimitées', 'desc'=>'Historique complet et stockage cloud sans limite',    'price'=>3,
           'svg'=>'<img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'support',     'label'=>'Support prioritaire',    'desc'=>'Réponse garantie en moins de 2h, 7j/7',               'price'=>4,
           'svg'=>'<img src="/NEBULA/public/assets/img/icons/ecommerce/bouclier-securite.png" alt="icon" width="20" height="20" class="icon-img">'],
          ['id'=>'multidevice', 'label'=>'Multi-appareils',        'desc'=>'Jouez sur 2 appareils simultanément',                 'price'=>6,
           'svg'=>'<img src="/NEBULA/public/assets/img/icons/platforms/nintendo.png" alt="icon" width="24" height="24" class="platform-icon">'],
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
            <img src="/NEBULA/public/assets/img/icons/dashboard/colis.png" alt="icon" width="20" height="20" class="icon-img">
            Paiement sécurisé
          </span>
          <span>
            <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="14" height="14" class="icon-img">
            Résiliation en 1 clic
          </span>
        </div>
      </div>
    </div>
  </aside>

</div><!-- /.config-layout -->

</div><!-- /.config-page -->

<?php require 'includes/footer.php'; ?>
