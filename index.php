<?php
// -- Configuration de la page (titre, CSS, JS) --
$pageTitle = 'Jouez à vos jeux n\'importe où';
$pageCSS   = ['index', 'jeux'];
$pageJS    = [];

require_once 'api/igdb.php';
$topGames = igdb_get_trending_games(5);

require 'includes/header.php';
?>

<!-- ═══════════════════════════════ HERO ═══════════════════
     Bannière principale avec titre accrocheur et bouton CTA
     ═══════════════════════════════════════════════════════ -->
<section class="hero">
  <h1>Jouez à vos jeux<br><span class="gradient-text">n'importe où</span></h1>
  <p>Profitez de vos jeux préférés en streaming haute qualité sans téléchargement, sur tous vos appareils</p>

  <!-- Bouton d'inscription -->
  <div class="hero-actions">
    <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg">
      <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="16" height="16" class="icon-img">
      Commencer gratuitement
    </a>
  </div>

</section>

<!-- ═══════════════════════════ BIBLIOTHÈQUE ════════════════
     Vitrine des jeux tendances récupérés via l'API IGDB.
     Même grille catalogue-card que dans produit.php.
     ═══════════════════════════════════════════════════════ -->
<?php if (!empty($topGames)): ?>
<section class="section library-section section--alt">
  <div class="section-header">
    <h2>Une bibliothèque infinie</h2>
    <p>Des centaines de jeux AAA disponibles instantanément, de nouvelles sorties chaque mois</p>
  </div>

  <div class="catalogue-grid">
    <?php foreach ($topGames as $game):
      if (empty($game['titre'])) continue;
    ?>
    <a href="/NEBULA/produit.php?id=<?= (int)$game['id_jeu'] ?>" class="catalogue-card">
      <div class="catalogue-card-poster">
        <img src="<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['titre']) ?>" loading="lazy">
      </div>
      <div class="catalogue-card-overlay">
        <div class="catalogue-card-title"><?= htmlspecialchars($game['titre']) ?></div>
        <div class="catalogue-play-btn">Jouer</div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-32">
    <a href="/NEBULA/jeux.php" class="btn btn-outline">Voir tous les jeux</a>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════ FONCTIONNALITÉS ═════════════
     Grille de 6 cartes présentant les avantages de Nebula
     ═══════════════════════════════════════════════════════ -->
<section class="section features-section">
  <div class="section-header">
    <h2>Tout ce dont vous avez besoin</h2>
    <p>Une technologie de pointe pour une expérience de jeu sans friction</p>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Latence ultra-faible</h3>
      <p>Moins de 20ms grâce à notre infrastructure distribuée, peu importe votre localisation.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/4K.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>4K 144 FPS</h3>
      <p>Qualité d'image exceptionnelle jusqu'en 4K Ultra HD à 144 FPS, sur n'importe quel écran.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/platforms/multi.png" alt="icon" width="22" height="22" class="platform-icon"></div>
      <h3>Multi-appareils</h3>
      <p>PC, Mac, TV, smartphone — reprenez votre partie là où vous l'avez laissée.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Sauvegardes cloud</h3>
      <p>Vos sauvegardes sont synchronisées automatiquement. Ne perdez plus jamais votre progression.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/game.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Compatible manettes</h3>
      <p>DualSense, Xbox Series, contrôleurs Bluetooth — plug & play garanti.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/contact/document-legal.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Sans engagement</h3>
      <p>Résiliez quand vous voulez. Nos abonnements sont flexibles, sans frais cachés.</p>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════ CTA ════════════════════
     Bandeau final d'appel à l'action (inscription + offres)
     ═══════════════════════════════════════════════════════ -->
<section class="cta-band section--alt">
  <div class="glow-bar"></div>
  <h2>Prêt à jouer ?</h2>
  <p>Rejoignez des milliers de joueurs qui profitent déjà de Nebula. Commencez gratuitement.</p>
  <div class="cta-actions">
    <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg">Créer un compte gratuit</a>
    <a href="/NEBULA/offres.php" class="btn btn-outline btn-lg">Voir les offres</a>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
