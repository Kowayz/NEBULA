<?php
require 'includes/igdb.php';

$id  = (int)($_GET['id'] ?? 0);
$jeu = null;

if ($id > 0) {
    $token = igdb_token();
    if ($token) {
        $data = igdb_query('games', "
            fields id,name,summary,genres.name,cover.url,artworks.url,screenshots.url,
                   videos.video_id,videos.name,
                   first_release_date,involved_companies.company.name,rating;
            where id = {$id};
        ", $token);

        $g = is_array($data) ? ($data[0] ?? null) : null;
        if ($g) {
            $jeu = array_merge(igdb_map($g), [
                'rating'      => isset($g['rating']) ? round($g['rating']) : null,
                'trailer_id'  => $g['videos'][0]['video_id'] ?? null,
                'cover_url'   => igdb_cover($g['cover']['url'] ?? null, 't_original'),
                'hero_url'    => isset($g['artworks'][0])
                    ? igdb_cover($g['artworks'][0]['url'], 't_1080p')
                    : (isset($g['screenshots'][0])
                        ? igdb_cover($g['screenshots'][0]['url'], 't_1080p')
                        : igdb_cover($g['cover']['url'] ?? null, 't_1080p')),
                'logo_url'    => igdb_logo($g['name']),
                'screenshots' => (function() use ($g) {
                    // Use in-game screenshots for aperçu (varied scenes)
                    // artworks are marketing key art — often duplicate/logo — kept only for hero bg
                    $shots = array_map(
                        fn($s) => igdb_cover($s['url'], 't_screenshot_huge'),
                        array_slice($g['screenshots'] ?? [], 0, 3)
                    );
                    // If no screenshots, fall back to artworks[1..] (skip hero artwork[0])
                    if (count($shots) < 3) {
                        foreach (array_slice($g['artworks'] ?? [], 1, 3 - count($shots)) as $a) {
                            $shots[] = igdb_cover($a['url'], 't_1080p');
                        }
                    }
                    return array_filter($shots);
                })(),
            ]);

            // Jeux liés par genre
            $related     = [];
            $genreNames  = array_column($g['genres'] ?? [], 'name');
            $genreFilter = '';
            if ($genreNames) {
                $names  = implode(',', array_map(fn($n) => '"' . addslashes($n) . '"', $genreNames));
                $genres = igdb_query('genres', "fields id; where name = ({$names}); limit 10;", $token);
                if ($genres) {
                    $ids         = implode(',', array_column($genres, 'id'));
                    $genreFilter = "genres = ({$ids}) &";
                }
            }
            $relData = igdb_query('games', "
                fields id,name,genres.name,cover.url;
                where {$genreFilter} id != {$id} & cover != null & rating > 75 & rating_count > 50;
                sort rating desc; limit 20;
            ", $token);
            if ($relData) {
                shuffle($relData);
                $related = array_map('igdb_map', array_slice($relData, 0, 4));
            }
        }
    }
}

// Fallback si IGDB indisponible ou ID invalide
if (!$jeu) {
    $jeu = [
        'id_jeu'      => 0,
        'titre'       => 'Jeu introuvable',
        'genre'       => '',
        'developpeur' => null,
        'image_url'   => null,
        'cover_url'   => null,
        'hero_url'    => null,
        'logo_url'    => null,
        'screenshots' => [],
        'description' => 'Ce jeu est introuvable ou temporairement indisponible.',
        'date_sortie' => null,
    ];
    $related = [];
}

$tags = !empty($jeu['genre'])
    ? array_filter(array_map('trim', explode(',', $jeu['genre'])))
    : [];

// Formater la date de sortie
$dateFormatted = '';
$yearOnly      = '';
if (!empty($jeu['date_sortie'])) {
    $ts = strtotime($jeu['date_sortie']);
    if ($ts) {
        $months = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        $dateFormatted = date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
        $yearOnly      = date('Y', $ts);
    }
}

$pageTitle = htmlspecialchars($jeu['titre']);
$pageCSS   = ['produit'];
$pageJS    = [];

require 'includes/header.php';
?>

<!-- ── Game hero ─────────────────────────────────────────────── -->
<div class="produit-hero"<?php if (!empty($jeu['hero_url'])): ?> style="--hero-img: url('<?= htmlspecialchars($jeu['hero_url']) ?>')"<?php endif; ?>>
  <div class="produit-hero-overlay"></div>
  <div class="produit-hero-glow"></div>

  <div class="produit-hero-content">
    <div class="produit-hero-row">

      <!-- Gauche : tags · titre · meta · actions -->
      <div class="produit-hero-left">
        <?php if (!empty($tags)): ?>
        <div class="produit-tags">
          <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
            <span class="produit-tag"><?= htmlspecialchars($tag) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h1 class="produit-title"><?= htmlspecialchars($jeu['titre']) ?></h1>

        <div class="produit-hero-meta">
          <?php if (!empty($jeu['developpeur'])): ?>
            <span class="produit-hero-meta-dev"><?= htmlspecialchars($jeu['developpeur']) ?></span>
          <?php endif; ?>
          <?php if ($yearOnly): ?>
            <?php if (!empty($jeu['developpeur'])): ?><span class="produit-hero-meta-sep"></span><?php endif; ?>
            <span class="produit-hero-meta-year"><?= $yearOnly ?></span>
          <?php endif; ?>
          <?php if (!empty($jeu['rating'])): ?>
            <div class="produit-hero-meta-rating">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <?= $jeu['rating'] ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="produit-hero-actions">
          <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg produit-play-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Jouer maintenant
          </a>
          <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Voir le catalogue</a>
        </div>
      </div>

      <!-- Droite : logotype SteamGridDB -->
      <?php if (!empty($jeu['logo_url'])): ?>
      <div class="produit-hero-right">
        <img class="produit-logo" src="<?= htmlspecialchars($jeu['logo_url']) ?>" alt="<?= htmlspecialchars($jeu['titre']) ?>">
      </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="produit-hero-accent"></div>
</div>

<!-- ── Main content ──────────────────────────────────────────── -->
<section class="section produit-main">
  <div class="produit-content-grid">

    <!-- Colonne gauche : description + screenshots -->
    <div class="produit-desc-col">
      <div class="produit-desc-card">
        <div class="produit-desc-label">Description</div>
        <p class="produit-desc-text"><?= nl2br(htmlspecialchars($jeu['description'] ?? '')) ?></p>
      </div>

      <div class="produit-media-card">
        <div class="produit-desc-label">Aperçu</div>

        <?php if (!empty($jeu['trailer_id'])): ?>
        <div class="produit-trailer">
          <iframe
            src="https://www.youtube.com/embed/<?= htmlspecialchars($jeu['trailer_id']) ?>?rel=0&modestbranding=1"
            title="Trailer <?= htmlspecialchars($jeu['titre']) ?>"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            loading="lazy">
          </iframe>
        </div>
        <?php endif; ?>

        <?php if (!empty($jeu['screenshots'])): ?>
        <div class="produit-screenshots <?= !empty($jeu['trailer_id']) ? 'produit-screenshots--compact' : '' ?>">
          <?php foreach ($jeu['screenshots'] as $shot): ?>
            <div class="produit-screenshot-item">
              <img src="<?= htmlspecialchars($shot) ?>" alt="Screenshot <?= htmlspecialchars($jeu['titre']) ?>" loading="lazy">
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <!-- Commentaires -->
      <div class="produit-desc-card produit-reviews-card">
        <div class="produit-desc-label">Avis joueurs</div>
        <div class="produit-reviews">

          <div class="produit-review">
            <div class="produit-review-head">
              <div class="produit-review-avatar">MK</div>
              <div class="produit-review-meta">
                <span class="produit-review-author">Maxime_K</span>
                <div class="produit-review-stars">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
              </div>
              <span class="produit-review-date">Il y a 3 jours</span>
            </div>
            <p class="produit-review-text">Franchement bluffant en cloud gaming, aucune latence notable. L'image est nette et le jeu tourne parfaitement sur ma télé. Je ne pensais pas que ce serait aussi fluide.</p>
          </div>

          <div class="produit-review">
            <div class="produit-review-head">
              <div class="produit-review-avatar" style="background:linear-gradient(135deg,#9f1239,#7c3aed)">SL</div>
              <div class="produit-review-meta">
                <span class="produit-review-author">SaraLvl99</span>
                <div class="produit-review-stars">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="rgba(255,255,255,.15)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
              </div>
              <span class="produit-review-date">Il y a 1 semaine</span>
            </div>
            <p class="produit-review-text">Un must-have, le contenu est immense. Je joue depuis mon PC portable sans GPU dédié et c'est une révélation. Parfait pour les longues sessions.</p>
          </div>

          <div class="produit-review">
            <div class="produit-review-head">
              <div class="produit-review-avatar" style="background:linear-gradient(135deg,#0e7490,#7c3aed)">TR</div>
              <div class="produit-review-meta">
                <span class="produit-review-author">ThomasR</span>
                <div class="produit-review-stars">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="#c084fc"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
              </div>
              <span class="produit-review-date">Il y a 2 semaines</span>
            </div>
            <p class="produit-review-text">Qualité visuelle au rendez-vous, les graphismes passent super bien via Nebula. Aucun bug, aucune coupure sur ma fibre. Je recommande à 100%.</p>
          </div>

        </div>
      </div>

    </div>

    <!-- Colonne droite : infos + plateformes -->
    <aside class="produit-info-col">

      <?php if (!empty($jeu['cover_url'])): ?>
      <div class="produit-cover-wrap">
        <img class="produit-cover" src="<?= htmlspecialchars($jeu['cover_url']) ?>" alt="<?= htmlspecialchars($jeu['titre']) ?>">
      </div>
      <?php endif; ?>

      <div class="produit-info-card">
        <div class="produit-info-title">Informations</div>
        <?php if (!empty($jeu['rating'])): ?>
        <div class="produit-rating-row">
          <div class="produit-rating-score"><?= $jeu['rating'] ?></div>
          <div style="flex:1">
            <div class="produit-rating-bar">
              <div class="produit-rating-fill" style="width:<?= $jeu['rating'] ?>%"></div>
            </div>
            <div class="produit-rating-label" style="margin-top:4px">Note IGDB</div>
          </div>
        </div>
        <?php endif; ?>
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

          <?php if (!empty($jeu['developpeur'])): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><circle cx="12" cy="12" r="2"/></svg>
              Développeur
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($jeu['developpeur']) ?></span>
          </div>
          <?php endif; ?>

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

<!-- ── Jeux liés ───────────────────────────────────────────────── -->
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
          <img src="<?= htmlspecialchars($rel['image_url']) ?>" alt="<?= htmlspecialchars($rel['titre']) ?>" loading="lazy">
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
