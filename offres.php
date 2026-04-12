<?php
/* ============================================================
   OFFRES.PHP — Page des offres d'abonnement
   Affiche les 3 plans (Starter, Gamer, Ultra), un tableau
   comparatif détaillé et un CTA vers le configurateur.
   ============================================================ */

// -- Configuration de la page --
$pageTitle = 'Nos offres';
$pageCSS   = ['offres'];
$pageJS    = [];

// -- Inclure le header commun --
require 'includes/header.php';
?>

<!-- ══════════════════════════ CARTES TARIFS ═══════════════════
     Titre + 3 cartes : Starter (gratuit), Gamer (24.99€), Ultra (44.99€)
     La carte "Gamer" a la classe "featured" (mise en avant)
     ══════════════════════════════════════════════════════════ -->
<section class="section text-center">
  <h1 class="offres-title">
    Choisissez votre <span class="gradient-text">offre</span>
  </h1>
  <p class="text-muted">Commencez gratuitement, évoluez quand vous le souhaitez. Sans engagement.</p>
  <div class="pricing-grid">

    <!-- Starter : offre gratuite d'entrée -->
    <div class="pricing-card">
      <div class="pricing-name">Starter</div>
      <div class="pricing-price">
        <span class="price-amount">Gratuit</span>
      </div>
      <div class="pricing-sub">Parfait pour découvrir le cloud gaming</div>
      <ul class="pricing-features">
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> 10h de jeu par mois</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Qualité HD (720p)</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Accès à +25 jeux</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Latence standard</li>
        <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img"> Ray tracing</li>
        <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img"> Accès prioritaire</li>
        <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img"> Support 24/7</li>
      </ul>
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-outline btn-full">Commencer gratuitement</a>
    </div>

    <!-- Gamer : offre populaire (mise en avant) -->
    <div class="pricing-card featured">
      <div class="pricing-badge">Populaire</div>
      <div class="pricing-name">Gamer</div>
      <div class="pricing-price">
        <span class="price-amount">24.99</span><span class="price-period"> €/mois</span>
      </div>
      <div class="pricing-sub">Pour les joueurs réguliers</div>
      <ul class="pricing-features">
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Jouez en illimité</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Qualité 4K Ultra HD</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Accès à +200 jeux</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Latence ultra-faible</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Ray tracing</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Sauvegardes illimitées</li>
        <li class="pricing-feature"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="icon" width="14" height="14" class="icon-img"> Support prioritaire</li>
      </ul>
      <a href="/NEBULA/panier.php?add=200&amp;nom=Offre+Gamer+(mensuel)&amp;prix=24.99&amp;cat=offre" class="btn btn-primary btn-full">S'abonner</a>
    </div>

    <!-- Ultra : offre premium complète -->
    <div class="pricing-card">
      <div class="pricing-name">Ultra</div>
      <div class="pricing-price">
        <span class="price-amount">44.99</span><span class="price-period"> €/mois</span>
      </div>
      <div class="pricing-sub">L'expérience gaming ultime</div>
      <ul class="pricing-features">
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Tout de Gamer +</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Sessions prioritaires</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Support 24/7</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Accès anticipé aux nouveautés</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Streaming simultané (2 appareils)</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Cadeaux exclusifs</li>
        <li class="pricing-feature yes"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img"> Support prioritaire</li>
      </ul>
      <a href="/NEBULA/panier.php?add=201&amp;nom=Offre+Ultra+(mensuel)&amp;prix=44.99&amp;cat=offre" class="btn btn-outline btn-full">S'abonner</a>
    </div>

  </div>
</section>

<!-- ══════════════════════════ TABLEAU COMPARATIF ══════════════
     Grille comparant les fonctionnalités des 3 offres
     (coche = inclus, croix = non disponible)
     ══════════════════════════════════════════════════════════ -->
<section class="section section--alt">
  <h2 class="text-center compare-title">
    Tableau comparatif détaillé
  </h2>
  <div class="compare-wrap">
    <!-- En-tête du tableau -->
    <div class="compare-row compare-head">
      <div class="compare-label">Fonctionnalité</div>
      <div class="compare-cell">Starter</div>
      <div class="compare-cell compare-featured">Gamer</div>
      <div class="compare-cell">Ultra</div>
    </div>
    <!-- Lignes de comparaison -->
    <div class="compare-row">
      <div class="compare-label">Résolution 4K Ultra HD</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Grande bibliothèque (+200 jeux)</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Ray Tracing</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Latence ultra-faible</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Sessions prioritaires</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Streaming multi-appareils</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
    <div class="compare-row">
      <div class="compare-label">Accès anticipé aux nouveaux jeux</div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell compare-featured"><img src="/NEBULA/public/assets/img/icons/nav/croix-fermer.png" alt="non" width="14" height="14" class="icon-img"></div>
      <div class="compare-cell"><img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="oui" width="14" height="14" class="icon-img"></div>
    </div>
  </div>
</section>

<!-- ══════════════════════════ CTA CONFIGURATEUR ═══════════════
     Bandeau d'appel à l'action vers le configurateur de bouquet
     ══════════════════════════════════════════════════════════ -->
<section class="section text-center">
  <h2 class="offres-cta-title">Créez votre bouquet sur mesure</h2>
  <p class="offres-cta-sub">Pourquoi s'adapter à une liste prédéfinie quand vous pouvez composer la vôtre ?</p>
  <a href="/NEBULA/configurateur.php" class="btn btn-primary btn-lg">Configurer mon bouquet</a>
</section>

<?php require 'includes/footer.php'; ?>
