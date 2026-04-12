<?php
/* ============================================================
   MENTIONS.PHP — Page des mentions légales
   Contenu statique : éditeur, hébergement, propriété
   intellectuelle, RGPD/cookies et coordonnées de contact.
   ============================================================ */

// -- Configuration de la page et inclusion du header --
$pageTitle = 'Mentions légales';
$pageCSS   = ['mentions'];
$pageJS    = [];
require 'includes/header.php';
?>

<section class="section">
  <div class="section-header">
    <h1 class="ml-title">Mentions <span class="gradient-text">légales</span></h1>
    <p class="text-muted">Mise à jour : 1er janvier 2026</p>
  </div>

  <!-- Carte unique regroupant toutes les sections légales -->
  <div class="ml-wrap">
    <div class="ml-card">
      <div class="ml-card-head">Mentions légales</div>

      <!-- Section : Éditeur du site -->
      <h2 class="ml-heading">Éditeur</h2>
      <p class="ml-text">Nebula SAS — 1 rue des Étoiles, 75001 Paris<br>SIRET : 000 000 000 00000 · Directeur de publication : Alexandre Martin</p>
      <hr class="ml-sep">

      <!-- Section : Hébergeur -->
      <h2 class="ml-heading">Hébergement</h2>
      <p class="ml-text">Vercel Inc. — 340 S Lemon Ave #4133, Walnut, CA 91789, USA · <a href="https://vercel.com" target="_blank" rel="noopener">vercel.com</a></p>
      <hr class="ml-sep">

      <!-- Section : Propriété intellectuelle -->
      <h2 class="ml-heading">Propriété intellectuelle</h2>
      <p class="ml-text">Tout le contenu est la propriété de Nebula SAS. Toute reproduction est interdite sans accord écrit.</p>
      <hr class="ml-sep">

      <!-- Section : RGPD et cookies -->
      <h2 class="ml-heading">Données personnelles & cookies</h2>
      <p class="ml-text">Conformément au RGPD, vous disposez de droits d'accès, rectification, effacement et portabilité. Ce site utilise des cookies essentiels et fonctionnels. Aucun cookie publicitaire sans consentement. DPO : <a href="mailto:dpo@nebula.gg">dpo@nebula.gg</a></p>
      <hr class="ml-sep">

      <!-- Section : Contact -->
      <h2 class="ml-heading">Contact</h2>
      <p class="ml-text"><a href="mailto:contact@nebula.gg">contact@nebula.gg</a> · 1 rue des Étoiles, 75001 Paris</p>
      <div class="ml-actions">
        <a href="/NEBULA/contact.php" class="btn btn-primary btn-sm">Nous contacter</a>
        <a href="/NEBULA/faq.php" class="btn btn-outline btn-sm">FAQ</a>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
