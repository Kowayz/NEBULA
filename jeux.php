<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// 1. Récupération des jeux depuis la base de données
// On sélectionne tout, trié par ordre d'ajout (ou par nom si tu préfères)
try {
    $stmt = $pdo->query("SELECT * FROM jeu ORDER BY id_jeu DESC");
    $jeux = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur lors du chargement des jeux.";
}
?>

<section class="games-catalogue-section">
    <div class="container">
        <h1 class="page-title">Notre Bibliothèque Cloud</h1>
        <p class="page-subtitle">Accédez instantanément à une collection évolutive de hits PC et Console.</p>

        <div class="search-bar-container">
            <input type="text" placeholder="Rechercher un jeu (Cyberpunk, Elden Ring...)" class="search-input">
            <button class="btn-search">🔍</button>
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
                        </div>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($jeu['titre']) ?></h3>
                            
                            <ul class="game-tags">
                                <li><?= htmlspecialchars($jeu['genre']) ?></li>
                                <li>Cloud Ready</li>
                            </ul>
                            
                            <div class="card-footer">
                                <span class="date-sortie">Sortie : <?= date('d/m/Y', strtotime($jeu['date_sortie'])) ?></span>
                                <button class="btn-play">Jouer ▶</button>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>

            <?php else: ?>
                <p>Aucun jeu disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>