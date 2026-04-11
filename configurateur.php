<?php
/* ============================================================
   CONFIGURATEUR.PHP — Configurateur de bouquet personnalisé
   Permet à l'utilisateur de composer son offre sur mesure :
   - Étape 1 : choisir des accessoires (merch)
   - Étape 2 : choisir une offre d'abonnement
   - Étape 3 : ajouter des options supplémentaires
   Un résumé sticky en sidebar calcule le total en temps réel.
   Le JS (configurateur.js) gère les interactions côté client.
   ============================================================ */

// -- Connexion BDD et session --
require 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// -- Configuration de la page --
$pageTitle = 'Mon Bouquet';
$pageCSS   = ['configurateur'];
$pageJS    = ['configurateur'];

// -- Inclure le header commun --
require 'includes/header.php';
?>

<div class="config-page">

<!-- ── Hero : titre et description du configurateur ───────────── -->
<div class="config-hero">
  <div class="config-hero-orb config-hero-orb-a"></div>
  <div class="config-hero-orb config-hero-orb-b"></div>
  <div class="config-hero-inner">
    <h1 class="config-hero-title">Composez votre <span class="gradient-text">bouquet</span></h1>
    <p class="config-hero-sub">Payez uniquement pour ce que vous utilisez. Sélectionnez vos genres favoris, votre qualité de streaming et les options qui vous correspondent.</p>
  </div>
</div>

