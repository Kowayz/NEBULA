<?php
require 'includes/db.php';

$pageTitle = 'Jeu';
$pageCSS   = ['produit'];
$pageJS    = [];

$id  = (int)($_GET['id'] ?? 0);
$jeu = null;

try {
    $pdo = getPDO();
    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT * FROM jeu WHERE id_jeu = :id');
        $stmt->execute([':id' => $id]);
        $jeu = $stmt->fetch();
    }
} catch (Exception $e) {
    $jeu = null;
}

// Fallback
if (!$jeu) {
    $jeu = [
        'id_jeu'      => 1,
        'titre'       => "Marvel's Wolverine",
        'genre'       => 'Action,Aventure,Super-héros',
        'developpeur' => 'Insomniac Games',
        'image_url'   => '',
        'description' => "Incarnez le mutant emblématique Logan dans une aventure mature et brutale. Déchaînez vos griffes en adamantium dans une histoire originale et viscérale. Explorez un monde ouvert riche en détails, affrontez des ennemis redoutables et découvrez les secrets les plus sombres de l'identité de Wolverine.",
        'date_sortie' => '2025-03-15',
    ];
}

$tags = !empty($jeu['genre'])
    ? array_filter(array_map('trim', explode(',', $jeu['genre'])))
    : [];

// Fetch related games
$related = [];
try {
    $pdo = $pdo ?? getPDO();
    $stmtRel = $pdo->prepare(
        'SELECT id_jeu, titre, genre, image_url FROM jeu WHERE id_jeu != :id ORDER BY RAND() LIMIT 4'
    );
    $stmtRel->execute([':id' => $jeu['id_jeu']]);
    $related = $stmtRel->fetchAll();
} catch (Exception $e) {
    $related = [];
}

if (empty($related)) {
    $related = [
        ['id_jeu'=>2,'titre'=>'Spider-Man 2',       'genre'=>'Action,Aventure',  'image_url'=>''],
        ['id_jeu'=>3,'titre'=>'Elden Ring',          'genre'=>'RPG,Action',       'image_url'=>''],
        ['id_jeu'=>4,'titre'=>'Cyberpunk 2077',      'genre'=>'RPG,FPS',          'image_url'=>''],
        ['id_jeu'=>5,'titre'=>'God of War Ragnarök', 'genre'=>'Action,Aventure',  'image_url'=>''],
    ];
}

