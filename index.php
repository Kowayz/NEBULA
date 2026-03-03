<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<section class="hero-section hero-bg">
    <div class="container center-text">
        <h1 class="hero-title">
            Jouez à vos jeux <br>
            <span class="highlight-text">n'importe où</span>
        </h1>
        
        <p class="hero-subtitle">
            Profitez de vos jeux préférés en streaming haute qualité sans téléchargement, sur tous vos appareils
        </p>

        <div class="cta-group">
            <a href="login.php" class="btn-primary">
                <img src="assets/img/icon-play.png" alt="Play" class="btn-icon"> Commencer gratuitement
            </a>
            <a href="demo.php" class="btn-glass">Voir la démo</a>
        </div>

        <div class="features-strip">
            <div class="feature-item"><span class="dot-green"></span> 4K Ultra HD</div>
            <div class="feature-item"><span class="dot-green"></span> 60 FPS</div>
            <div class="feature-item"><span class="dot-green"></span> Latence ultra-faible</div>
        </div>

        <div class="scroll-indicator">
            <img src="assets/img/icon-mouse.png" alt="Scroll">
        </div>
    </div>
</section>

<section class="library-section">
    <div class="container">
        </div>
</section>

<?php require_once 'includes/footer.php'; ?>