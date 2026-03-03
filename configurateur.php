<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<section class="config-header">
    <div class="container center-text">
        <h1>Configurateur NEBULA Setup</h1>
        <p>Créez votre configuration personnalisée. Le prix se met à jour en temps réel.</p>
    </div>
</section>

<section class="configurator">
    <div class="container config-flex">
        
        <form id="nebula-config-form" class="config-main">
            <div class="config-step">
                <div class="step-number">1</div>
                <h2>Choisissez votre abonnement</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="offre" value="0" data-price="0" checked>
                        <div class="option-info">
                            <span class="title">Starter</span>
                            <span class="price">Gratuit</span>
                            <ul><li>10h de jeu / mois</li><li>Qualité HD</li></ul>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="offre" value="9.99" data-price="9.99">
                        <div class="option-info">
                            <span class="title">Gamer</span>
                            <span class="price">9.99€/mois</span>
                            <ul><li>Jeu illimité</li><li>Qualité 4K</li></ul>
                        </div>
                    </label>
                </div>
            </div>

            <div class="config-step">
                <div class="step-number">2</div>
                <h2>Ajoutez des accessoires (optionnel)</h2>
                <div class="options-grid">
                    <label class="option-card check">
                        <input type="checkbox" name="acc[]" value="79.99" data-name="Manette NEBULA Pro">
                        <div class="option-info">
                            <span class="title">Manette NEBULA Pro</span>
                            <span class="price">79.99€</span>
                        </div>
                    </label>
                    <label class="option-card check">
                        <input type="checkbox" name="acc[]" value="149.99" data-name="Routeur Gaming">
                        <div class="option-info">
                            <span class="title">Routeur Gaming Wi-Fi 6</span>
                            <span class="price">149.99€</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="config-step">
                <div class="step-number">3</div>
                <h2>Durée d'engagement</h2>
                <div class="range-container">
                    <p>Nombre de mois : <span id="month-value">1</span></p>
                    <input type="range" min="1" max="24" value="1" class="slider" id="engagement">
                    <div class="range-labels"><span>1 mois</span><span>12 mois</span><span>24 mois</span></div>
                </div>
            </div>
        </form>

        <aside class="config-sidebar">
            <div class="summary-card">
                <h3>Récapitulatif</h3>
                <div class="summary-line"><span>Abonnement</span> <span id="summary-plan">Starter</span></div>
                <div class="summary-line"><span>Durée</span> <span id="summary-duration">1 mois</span></div>
                <hr>
                <div class="summary-line total"><span>Coût mensuel</span> <span id="monthly-total">0.00€</span></div>
                <div class="summary-line total"><span>Paiement unique</span> <span id="one-time-total">0.00€</span></div>
                <button type="button" class="btn-gradient full-width">🛒 Ajouter au panier</button>
            </div>
        </aside>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>