// Format release date
$dateFormatted = '';
if (!empty($jeu['date_sortie'])) {
    $ts = strtotime($jeu['date_sortie']);
    if ($ts) {
        $months = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        $dateFormatted = date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$pageTitle = htmlspecialchars($jeu['titre']);

require 'includes/header.php';
?>

<!-- ── Game hero ─────────────────────────────────────────────── -->
<div class="produit-hero"<?php if (!empty($jeu['image_url'])): ?> style="--hero-img: url('/NEBULA/<?= htmlspecialchars($jeu['image_url']) ?>')"<?php endif; ?>>
  <div class="produit-hero-overlay"></div>
  <div class="produit-hero-content">
    <div class="produit-tags">
      <?php foreach ($tags as $tag): ?>
        <span class="produit-tag"><?= htmlspecialchars($tag) ?></span>
      <?php endforeach; ?>
    </div>
    <h1 class="produit-title"><?= htmlspecialchars($jeu['titre']) ?></h1>
    <div class="produit-dev">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><circle cx="12" cy="12" r="2"/></svg>
      <?= htmlspecialchars($jeu['developpeur'] ?? 'Développeur inconnu') ?>
    </div>
    <div class="produit-hero-actions">
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg produit-play-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        Jouer maintenant
      </a>
      <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Voir le catalogue</a>
    </div>
  </div>
</div>

<!-- ── Main content ──────────────────────────────────────────── -->
<section class="section produit-main">
  <div class="produit-content-grid">

    <!-- Left: description -->
    <div class="produit-desc-col">
      <div class="produit-desc-card">
        <div class="produit-desc-label">Description</div>
        <p class="produit-desc-text"><?= nl2br(htmlspecialchars($jeu['description'] ?? '')) ?></p>
      </div>

      <!-- Media / screenshots -->
      <div class="produit-media-card">
        <div class="produit-desc-label">Aperçu</div>
        <div class="produit-screenshots">

          <!-- Trailer placeholder -->
          <div class="produit-screenshot-placeholder">
            <div class="screenshot-play-btn">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
            <span>Bande-annonce</span>
          </div>

          <div class="produit-screenshot-placeholder">
            <span>Screenshot 1</span>
          </div>

          <div class="produit-screenshot-placeholder">
            <span>Screenshot 2</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: game info -->
    <aside class="produit-info-col">
      <!-- Info card -->
      <div class="produit-info-card">
        <div class="produit-info-title">Informations</div>

        <div class="produit-info-rows">
          <?php if ($dateFormatted): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Sortie
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($dateFormatted) ?></span>
          </div>
          <?php endif; ?>

          <div class="produit-info-row">
            <span class="produit-info-key">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><circle cx="12" cy="12" r="2"/></svg>
              Développeur
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($jeu['developpeur'] ?? '—') ?></span>
          </div>

          <?php if (!empty($tags)): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
              Genres
            </span>
            <span class="produit-info-val produit-info-tags">
              <?php foreach ($tags as $tag): ?>
                <span class="produit-mini-tag"><?= htmlspecialchars($tag) ?></span>
              <?php endforeach; ?>
            </span>
          </div>
          <?php endif; ?>

          <div class="produit-info-row">
            <span class="produit-info-key">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              Qualité max
            </span>
            <span class="produit-info-val produit-info-badge">4K · 144 FPS · HDR10</span>
          </div>
        </div>

        <div class="produit-platforms-label">Plateformes disponibles</div>
        <div class="produit-platforms">
          <div class="produit-platform-item" title="Xbox">
            <svg class="platform-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M4.102 4.102C2.144 5.874 1 8.3 1 12c0 3.697 1.14 6.12 3.098 7.894C5.864 21.574 8.778 23 12 23s6.135-1.426 7.902-3.106C21.858 18.12 23 15.697 23 12c0-3.7-1.14-6.126-3.094-7.898C18.134 2.424 15.22 1 12 1S5.865 2.424 4.102 4.102zm2.034.716S7.757 6.4 9.97 9.207c-1.648 2.128-3.506 3.906-3.506 3.906S5.195 10.99 5.195 9.316c0-1.785.545-3.265.94-4.498zM12 7.41c1.22-1.57 2.453-2.816 3.272-3.55-.99-.41-2.087-.65-3.272-.65s-2.282.24-3.272.65C9.547 4.594 10.78 5.84 12 7.41zm5.535-2.59c.395 1.232.94 2.712.94 4.497 0 1.674-1.268 3.797-1.268 3.797s-1.858-1.778-3.506-3.906C15.913 6.4 17.535 4.82 17.535 4.82zm-5.535 6.68L9.2 14.84s1.43 2.16 2.8 3.57c1.37-1.41 2.8-3.57 2.8-3.57L12 11.5zM5.58 14.64s1.7 2.14 3.12 3.41c1.026.932 2.175 2.43 3.3 3.64-2.865-.17-5.39-1.7-6.855-3.82-.33-.47-.565-3.23.436-3.23zm12.84 0c1 0 .764 2.76.434 3.23-1.465 2.12-3.99 3.65-6.854 3.82 1.124-1.21 2.273-2.708 3.3-3.64 1.42-1.27 3.12-3.41 3.12-3.41z"/></svg>
            <span>Xbox</span>
          </div>
          <div class="produit-platform-item" title="PlayStation">
            <svg class="platform-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9.5 3v13.2l3 .9V6.3c0-.4.2-.7.6-.6 1 .3 1.3 1.4 1.3 2.2v4.4c2.1.5 4.3-.1 4.3-2.9C18.7 6 16.7 5 14.5 4.4L9.5 3zm-3.3 14.1c-1.9-.5-2.2-1.5-1.4-2.2.7-.6 2-.9 2-.9v1.6s-.6.2-.9.5c-.3.3-.1.6.7.8.8.2 5.3 1.5 5.3 1.5v1.6l-5.7-2.9zm9.3 0l-2.9 1.5V17l2.9-1.5v1.6z"/></svg>
            <span>PlayStation</span>
          </div>
          <div class="produit-platform-item" title="PC">
            <svg class="platform-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M0 3.449L9.75 2.1v9.451H0m10.949-9.602L24 0v11.4H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-12.9-1.801"/></svg>
            <span>PC</span>
          </div>
          <div class="produit-platform-item" title="Mobile">
            <svg class="platform-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            <span>Mobile</span>
          </div>
        </div>

        <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full" style="margin-top:20px">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          Jouer maintenant
        </a>
      </div>

      <!-- Requirements card -->
      <div class="produit-req-card">
        <div class="produit-req-title">Configuration recommandée</div>
        <div class="produit-req-rows">
          <div class="produit-req-row">
            <span class="produit-req-key">Connexion</span>
            <span class="produit-req-val">25 Mbit/s</span>
          </div>
          <div class="produit-req-row">
            <span class="produit-req-key">Latence</span>
            <span class="produit-req-val">&lt; 40 ms</span>
          </div>
          <div class="produit-req-row">
            <span class="produit-req-key">Navigateur</span>
            <span class="produit-req-val">Chrome, Edge, Safari</span>
          </div>
          <div class="produit-req-row">
            <span class="produit-req-key">Qualité max</span>
            <span class="produit-req-val">4K · 144 FPS</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</section>

<!-- ── Related games ──────────────────────────────────────────── -->
<?php if (!empty($related)): ?>
<section class="section" style="padding-top:0">
  <div class="section-header">
    <div class="section-tag">Catalogue</div>
    <h2>Autres jeux disponibles</h2>
    <div class="glow-bar"></div>
  </div>
  <div class="produit-related-grid">
    <?php foreach ($related as $rel):
      $relTags = !empty($rel['genre'])
          ? array_slice(array_filter(array_map('trim', explode(',', $rel['genre']))), 0, 2)
          : [];
    ?>
    <a href="/NEBULA/produit.php?id=<?= (int)$rel['id_jeu'] ?>" class="produit-related-card">
      <div class="produit-related-img">
        <?php if (!empty($rel['image_url'])): ?>
          <img src="/NEBULA/<?= htmlspecialchars($rel['image_url']) ?>" alt="<?= htmlspecialchars($rel['titre']) ?>">
        <?php else: ?>
          <div class="produit-related-placeholder" style="width:100%;height:100%;background:linear-gradient(135deg,rgba(124,58,237,.35),rgba(159,18,57,.25),rgba(12,6,28,.9))"></div>
        <?php endif; ?>
        <div class="produit-related-overlay">
          <div class="produit-related-play">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:4px"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Jouer
          </div>
        </div>
      </div>
      <div class="produit-related-info">
        <div class="produit-related-title"><?= htmlspecialchars($rel['titre']) ?></div>
        <div class="produit-related-tags">
          <?php foreach ($relTags as $t): ?>
            <span class="produit-mini-tag"><?= htmlspecialchars($t) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="text-center" style="margin-top:32px">
    <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Voir tout le catalogue</a>
  </div>
</section>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
