<?php
/* ============================================================
   PRODUIT.PHP — Page de détail d'un jeu
   Affiche le hero, la description, les screenshots, les avis,
   les infos techniques et les jeux liés.
   Le jeu est récupéré via l'API IGDB grâce au paramètre ?id=.
   ============================================================ */

// -- Charger l'API IGDB --
require 'api/igdb.php';

// -- Récupérer l'ID du jeu depuis l'URL (?id=123) --
$id  = (int)($_GET['id'] ?? 0);
$jeu = null;
$related = [];

// -- Déterminer le type de jeu : premium (achetable) ou streaming (inclus) --
// Par défaut : ID pair = achetable, ID impair = inclus dans l'abonnement
// On peut forcer le type via ?type=premium ou ?type=streaming
$typeForce = $_GET['type'] ?? null;

if ($typeForce) {
    $typeJeu = $typeForce;
} else {
    // ID pair = achetable, ID impair = inclus
    $typeJeu = ($id % 2 === 0) ? 'premium' : 'streaming';
}

// -- Prix fixe pour les jeux premium (achetables) --
$prixJeu = 39.99;

// -- Si un ID valide est fourni, récupérer les données du jeu --
if ($id > 0) {
    $jeu = igdb_get_game($id);
    
    // Ajouter le type et le prix au jeu
    if ($jeu) {
        $jeu['type'] = $typeJeu;
        $jeu['prix'] = ($typeJeu === 'premium') ? $prixJeu : 0;
    }
    
    // Jeux liés : récupérer 20 jeux, mélanger et en garder 7 au hasard
    $games = igdb_get_games(20);
    shuffle($games);
    $related = array_slice($games, 0, 7);
}

// -- Formater la date de sortie en français (ex: "15 mars 2024") --
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

// -- Configuration de la page et inclusion du header --
$pageTitle = htmlspecialchars($jeu['titre']);
$pageCSS   = ['jeux', 'produit'];
$pageJS    = [];

require 'includes/header.php';
?>

<!-- ── Hero du jeu ──────────────────────────────────────────────
     Grande bannière avec l'artwork du jeu en fond (via CSS variable),
     le titre, le tag (Acheter/Inclus) et les boutons d'action.
     ──────────────────────────────────────────────────────────────── -->
<div class="produit-hero"<?php if (!empty($jeu['hero_url'])): ?> style="--hero-img: url('<?= htmlspecialchars($jeu['hero_url']) ?>')"<?php endif; ?>>
  <div class="produit-hero-overlay"></div>

  <div class="produit-hero-content">
    <div class="produit-hero-row">

      <!-- Gauche : tag du type · titre du jeu · boutons d'action -->
      <div class="produit-hero-left">
        <!-- Tag indiquant si le jeu est achetable ou inclus -->
        <div class="produit-tags">
          <span class="produit-tag produit-tag--type <?= $typeJeu === 'premium' ? 'produit-tag--buy' : '' ?>">
            <?= $typeJeu === 'premium' ? 'Acheter' : 'Inclus' ?>
          </span>
        </div>

        <h1 class="produit-title"><?= htmlspecialchars($jeu['titre']) ?></h1>

        <!-- Boutons CTA : acheter ou jouer selon le type -->
        <div class="produit-hero-actions">
          <?php if ($typeJeu === 'premium'): ?>
            <!-- Jeu premium : bouton d'achat avec prix -->
            <a href="/NEBULA/panier.php?add=<?= $id ?>&nom=<?= urlencode($jeu['titre']) ?>&prix=<?= $prixJeu ?>" class="btn btn-primary btn-lg produit-buy-btn">
              <span class="produit-price"><?= number_format($prixJeu, 2) ?> €</span>
              <span class="produit-buy-text">Acheter</span>
            </a>
          <?php else: ?>
            <!-- Jeu inclus : bouton pour jouer directement -->
            <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg produit-play-btn">
              <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
              Jouer maintenant
            </a>
          <?php endif; ?>
          <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Voir le catalogue</a>
        </div>
      </div>


    </div>
  </div>

</div>

<!-- ── Contenu principal ─────────────────────────────────────────
     Layout en 2 colonnes :
     - Gauche : description, aperçu (trailer + screenshots), avis joueurs
     - Droite : couverture, infos techniques, plateformes, bouton CTA
     ──────────────────────────────────────────────────────────────── -->
