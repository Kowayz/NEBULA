<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<section class="pricing-section">
    <div class="container">
        <div class="pricing-grid">
            
            <div class="pricing-card">
                <h3 class="plan-name">Starter</h3>
                <p class="plan-desc">Parfait pour découvrir le cloud gaming</p>
                <div class="price">Gratuit</div>
                
                <ul class="features-list">
                    <li class="check">10h de jeu par mois</li>
                    <li class="check">Qualité HD (720p)</li>
                    <li class="check">Accès à +25 jeux</li>
                    <li class="check">Latence standard</li>
                    <li class="cross">Ray tracing</li>
                    <li class="cross">Accès prioritaire</li>
                </ul>

                <a href="configurateur.php?offre=starter" class="btn-glass full-width">S'abonner</a>
            </div>

            <div class="pricing-card popular">
                <div class="badge-popular">★ Plus populaire</div>
                <h3 class="plan-name">Gamer</h3>
                <p class="plan-desc">Pour les joueurs réguliers</p>
                <div class="price">24.99 <span>€/mois</span></div>
                
                <ul class="features-list">
                    <li class="check">Jouez en illimité</li>
                    <li class="check">Qualité 4K Ultra HD</li>
                    <li class="check">Accès à +200 jeux</li>
                    <li class="check">Latence ultra-faible</li>
                    <li class="check">Ray tracing</li>
                    <li class="check">Sauvegardes illimitées</li>
                    <li class="cross">Support prioritaire</li>
                </ul>

                <a href="configurateur.php?offre=gamer" class="btn-gradient full-width">S'abonner</a>
            </div>

            <div class="pricing-card">
                <h3 class="plan-name">Ultra</h3>
                <p class="plan-desc">L'expérience gaming ultime</p>
                <div class="price">44.99 <span>€/mois</span></div>
                
                <ul class="features-list">
                    <li class="check">Tout de Gamer +</li>
                    <li class="check">Sessions prioritaires</li>
                    <li class="check">Support 24/7</li>
                    <li class="check">Accès anticipé aux nouveautés</li>
                    <li class="check">Streaming simultané (2 appareils)</li>
                    <li class="check">Cadeaux exclusifs</li>
                </ul>

                <a href="configurateur.php?offre=ultra" class="btn-glass full-width">S'abonner</a>
            </div>

        </div>
    </div>
</section>

<section class="comparison-section">
    <div class="container">
        <div class="comparison-box">
            <h2 class="center-text">Tableau Comparatif Détaillé</h2>
            
            <table class="compare-table">
                <thead>
                    <tr>
                        <th style="text-align: left;">Fonctionnalité</th>
                        <th>Starter</th>
                        <th>Gamer</th>
                        <th>Ultra</th>
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
                        <td>Grande bibliothèque</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                        <td class="check">✓</td>
                    </tr>
                    <tr>
                        <td>Ray Tracing</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                        <td class="check">✓</td>
                    </tr>
                    <tr>
                        <td>Latence ultra-faible</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                        <td class="check">✓</td>
                    </tr>
                    <tr>
                        <td>Sessions prioritaires</td>
                        <td class="cross">✕</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                    </tr>
                    <tr>
                        <td>Streaming multi-appareils</td>
                        <td class="cross">✕</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                    </tr>
                    <tr>
                        <td>Accès anticipé aux nouveaux jeux</td>
                        <td class="cross">✕</td>
                        <td class="cross">✕</td>
                        <td class="check">✓</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="custom-bouquet-section">
    <div class="container center-text">
        <h2>Possibilité de créer son bouquet</h2>
        <p>Prenez les commandes de votre catalogue. Pourquoi s'adapter à une liste prédéfinie quand vous pouvez composer la vôtre ?</p>
        <br>
        <a href="configurateur.php" class="btn-gradient">Créer mon bouquet</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>