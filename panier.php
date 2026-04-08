<?php
require 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Panier';
$pageCSS   = ['panier'];
$pageJS    = [];

// Handle add to cart via GET
if (isset($_GET['offre'])) {
    $offreId = (int)$_GET['offre'];
    try {
        $pdo  = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM offre WHERE id_offre = :id');
        $stmt->execute([':id' => $offreId]);
        $offre = $stmt->fetch();
        if ($offre) {
            $_SESSION['panier'] = [
                'id'  => $offre['id_offre'],
                'nom' => $offre['nom_offre'],
                'prix'=> $offre['prix_mensuel'],
            ];
        }
    } catch (Exception $e) {
        // ignore
    }
}

// Handle remove
if (isset($_GET['vider'])) {
    unset($_SESSION['panier']);
}

$panier   = $_SESSION['panier'] ?? null;
$tvaRate  = 0.20;
$sousTotal = $panier ? (float)$panier['prix'] : 0;
$tva      = round($sousTotal * $tvaRate, 2);
$total    = round($sousTotal + $tva, 2);

require 'includes/header.php';
?>

<!-- ── Hero header ───────────────────────────────────────────── -->
<section class="section text-center cart-hero" style="padding-bottom:36px">
  <div class="section-tag">Panier</div>
  <h1 class="cart-hero-title">Votre <span class="gradient-text">panier</span></h1>
</section>