<section class="section produit-main">
  <div class="produit-content-grid">

    <!-- ── Colonne gauche : description + aperçu + avis ── -->
    <div class="produit-desc-col">

      <!-- Carte : Description du jeu -->
      <div class="db-card">
        <div class="db-card-head"><div class="db-card-title">Description</div></div>
        <p class="produit-desc-text"><?= nl2br(htmlspecialchars($jeu['description'] ?? '')) ?></p>
      </div>

      <!-- Carte : Aperçu (trailer YouTube + screenshots IGDB) -->
      <div class="db-card">
        <div class="db-card-head"><div class="db-card-title">Aperçu</div></div>

        <!-- Trailer YouTube intégré (si disponible) -->
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

        <!-- Grille de screenshots (max 3, compact si trailer présent) -->
        <?php if (!empty($jeu['screenshots'])): ?>
        <div class="produit-screenshots <?= !empty($jeu['trailer_id']) ? 'produit-screenshots--compact' : '' ?>">
          <?php foreach (array_slice($jeu['screenshots'], 0, 3) as $shot): ?>
            <div class="produit-screenshot-item">
              <img src="<?= htmlspecialchars($shot) ?>" alt="Screenshot <?= htmlspecialchars($jeu['titre']) ?>" loading="lazy">
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <!-- Carte : Avis joueurs (commentaires statiques de démonstration) -->
      <div class="db-card">
        <div class="db-card-head"><div class="db-card-title">Avis joueurs</div></div>
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

    <!-- ── Colonne droite (sidebar) : couverture + infos ── -->
    <aside class="produit-info-col">

      <!-- Image de couverture du jeu -->
      <?php if (!empty($jeu['cover_url'])): ?>
      <div class="produit-cover-wrap">
        <img class="produit-cover" src="<?= htmlspecialchars($jeu['cover_url']) ?>" alt="<?= htmlspecialchars($jeu['titre']) ?>">
      </div>
      <?php endif; ?>

      <!-- Carte : Informations techniques du jeu -->
      <div class="db-card">
        <div class="db-card-head"><div class="db-card-title">Informations</div></div>
        <div class="produit-info-rows">
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/dashboard/star.png" alt="icon" width="16" height="16" class="icon-img" style="opacity:0.8">
              Note
            </span>
            <span class="produit-info-val">83</span>
          </div>

          <?php if ($dateFormatted): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/calendrier.png" alt="icon" width="16" height="16" class="icon-img">
              Sortie
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($dateFormatted) ?></span>
          </div>
          <?php endif; ?>

          <?php if (!empty($jeu['developpeur'])): ?>
          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/platforms/image.png" alt="icon" width="16" height="16" class="icon-img">
              Développeur
            </span>
            <span class="produit-info-val"><?= htmlspecialchars($jeu['developpeur']) ?></span>
          </div>
          <?php endif; ?>


          <div class="produit-info-row">
            <span class="produit-info-key">
              <img src="/NEBULA/public/assets/img/icons/dashboard/4K.png" alt="icon" width="16" height="16" class="icon-img">
              Qualité max
            </span>
            <span class="produit-info-val">4K · 144 FPS · HDR10</span>
          </div>
        </div>

        <!-- Plateformes compatibles -->
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
            <img src="/NEBULA/public/assets/img/icons/platforms/mobile.png" alt="icon" width="24" height="24" class="platform-icon">
            <span>Mobile</span>
          </div>
        </div>

        <!-- Bouton CTA sidebar : acheter ou jouer selon le type -->
        <?php if ($typeJeu === 'premium'): ?>
          <a href="/NEBULA/panier.php?add=<?= $id ?>&nom=<?= urlencode($jeu['titre']) ?>&prix=<?= $prixJeu ?>" class="btn btn-primary btn-full produit-buy-sidebar produit-sidebar-cta">
            Ajouter au panier — <?= number_format($prixJeu, 2) ?> €
          </a>
        <?php else: ?>
          <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full produit-sidebar-cta">
            <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
            Jouer maintenant
          </a>
        <?php endif; ?>
      </div>

    </aside>
  </div>
</section>

<!-- ── Jeux liés ─────────────────────────────────────────────────
     Grille de 7 jeux aléatoires du catalogue, affichés avec les
     mêmes cartes que la page jeux.php (catalogue-card).
     ──────────────────────────────────────────────────────────────── -->
<?php if (!empty($related)): ?>
<section class="section produit-related-section">
  <div class="section-header">
    <div class="section-tag">Catalogue</div>
    <h2>Autres jeux disponibles</h2>
    <div class="glow-bar"></div>
  </div>
  <div class="catalogue-grid">
    <?php foreach ($related as $rel):
      if (empty($rel['titre'])) continue;
    ?>
    <a href="/NEBULA/produit.php?id=<?= (int)$rel['id_jeu'] ?>" class="catalogue-card">
      <div class="catalogue-card-poster">
        <?php if (!empty($rel['image_url'])): ?>
          <img src="<?= htmlspecialchars($rel['image_url']) ?>" alt="<?= htmlspecialchars($rel['titre']) ?>" loading="lazy">
        <?php else: ?>
          <div class="catalogue-card-placeholder"></div>
        <?php endif; ?>
      </div>
      <div class="catalogue-card-overlay">
        <div class="catalogue-card-title"><?= htmlspecialchars($rel['titre']) ?></div>
        <div class="catalogue-play-btn">Jouer</div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="text-center produit-related-more">
    <a href="/NEBULA/jeux.php" class="btn btn-outline btn-lg">Voir tout le catalogue</a>
  </div>
</section>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
