<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// 1. Récupération des jeux "Tendance"
// On prend 12 jeux au hasard pour simuler une sélection dynamique
try {
    $stmt = $pdo->query("SELECT * FROM jeu ORDER BY RAND() LIMIT 12");
    $jeux = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur lors du chargement des jeux.";
}
?>

<section class="trending-games-section">
    <div class="container">
        <div class="section-header center-text">
            <h1 class="page-title">Les Hits du Moment 🔥</h1>
            <p class="page-subtitle">
                Les jeux les plus joués sur NEBULA cette semaine.<br>
                Lancez-les instantanément en 4K, sans téléchargement.
            </p>
        </div>

        <div class="filter-bar">
            <button class="filter-btn active">Tout</button>
            <button class="filter-btn">Action</button>
            <button class="filter-btn">RPG</button>
            <button class="filter-btn">Sport</button>
            <button class="filter-btn">FPS</button>
        </div>

        <div class="games-grid">
            <?php if (count($jeux) > 0): ?>
                
                <?php foreach ($jeux as $jeu): ?>
                    <article class="game-card">
                        <div class="card-image">
                            <?php 
                                $image = !empty($jeu['image_url']) ? $jeu['image_url'] : 'assets/img/default_game.jpg'; 
                            ?>
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($jeu['titre']) ?>">
                            
                            <div class="badge-overlay">⚡ CLOUD</div>
                        </div>
                        
                        <div class="card-content">
                            <div class="card-header-flex">
                                <h3><?= htmlspecialchars($jeu['titre']) ?></h3>
                                <span class="genre-tag"><?= htmlspecialchars($jeu['genre']) ?></span>
                            </div>
                            
                            <p class="game-desc-short">
                                <?= substr(htmlspecialchars($jeu['description'] ?? 'Description à venir...'), 0, 60) ?>...
                            </p>

                            <div class="card-footer">
                                <button class="btn-play-small">Lancer ▶</button>
                                <span class="rating">⭐ 4.8/5</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>

            <?php else: ?>
                <p>Aucun jeu disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        
        <div class="show-more-container center-text">
            <br><br>
            <a href="#" class="btn-glass">Voir tout le catalogue (+500 jeux)</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>