<!-- ── Cart content ─────────────────────────────────────────── -->
<section class="section" style="padding-top:0">

  <?php if (!$panier): ?>
  <!-- Empty state -->
  <div class="cart-empty">
    <div class="cart-empty-icon">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="18" height="18" class="icon-img">
    </div>
    <h2 class="cart-empty-title">Votre panier est vide</h2>
    <p class="cart-empty-sub">Vous n'avez ajouté aucun abonnement à votre panier pour le moment.</p>
    <div class="cart-empty-actions">
      <a href="/NEBULA/boutique.php" class="btn btn-primary btn-lg">Voir la boutique</a>
      <a href="/NEBULA/offres.php"   class="btn btn-outline btn-lg">Comparer les offres</a>
    </div>
  </div>

  <?php else: ?>
  <!-- Cart with item -->
  <div class="cart-layout">

    <!-- Left: items -->
    <div class="cart-items-col">
      <div class="cart-items-card">
        <div class="cart-items-header">
          <span>Abonnement sélectionné</span>
          <span class="cart-item-count">1 article</span>
        </div>

        <div class="cart-item">
          <div class="cart-item-icon">
            <?php
            $planIcons = [
              'Starter' => '<img src="/NEBULA/public/assets/img/icons/ecommerce/composant-cpu.png" alt="icon" width="26" height="26" class="icon-img">',
              'Gamer'   => '<img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">',
              'Ultra'   => '<img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">',
            ];
            echo $planIcons[$panier['nom']] ?? $planIcons['Gamer'];
            ?>
          </div>
          <div class="cart-item-info">
            <div class="cart-item-name">Abonnement <?= htmlspecialchars($panier['nom']) ?></div>
            <div class="cart-item-desc">
              <?php
              $descs = [
                'Starter' => 'Accès Starter — 10h/mois, HD 720p, +25 jeux',
                'Gamer'   => 'Accès Gamer — Illimité, 4K 144FPS, +200 jeux, ray tracing',
                'Ultra'   => 'Accès Ultra — Tout Gamer + support 24/7, multi-appareils',
              ];
              echo htmlspecialchars($descs[$panier['nom']] ?? 'Abonnement mensuel');
              ?>
            </div>
            <div class="cart-item-pills">
              <span class="cart-pill">Sans engagement</span>
              <span class="cart-pill">Résiliation en 1 clic</span>
              <?php if ($panier['prix'] == 0): ?>
              <span class="cart-pill cart-pill-free">Gratuit</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="cart-item-price-col">
            <?php if ($panier['prix'] == 0): ?>
              <div class="cart-item-price">Gratuit</div>
            <?php else: ?>
              <div class="cart-item-price"><?= number_format($panier['prix'], 2, ',', '') ?> €</div>
              <div class="cart-item-period">/mois</div>
            <?php endif; ?>
          </div>
        </div>

        <div class="cart-item-footer">
          <a href="/NEBULA/panier.php?vider=1" class="cart-remove-btn">
            <img src="/NEBULA/public/assets/img/icons/ecommerce/poubelle.png" alt="icon" width="14" height="14" class="icon-img">
            Supprimer
          </a>
          <a href="/NEBULA/boutique.php" class="cart-continue-btn">
            <img src="/NEBULA/public/assets/img/icons/nav/fleche-bas.png" alt="icon" width="14" height="14" class="icon-img">
            Continuer mes achats
          </a>
        </div>
      </div>

      <!-- Security badges -->
      <div class="cart-security">
        <div class="cart-security-item">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/bouclier-securite.png" alt="icon" width="20" height="20" class="icon-img">
          <span>Paiement 100% sécurisé</span>
        </div>
        <div class="cart-security-item">
          <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="14" height="14" class="icon-img">
          <span>Remboursement 7 jours</span>
        </div>
        <div class="cart-security-item">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/calendrier.png" alt="icon" width="14" height="14" class="icon-img">
          <span>Facturation mensuelle</span>
        </div>
      </div>
    </div>

    <!-- Right: order summary -->
    <aside class="cart-summary-col">
      <div class="cart-summary-card">
        <div class="cart-summary-title">Récapitulatif</div>

        <div class="cart-summary-lines">
          <div class="cart-summary-line">
            <span>Abonnement <?= htmlspecialchars($panier['nom']) ?></span>
            <?php if ($panier['prix'] == 0): ?>
              <span>Gratuit</span>
            <?php else: ?>
              <span><?= number_format($sousTotal, 2, ',', '') ?> €</span>
            <?php endif; ?>
          </div>

          <?php if ($panier['prix'] > 0): ?>
          <div class="cart-summary-line cart-summary-line-muted">
            <span>TVA (20%)</span>
            <span><?= number_format($tva, 2, ',', '') ?> €</span>
          </div>
          <?php endif; ?>
        </div>

        <div class="cart-summary-divider"></div>

        <div class="cart-summary-total">
          <span>Total TTC</span>
          <?php if ($panier['prix'] == 0): ?>
            <span class="cart-total-price">Gratuit</span>
          <?php else: ?>
            <span class="cart-total-price"><?= number_format($total, 2, ',', '') ?> €<small>/mois</small></span>
          <?php endif; ?>
        </div>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="/NEBULA/dashboard.php" class="btn btn-primary btn-full btn-lg cart-checkout-btn">
            Confirmer l'abonnement
          </a>
        <?php else: ?>
          <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-full btn-lg cart-checkout-btn">
            Créer un compte &amp; payer
          </a>
          <a href="/NEBULA/auth.php" class="btn btn-outline btn-full btn-sm" style="margin-top:10px">
            Déjà client ? Se connecter
          </a>
        <?php endif; ?>

        <div class="cart-summary-note">
          En validant, vous acceptez nos
          <a href="/NEBULA/mentions.php" style="color:var(--accent)">conditions générales</a>.
          Résiliation possible à tout moment.
        </div>
      </div>

      <!-- Promo code -->
      <div class="cart-promo-card">
        <div class="cart-promo-title">Code promo</div>
        <div class="cart-promo-row">
          <input type="text" class="form-control" placeholder="NEBULA2026" style="flex:1">
          <button class="btn btn-outline btn-sm">Appliquer</button>
        </div>
      </div>
    </aside>

  </div>
  <?php endif; ?>

</section>

<?php require 'includes/footer.php'; ?>
