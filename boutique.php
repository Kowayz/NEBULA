<?php
/* ============================================================
   BOUTIQUE.PHP — Page boutique / merchandising
   Affiche les produits dérivés Nebula (t-shirts, mugs, etc.),
   les garanties de paiement et un CTA vers le configurateur.
   Chaque produit propose un lien "Ajouter" vers panier.php.
   ============================================================ */

// -- Configuration de la page (titre, CSS, JS) --
$pageTitle = 'Boutique';
$pageCSS   = ['boutique'];
$pageJS    = [];

// -- Inclure le header commun --
require 'includes/header.php';
?>

<div class="boutique-page">

<!-- ══════════════════════════ HERO ══════════════════════════
     Bannière avec orbes décoratives et titre "Nebula Merch"
     ══════════════════════════════════════════════════════════ -->
<div class="boutique-hero">
  <div class="boutique-hero-orb boutique-hero-orb-a"></div>
  <div class="boutique-hero-orb boutique-hero-orb-b"></div>
  <div class="boutique-hero-inner">
    <div class="boutique-hero-tag">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="18" height="18" class="icon-img">
      Boutique
    </div>
    <h1 class="boutique-hero-title">Nebula <span class="gradient-text">Merch</span></h1>
    <p class="boutique-hero-sub">T-shirts, mugs et accessoires pour les vrais fans de gaming.</p>
  </div>
</div>

<!-- ══════════════════════════ GRILLE PRODUITS ═════════════════
     6 cartes produits (merch-card) avec image, nom, description,
     prix et bouton d'ajout au panier via panier.php?add=
     ══════════════════════════════════════════════════════════ -->
<div class="boutique-section">
  <div class="boutique-section-header">
    <div class="boutique-section-title">Produits</div>
    <div class="boutique-section-sub">Collection exclusive Nebula</div>
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
          <a href="/NEBULA/panier.php?add=1&amp;type=produit&amp;nom=T-Shirt+Nebula&amp;prix=29.99" class="btn btn-outline btn-sm">Ajouter</a>
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
          <a href="/NEBULA/panier.php?add=2&amp;type=produit&amp;nom=Hoodie+Nebula&amp;prix=49.99" class="btn btn-primary btn-sm">Ajouter</a>
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
          <a href="/NEBULA/panier.php?add=3&amp;type=produit&amp;nom=Mug+Gaming&amp;prix=14.99" class="btn btn-outline btn-sm">Ajouter</a>
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
          <a href="/NEBULA/panier.php?add=4&amp;type=produit&amp;nom=Casquette+Nebula&amp;prix=24.99" class="btn btn-outline btn-sm">Ajouter</a>
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
          <a href="/NEBULA/panier.php?add=5&amp;type=produit&amp;nom=Mousepad+XXL&amp;prix=19.99" class="btn btn-outline btn-sm">Ajouter</a>
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
          <a href="/NEBULA/panier.php?add=6&amp;type=produit&amp;nom=Pack+Stickers&amp;prix=9.99" class="btn btn-outline btn-sm">Ajouter</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════ GARANTIES ══════════════════════
     Bandeau de 4 garanties (paiement, remboursement, etc.)
     ══════════════════════════════════════════════════════════ -->
<div class="boutique-guarantees">
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/dashboard/bouclier-securite.png" alt="icon" width="20" height="20" class="icon-img">
    </span>
    <span>Paiement sécurisé SSL</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="20" height="20" class="icon-img">
    </span>
    <span>Remboursement 7 jours</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/calendrier.png" alt="icon" width="20" height="20" class="icon-img">
    </span>
    <span>Sans engagement</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/card.png" alt="icon" width="20" height="20" class="icon-img">
    </span>
    <span>CB, PayPal, virement</span>
  </div>
</div>

<!-- ══════════════════════════ CTA CONFIGURATEUR ═══════════════
     Bandeau d'appel à l'action vers le configurateur de bouquet
     ══════════════════════════════════════════════════════════ -->
<div class="boutique-sub-cta">
  <h2 class="boutique-sub-cta-title">Composez votre bouquet sur mesure</h2>
  <p class="boutique-sub-cta-sub">Choisissez uniquement les genres et options qui vous intéressent. Payez exactement pour ce que vous utilisez.</p>
  <a href="/NEBULA/configurateur.php" class="btn btn-primary btn-lg">Configurer mon bouquet</a>
</div>

</div><!-- /.boutique-page -->

<?php require 'includes/footer.php'; ?>
