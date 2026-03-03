<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<section class="pricing-page-header">
    <div class="container center-text">
        <h1>Nos Abonnements</h1>
        <p>Choisissez l'offre qui correspond à vos besoins. Changez ou annulez à tout moment.</p>
    </div>
</section>

<section class="pricing-section">
    <div class="container">
        <div class="pricing-grid">
            
            <div class="pricing-card">
                <h3 class="plan-name">Starter</h3>
                <p class="plan-desc">Parfait pour découvrir le cloud gaming</p>
                <div class="price">Gratuit</div>
                
                <ul class="features-list">
                    <li class="check">10 heures de jeu / mois</li>
                    <li class="check">Qualité HD (720p)</li>
                    <li class="check">Accès à 50+ jeux</li>
                    <li class="check">Latence standard</li>
                    <li class="cross">Qualité 4K</li>
                    <li class="cross">Ray tracing</li>
                    <li class="cross">Support prioritaire</li>
                </ul>
                <a href="configurateur.php?offre=starter" class="btn-glass full-width">S'abonner</a>
            </div>

            <div class="pricing-card popular">
                <div class="badge-popular">★ Plus Populaire</div>
                <h3 class="plan-name">Gamer</h3>
                <p class="plan-desc">Pour les joueurs réguliers</p>
                <div class="price">9.99<span>€ /mois</span></div>
                
                <ul class="features-list">
                    <li class="check">Jeu illimité</li>
                    <li class="check">Qualité 4K Ultra HD</li>
                    <li class="check">Accès à 300+ jeux</li>
                    <li class="check">Latence ultra-faible</li>
                    <li class="check">Ray tracing activé</li>
                    <li class="check">Sauvegardes illimitées</li>
                    <li class="cross">Support prioritaire</li>
                </ul>
                <a href="configurateur.php?offre=gamer" class="btn-gradient full-width">S'abonner</a>
            </div>

            <div class="pricing-card">
                <h3 class="plan-name">Ultimate</h3>
                <p class="plan-desc">L'expérience gaming ultime</p>
                <div class="price">19.99<span>€ /mois</span></div>
                
                <ul class="features-list">
                    <li class="check">Tout de Gamer +</li>
                    <li class="check">Sessions prioritaires</li>
                    <li class="check">Support 24/7</li>
                    <li class="check">Accès anticipé aux nouveautés</li>
                    <li class="check">Streaming simultané (2 appareils)</li>
                    <li class="check">Profils multiples</li>
                    <li class="check">Cadeaux exclusifs mensuels</li>
                </ul>
                <a href="configurateur.php?offre=ultimate" class="btn-glass full-width">S'abonner</a>
            </div>
        </div>
    </div>
</section>

<section class="comparison-section">
    <div class="container">
        <h2 class="center-text">Tableau Comparatif Détaillé</h2>
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Fonctionnalité</th>
                    <th>Starter<br><span>Gratuit</span></th>
                    <th>Gamer<br><span>9.99€/mois</span></th>
                    <th>Ultimate<br><span>19.99€/mois</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Résolution 4K Ultra HD</td>
                    <td class="cross">✕</td>
                    <td class="check">✓</td>
                    <td class="check">✓</td>
                </tr>
                <tr>
                    <td>Jeu illimité</td>
                    <td class="cross">✕</td>
                    <td class="check">✓</td>
                    <td class="check">✓</td>
                </tr>
                </tbody>
        </table>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>