<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<section class="hero-section">
    <div class="container center-text">
        <h1 class="hero-title">
            Jouez à vos jeux <br>
            <span class="highlight-text">n’importe où</span>
        </h1>
        
        <p class="hero-subtitle">
            Profitez de vos jeux préférés en streaming haute qualité sans téléchargement, sur tous vos appareils
        </p>

        <div class="cta-group">
            <a href="login.php" class="btn-gradient">▷ Commencer gratuitement</a>
            <a href="demo.php" class="btn-glass">Voir la demo</a>
        </div>

        <div class="features-strip">
            <div class="feature-item">
                <span class="icon">4K</span> Ultra HD
            </div>
            <div class="feature-item">
                <img src="assets/img/icon_monitor.png" alt="Ecran" class="feature-icon"> 144 FPS
            </div>
            <div class="feature-item">
                <img src="assets/img/icon_flash.png" alt="Latence" class="feature-icon"> Latence ultra-faible
            </div>
        </div>
    </div>
</section>

<section class="library-section">
    <div class="container">
        <div class="section-header center-text">
            <h2>Une bibliothèque infinie</h2>
            <p>Des centaines de jeux AAA disponibles instantanément, de nouvelles sorties chaque mois</p>
        </div>

        <div class="games-grid">
            
            <article class="game-card">
                <div class="card-image">
                    <img src="assets/img/arc_raiders.jpg" alt="ARC Raiders">
                </div>
                <div class="card-content">
                    <h3>ARC Raiders</h3>
                    <p class="game-desc">
                        Sur une Terre dévastée, vous affrontez les ARC, des machines impitoyables...
                    </p>
                    <ul class="game-tags">
                        <li>Extraction Shooter</li>
                        <li>PvPvE</li>
                        <li>Science-fiction</li>
                    </ul>
                    <div class="card-footer">
                        <span class="developer">Développé par Embark Studios</span>
                        <div class="platforms">
                            <span>Xbox</span> <span>PS</span> <span>PC</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="game-card highlight-card">
                <div class="card-image">
                    <img src="assets/img/cyberpunk.jpg" alt="Cyberpunk 2077">
                </div>
                <div class="card-content">
                    <h3>Cyberpunk</h3>
                    <p class="game-desc">
                        Dans la mégalopole de Night City, vous incarnez V, un mercenaire hors-la-loi...
                    </p>
                    <ul class="game-tags">
                        <li>Action-RPG</li>
                        <li>Monde ouvert</li>
                        <li>Cyberpunk</li>
                    </ul>
                    <div class="card-footer">
                        <span class="developer">Développé par CD Projekt</span>
                        <div class="platforms">
                            <span>Xbox</span> <span>PS</span> <span>PC</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="game-card">
                <div class="card-image">
                    <img src="assets/img/nomanssky.jpg" alt="No Man's Sky">
                </div>
                <div class="card-content">
                    <h3>No Man's Sky</h3>
                    <p class="game-desc">
                        Propulsé dans un univers infini, voyagez de planète en planète pour découvrir...
                    </p>
                    <ul class="game-tags">
                        <li>Exploration</li>
                        <li>Survie</li>
                        <li>Bac à sable</li>
                    </ul>
                    <div class="card-footer">
                        <span class="developer">Développé par Hello Games</span>
                        <div class="platforms">
                            <img src="assets/img/logo_xbox.png" alt="Xbox" class="platform-icon">
                            <img src="assets/img/logo_ps5.png" alt="PlayStation" class="platform-icon">
                            <img src="assets/img/logo_pc.png" alt="PC" class="platform-icon">
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>