<!-- ── Layout principal : étapes à gauche, résumé à droite ───── -->
<div class="config-layout">
  <div class="config-steps-col">

    <!-- ── Étape 1 : Accessoires Nebula (choix multiple) ─────
         Chaque bouton a data-produit, data-nom et data-prix.
         Au clic, le JS ajoute/retire la classe "selected" sur
         la carte et ajoute/soustrait le prix dans prixBoutique. ── -->
    <div class="config-card">
      <div class="config-card-head">
        <div class="config-card-title">
          <span class="config-step-num">1</span>
          Accessoires Nebula
        </div>
        <span class="config-step-sub">Collection exclusive</span>
      </div>

      <div class="merch-grid">
        <!-- T-Shirt -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-tshirt.png" alt="T-Shirt Nebula">
          </div>
          <div class="merch-body">
            <div class="merch-category">Vêtement</div>
            <div class="merch-name">T-Shirt Nebula</div>
            <div class="merch-desc">100% coton · Noir · Logo violet</div>
            <div class="merch-footer">
              <div class="merch-price">29,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="tshirt" data-nom="T-Shirt Nebula" data-prix="29.99">Ajouter</button>
            </div>
          </div>
        </div>

        <!-- Hoodie -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-hoodie.png" alt="Hoodie Nebula">
          </div>
          <div class="merch-body">
            <div class="merch-category">Vêtement</div>
            <div class="merch-name">Hoodie Nebula</div>
            <div class="merch-desc">Coton doux · Poche kangourou · Unisexe</div>
            <div class="merch-footer">
              <div class="merch-price">49,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="hoodie" data-nom="Hoodie Nebula" data-prix="49.99">Ajouter</button>
            </div>
          </div>
        </div>

        <!-- Mug -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-mug.png" alt="Mug Gaming">
          </div>
          <div class="merch-body">
            <div class="merch-category">Accessoire</div>
            <div class="merch-name">Mug Gaming</div>
            <div class="merch-desc">Céramique · 350ml · Thermosensible</div>
            <div class="merch-footer">
              <div class="merch-price">14,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="mug" data-nom="Mug Gaming" data-prix="14.99">Ajouter</button>
            </div>
          </div>
        </div>

        <!-- Casquette -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-casquette.png" alt="Casquette Nebula">
          </div>
          <div class="merch-body">
            <div class="merch-category">Vêtement</div>
            <div class="merch-name">Casquette Nebula</div>
            <div class="merch-desc">Snapback · Brodé · Réglable</div>
            <div class="merch-footer">
              <div class="merch-price">24,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="casquette" data-nom="Casquette Nebula" data-prix="24.99">Ajouter</button>
            </div>
          </div>
        </div>

        <!-- Mousepad -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-mousepad.png" alt="Mousepad XXL">
          </div>
          <div class="merch-body">
            <div class="merch-category">Accessoire</div>
            <div class="merch-name">Mousepad XXL</div>
            <div class="merch-desc">900x400mm · Surface lisse · Base antidérapante</div>
            <div class="merch-footer">
              <div class="merch-price">19,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="mousepad" data-nom="Mousepad XXL" data-prix="19.99">Ajouter</button>
            </div>
          </div>
        </div>

        <!-- Stickers -->
        <div class="merch-card">
          <div class="merch-img">
            <img src="/NEBULA/public/assets/img/merch-stickers.png" alt="Pack Stickers">
          </div>
          <div class="merch-body">
            <div class="merch-category">Accessoire</div>
            <div class="merch-name">Pack Stickers</div>
            <div class="merch-desc">15 stickers · Vinyle waterproof · Mix designs</div>
            <div class="merch-footer">
              <div class="merch-price">9,99 €</div>
              <button class="btn btn-outline btn-sm" data-produit="stickers" data-nom="Pack Stickers" data-prix="9.99">Ajouter</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Étape 2 : Offre d'abonnement (choix unique) ─────────
         3 cartes (Starter/Gamer/Ultra). Chaque bouton porte
         data-plan et data-price. Au clic le JS désélectionne
         toutes les cartes puis sélectionne celle cliquée.
         Gamer est sélectionné par défaut au chargement. ────── -->
    <div class="config-card">
      <div class="config-card-head">
        <div class="config-card-title">
          <span class="config-step-num">2</span>
          Votre offre
        </div>
        <span class="config-step-sub">Choisissez votre abonnement</span>
      </div>

      <div class="pricing-grid" id="pricingGrid">
        <!-- Starter -->
        <div class="pricing-card">
          <div class="pricing-name">Starter</div>
          <div class="pricing-price">
            <span class="price-amount">Gratuit</span>
          </div>
          <div class="pricing-sub">Parfait pour découvrir le cloud gaming</div>
          <ul class="pricing-features">
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">10h de jeu par mois</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Qualité HD (720p)</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Accès à +25 jeux</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Latence standard</li>
            <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img">Ray tracing</li>
            <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img">Accès prioritaire</li>
            <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img">Support 24/7</li>
          </ul>
          <button class="btn btn-outline btn-full" data-plan="starter" data-price="0">Sélectionner</button>
        </div>

        <!-- Gamer -->
        <div class="pricing-card featured">
          <div class="pricing-badge">Populaire</div>
          <div class="pricing-name">Gamer</div>
          <div class="pricing-price">
            <span class="price-amount">24.99</span><span class="price-period"> €/mois</span>
          </div>
          <div class="pricing-sub">Pour les joueurs réguliers</div>
          <ul class="pricing-features">
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Jouez en illimité</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Qualité 4K Ultra HD</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Accès à +200 jeux</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Latence ultra-faible</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Ray tracing</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Sauvegardes illimitées</li>
            <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img">Support prioritaire</li>
          </ul>
          <button class="btn btn-primary btn-full" data-plan="gamer" data-price="24.99">Sélectionner</button>
        </div>

        <!-- Ultra -->
        <div class="pricing-card">
          <div class="pricing-name">Ultra</div>
          <div class="pricing-price">
            <span class="price-amount">44.99</span><span class="price-period"> €/mois</span>
          </div>
          <div class="pricing-sub">L'expérience gaming ultime</div>
          <ul class="pricing-features">
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Tout de Gamer +</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Sessions prioritaires</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Support 24/7</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Accès anticipé aux nouveautés</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Streaming simultané (2 appareils)</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Cadeaux exclusifs</li>
            <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">Support prioritaire</li>
          </ul>
          <button class="btn btn-outline btn-full" data-plan="ultra" data-price="44.99">Sélectionner</button>
        </div>
      </div>
    </div>

    <!-- ── Étape 3 : Options supplémentaires (choix multiple) ──
         Même principe que l'étape 1 : au clic, le JS toggle
         la classe "selected" et ajoute/soustrait le data-price
         dans prixOptions. ─────────────────────────────────────── -->
    <div class="config-card">
      <div class="config-card-head">
        <div class="config-card-title">
          <span class="config-step-num">3</span>
          Options supplémentaires
        </div>
        <span class="config-step-sub">Enrichissez votre expérience</span>
      </div>

      <div class="options-grid" id="optionsGrid">
        <!-- Ray Tracing -->
        <div class="option-card">
          <div class="option-icon">
            <img src="/NEBULA/public/assets/img/icons/platforms/nvidia.png" alt="icon" width="24" height="24" class="icon-img">
          </div>
          <div class="option-body">
            <div class="option-name">Ray Tracing</div>
            <div class="option-desc">Éclairage et reflets photoréalistes en temps réel</div>
            <div class="option-price">+5,00 €/mois</div>
            <button class="btn btn-outline btn-sm btn-full" data-option="raytracing" data-price="5">Ajouter</button>
          </div>
        </div>

        <!-- Sauvegardes illimitées -->
        <div class="option-card">
          <div class="option-icon">
            <img src="/NEBULA/public/assets/img/icons/platforms/database.png" alt="icon" width="24" height="24" class="icon-img">
          </div>
          <div class="option-body">
            <div class="option-name">Sauvegardes illimitées</div>
            <div class="option-desc">Historique complet et stockage cloud sans limite</div>
            <div class="option-price">+3,00 €/mois</div>
            <button class="btn btn-outline btn-sm btn-full" data-option="savecloud" data-price="3">Ajouter</button>
          </div>
        </div>

        <!-- Support prioritaire -->
        <div class="option-card">
          <div class="option-icon">
            <img src="/NEBULA/public/assets/img/icons/dashboard/support.png" alt="icon" width="24" height="24" class="icon-img">
          </div>
          <div class="option-body">
            <div class="option-name">Support prioritaire</div>
            <div class="option-desc">Réponse garantie en moins de 2h, 7j/7</div>
            <div class="option-price">+4,00 €/mois</div>
            <button class="btn btn-outline btn-sm btn-full" data-option="support" data-price="4">Ajouter</button>
          </div>
        </div>

        <!-- Multi-appareils -->
        <div class="option-card">
          <div class="option-icon">
            <img src="/NEBULA/public/assets/img/icons/platforms/multi.png" alt="icon" width="24" height="24" class="icon-img">
          </div>
          <div class="option-body">
            <div class="option-name">Multi-appareils</div>
            <div class="option-desc">Jouez sur 2 appareils simultanément</div>
            <div class="option-price">+6,00 €/mois</div>
            <button class="btn btn-outline btn-sm btn-full" data-option="multidevice" data-price="6">Ajouter</button>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /.config-steps-col -->

  <!-- ── Sidebar résumé sticky ────────────────────────────────
       La fonction majResume() du JS met à jour :
       - #summaryTotal     → total général (boutique + offre + options)
       - #summaryPlanName  → nom du plan choisi
       - #summaryPlanPrice → prix du plan
       Le bouton Commander (#summaryOrderBtn) redirige
       vers panier.php avec le total en paramètre GET. ──────── -->
  <aside class="config-summary-col">
    <div class="config-card">
      <div class="config-card-head summary-header">
        <div class="config-card-title">
          <img src="/NEBULA/public/assets/img/icons/dashboard/colis.png" alt="" width="16" height="16">
          Mon bouquet
        </div>
      </div>

      <div class="summary-content">
        <div class="summary-section">
          <div class="summary-label">Accessoires</div>
          <div id="summaryBoutique"><span class="summary-empty">Aucun article</span></div>
        </div>

        <div class="summary-section">
          <div class="summary-label">Offre</div>
          <div class="summary-row"><span id="summaryPlanName">-</span><span id="summaryPlanPrice">-</span></div>
        </div>

        <div class="summary-section">
          <div class="summary-label">Options</div>
          <div id="summaryOptionsList"><span class="summary-empty">Aucune option</span></div>
        </div>

        <div class="summary-total">
          <div class="summary-total-row">
            <span>Total mensuel</span>
            <span id="summaryTotal" class="summary-total-price">0 €</span>
          </div>
          <div class="summary-total-note">TVA incluse · Sans engagement</div>
        </div>

        <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full" id="summaryOrderBtn">Commander</a>

        <div class="summary-trust">
          <span><img src="/NEBULA/public/assets/img/icons/dashboard/bouclier-securite.png" alt="" width="14" height="14">Paiement sécurisé</span>
          <span><img src="/NEBULA/public/assets/img/icons/dashboard/clic.png" alt="" width="14" height="14">Annulation facile</span>
        </div>
      </div>
    </div>
  </aside>

</div><!-- /.config-layout -->

</div><!-- /.config-page -->

<?php require 'includes/footer.php'; ?>
