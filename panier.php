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
      <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
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
              'Starter' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
              'Gamer'   => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M9 8h6M12 6v4"/></svg>',
              'Ultra'   => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
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
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            Supprimer
          </a>
          <a href="/NEBULA/boutique.php" class="cart-continue-btn">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Continuer mes achats
          </a>
        </div>
      </div>

      <!-- Security badges -->
      <div class="cart-security">
        <div class="cart-security-item">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <span>Paiement 100% sécurisé</span>
        </div>
        <div class="cart-security-item">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 100-6.5L1 10"/></svg>
          <span>Remboursement 7 jours</span>
        </div>
        <div class="cart-security-item">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
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
