<?php
require 'includes/db.php';

$pageTitle = 'Bibliothèque de jeux';
$pageCSS   = ['jeux'];
$pageJS    = ['catalogue'];

try {
    $pdo = getPDO();
    $genreStmt = $pdo->query('SELECT DISTINCT genre FROM jeu WHERE genre IS NOT NULL ORDER BY genre');
    $genres    = $genreStmt->fetchAll(PDO::FETCH_COLUMN);
    $jeux      = $pdo->query('SELECT * FROM jeu ORDER BY titre')->fetchAll();
} catch (Exception $e) {
    $genres = [];
    $jeux   = [];
}

if (empty($jeux)) {
    $jeux = [
        ['id_jeu'=>1,'titre'=>'ARC Raiders',    'genre'=>'Action,Extraction,Science-fiction','developpeur'=>'Embark Studios',  'image_url'=>'','description'=>"Affrontez des machines implacables venues des cieux. Luttez pour arracher des ressources vitales à l'oubli et assurer la survie de l'humanité.",'date_sortie'=>null],
        ['id_jeu'=>2,'titre'=>'Cyberpunk 2077',  'genre'=>'Action-RPG,Monde ouvert,Cyberpunk',   'developpeur'=>'CD Projekt',       'image_url'=>'','description'=>"Dans la mégalopole de Night City, incarnez V, un mercenaire hors-la-loi en quête d'un implant unique ouvrant la voie à l'immortalité.",'date_sortie'=>null],
        ['id_jeu'=>3,'titre'=>'Elden Ring',       'genre'=>'Action-RPG,Souls-like,Fantasy',        'developpeur'=>'FromSoftware',     'image_url'=>'','description'=>"Partez à la conquête des Terres Intermédiaires dans cet action-RPG épique co-écrit par Hidetaka Miyazaki et George R. R. Martin.",'date_sortie'=>null],
        ['id_jeu'=>4,'titre'=>"No Man's Sky",     'genre'=>'Exploration,Survie,Bac à sable',       'developpeur'=>'Hello Games',      'image_url'=>'','description'=>"Voyagez de planète en planète dans un univers procédural infini pour découvrir des mondes inconnus et récolter des ressources.",'date_sortie'=>null],
        ['id_jeu'=>5,'titre'=>'Hades II',         'genre'=>'Roguelike,Action,Mythologie',           'developpeur'=>'Supergiant Games', 'image_url'=>'','description'=>"Incarnez Mélinoé, princesse des Enfers, dans ce dungeon crawler d'action intense inspiré de la mythologie grecque.",'date_sortie'=>null],
        ['id_jeu'=>6,'titre'=>'Forza Horizon 5',  'genre'=>'Course,Monde ouvert,Simulation',        'developpeur'=>'Playground Games', 'image_url'=>'','description'=>"Explorez le Mexique au volant de plus de 500 voitures iconiques dans ce jeu de course en monde ouvert époustouflant.",'date_sortie'=>null],
        ['id_jeu'=>7,'titre'=>'God of War Ragnarök','genre'=>'Action,Aventure,Mythologie',          'developpeur'=>'Santa Monica Studio','image_url'=>'','description'=>"Kratos et Atreus s'aventurent à travers les Neuf Royaumes au milieu du Fimbulwinter annonciateur du Ragnarök.",'date_sortie'=>null],
        ['id_jeu'=>8,'titre'=>'Starfield',         'genre'=>'Action-RPG,Science-fiction,Exploration','developpeur'=>'Bethesda',        'image_url'=>'','description'=>"Partez à la conquête des étoiles dans cet immense RPG spatial où vous explorez plus de 1000 planètes générées procéduralement.",'date_sortie'=>null],
    ];
    $genres = ['Action','Action-RPG','Aventure','Course','Exploration','Mythologie','Roguelike','Science-fiction'];
}

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
  <div class="filter-genres">
    <button class="filter-btn active" data-genre="tous">Tous</button>
    <?php foreach ($genres as $g): ?>
      <button class="filter-btn" data-genre="<?= htmlspecialchars(strtolower($g)) ?>">
        <?= htmlspecialchars($g) ?>
      </button>
    <?php endforeach; ?>
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
    <div class="cat-section-count"><?= count($jeux) ?> jeux</div>
  </div>

  <div class="catalogue-grid">
    <?php foreach ($jeux as $j):
      $tags = !empty($j['genre'])
          ? array_filter(array_map('trim', explode(',', $j['genre'])))
          : [];
      $firstGenre = $tags ? strtolower(reset($tags)) : '';
    ?>
    <a class="catalogue-card"
       href="/NEBULA/produit.php?id=<?= (int)$j['id_jeu'] ?>"
       data-genre="<?= htmlspecialchars($firstGenre) ?>"
       data-title="<?= htmlspecialchars(strtolower($j['titre'])) ?>">

      <div class="catalogue-card-poster">
        <?php if (!empty($j['image_url'])): ?>
          <img src="/NEBULA/<?= htmlspecialchars($j['image_url']) ?>"
               alt="<?= htmlspecialchars($j['titre']) ?>"
               loading="lazy">
        <?php else: ?>
          <div class="catalogue-card-placeholder"></div>
        <?php endif; ?>
      </div>

      <div class="catalogue-card-overlay">
        <?php if (!empty($tags)): ?>
          <div class="catalogue-card-tags">
            <?php foreach (array_slice($tags, 0, 2) as $tag): ?>
              <span><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="catalogue-card-title"><?= htmlspecialchars($j['titre']) ?></div>
        <?php if (!empty($j['description'])): ?>
          <div class="catalogue-card-desc"><?= htmlspecialchars($j['description']) ?></div>
        <?php endif; ?>
        <div class="catalogue-play-btn">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          Jouer
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <div id="noResults" class="no-results" style="display:none">
    Aucun jeu ne correspond à votre recherche.
  </div>
</div>

<?php require 'includes/footer.php'; ?>
