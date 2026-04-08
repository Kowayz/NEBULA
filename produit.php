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
                   first_release_date,involved_companies.company.name;
            where id = {$id};
        ", $token);

        $g = is_array($data) ? ($data[0] ?? null) : null;
        if ($g) {
            $jeu = array_merge(igdb_map($g), [
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
              <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
              <?= $jeu['rating'] ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="produit-hero-actions">
          <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg produit-play-btn">
            <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
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
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
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
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-vide.png" alt="icon" width="14" height="14" class="icon-img">
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
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
                  <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
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
        <div class="produit-info-rows">
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img" style="opacity:0.8">
              Note
            </span>
            <span class="produit-info-val">83</span>
          </div>

          <?php if ($dateFormatted): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/calendrier.png" alt="icon" width="14" height="14" class="icon-img">
              Sortie
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($dateFormatted) ?></span>
          </div>
          <?php endif; ?>

          <?php if (!empty($jeu['developpeur'])): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="14" height="14" class="icon-img">
              Développeur
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($jeu['developpeur']) ?></span>
          </div>
          <?php endif; ?>

          <?php if (!empty($tags)): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/Serveur.png" alt="icon" width="14" height="14" class="icon-img">
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
              <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="16" height="16" class="icon-img">
              Qualité max
            </span>
            <span class="produit-info-val produit-info-badge">4K · 144 FPS · HDR10</span>
          </div>
        </div>

        <div class="produit-platforms-label">Plateformes disponibles</div>
        <div class="produit-platforms">
          <div class="produit-platform-item" title="Xbox">
            <img src="/NEBULA/public/assets/img/icons/platforms/xbox.png" alt="icon" width="24" height="24" class="platform-icon">
            <span>Xbox</span>
          </div>
          <div class="produit-platform-item" title="PlayStation">
            <img src="/NEBULA/public/assets/img/icons/platforms/playstation.png" alt="icon" width="24" height="24" class="platform-icon">
            <span>PlayStation</span>
          </div>
          <div class="produit-platform-item" title="PC">
            <img src="/NEBULA/public/assets/img/icons/platforms/windows.png" alt="icon" width="24" height="24" class="platform-icon">
            <span>PC</span>
          </div>
          <div class="produit-platform-item" title="Mobile">
            <img src="/NEBULA/public/assets/img/icons/platforms/nintendo.png" alt="icon" width="24" height="24" class="platform-icon">
            <span>Mobile</span>
          </div>
        </div>

        <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full" style="margin-top:20px">
          <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
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
      if (empty($rel['titre'])) continue;
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
            <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
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
