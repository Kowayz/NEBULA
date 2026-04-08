<?php
require 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Boutique';
$pageCSS   = ['boutique'];
$pageJS    = [];

try {
    $pdo    = getPDO();
    $offres = $pdo->query('SELECT * FROM offre ORDER BY prix_mensuel ASC')->fetchAll();
} catch (Exception $e) {
    $offres = [];
}

if (empty($offres)) {
    $offres = [
        ['id_offre'=>1,'nom_offre'=>'Starter','prix_mensuel'=>0.00, 'description'=>'Parfait pour découvrir'],
        ['id_offre'=>2,'nom_offre'=>'Gamer',  'prix_mensuel'=>24.99,'description'=>'Pour les joueurs réguliers'],
        ['id_offre'=>3,'nom_offre'=>'Ultra',  'prix_mensuel'=>44.99,'description'=>"L'expérience ultime"],
    ];
}

$planDetails = [
    'Starter' => [
        'category' => 'Abonnement',
        'gradient' => 'linear-gradient(135deg, #1e0838 0%, #0e0320 100%)',
        'icon'     => '<img src="/NEBULA/public/assets/img/icons/ecommerce/composant-cpu.png" alt="icon" width="26" height="26" class="icon-img">',
        'badge'    => null,
        'hot'      => false,
        'perks'    => ['10h de jeu par mois', 'Qualité HD 720p', '+25 jeux inclus', 'Sauvegarde de base'],
    ],
    'Gamer' => [
        'category' => 'Abonnement populaire',
        'gradient' => 'linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #1e0838 100%)',
        'icon'     => '<img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">',
        'badge'    => 'Populaire',
        'hot'      => true,
        'perks'    => ['Jeu illimité', '4K Ultra HD + 144 FPS', '+200 jeux inclus', 'Ray tracing', 'Sauvegardes illimitées'],
    ],
    'Ultra' => [
        'category' => 'Abonnement premium',
        'gradient' => 'linear-gradient(135deg, #4c0519 0%, #9f1239 50%, #1e0838 100%)',
        'icon'     => '<img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">',
        'badge'    => 'Premium',
        'hot'      => false,
        'perks'    => ['Tout Gamer +', 'Support prioritaire 24/7', 'Accès anticipé', '2 appareils simultanés', 'Cadeaux exclusifs'],
    ],
];

require 'includes/header.php';
?>

<div class="boutique-page">

<!-- ══════════════════════════ HERO ══════════════════════════ -->
<div class="boutique-hero">
  <div class="boutique-hero-orb boutique-hero-orb-a"></div>
  <div class="boutique-hero-orb boutique-hero-orb-b"></div>
  <div class="boutique-hero-inner">
    <div class="boutique-hero-tag">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="18" height="18" class="icon-img">
      Boutique
    </div>
    <h1 class="boutique-hero-title">Choisissez votre <span class="gradient-text">abonnement</span></h1>
    <p class="boutique-hero-sub">Abonnements flexibles, sans engagement. Commencez gratuitement, évoluez à votre rythme.</p>
  </div>
</div>

<!-- ══════════════════════════ PLANS GRID ══════════════════════════ -->
<div class="boutique-section">
  <div class="boutique-section-header">
    <div class="boutique-section-title">Nos abonnements</div>
    <div class="boutique-section-sub">Choisissez la formule adaptée à votre façon de jouer</div>
  </div>

  <div class="merch-grid">
    <?php foreach ($offres as $o):
      $nom  = $o['nom_offre'];
      $prix = (float)$o['prix_mensuel'];
      $det  = $planDetails[$nom] ?? $planDetails['Starter'];
    ?>
    <div class="merch-card <?= $det['hot'] ? 'merch-card-hot' : '' ?>">
      <?php if ($det['badge']): ?>
        <div class="merch-hot-badge">
          <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
          <?= htmlspecialchars($det['badge']) ?>
        </div>
      <?php endif; ?>

      <div class="merch-img" style="background:<?= $det['gradient'] ?>; display:flex; align-items:center; justify-content:center;">
        <?= $det['icon'] ?>
      </div>

      <div class="merch-body">
        <div class="merch-category"><?= htmlspecialchars($det['category']) ?></div>
        <div class="merch-name"><?= htmlspecialchars($nom) ?></div>
        <div class="merch-desc"><?= implode(' · ', array_slice($det['perks'], 0, 2)) ?></div>
        <div class="merch-footer">
          <div class="merch-price">
            <?= $prix === 0.0 ? 'Gratuit' : number_format($prix, 2, ',', '') . ' €/mois' ?>
          </div>
          <?php if ($prix === 0.0): ?>
            <a href="/NEBULA/auth.php?tab=register" class="btn btn-outline btn-sm">Commencer</a>
          <?php else: ?>
            <a href="/NEBULA/panier.php?offre=<?= (int)$o['id_offre'] ?>" class="btn <?= $det['hot'] ? 'btn-primary' : 'btn-outline' ?> btn-sm">Ajouter</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ══════════════════════════ GUARANTEES ══════════════════════════ -->
<div class="boutique-guarantees">
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/contact/document-legal.png" alt="icon" width="22" height="22" class="icon-img">
    </span>
    <span>Paiement sécurisé SSL</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="14" height="14" class="icon-img">
    </span>
    <span>Remboursement 7 jours</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/ecommerce/calendrier.png" alt="icon" width="14" height="14" class="icon-img">
    </span>
    <span>Sans engagement</span>
  </div>
  <div class="boutique-guarantee">
    <span class="boutique-guarantee-icon">
      <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
    </span>
    <span>CB, PayPal, virement</span>
  </div>
</div>

<!-- ══════════════════════════ GIFT CARDS ══════════════════════════ -->
<div class="boutique-section">
  <div class="boutique-section-header">
    <div class="boutique-section-title">Cartes cadeaux</div>
    <div class="boutique-section-sub">La carte cadeau parfaite pour les joueurs de votre entourage. Valable 12 mois.</div>
  </div>

  <div class="gift-grid">
    <!-- 10€ -->
    <div class="gift-card">
      <div class="gift-card-top">
        <div class="gift-card-icon">
          <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
        </div>
        <div class="gift-card-validity">12 mois</div>
      </div>
      <div class="gift-card-amount">10 €</div>
      <div class="gift-card-name">Carte Découverte</div>
      <div class="gift-card-desc">Idéale pour offrir un premier mois sur l'offre Starter ou compléter un abonnement existant.</div>
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-outline btn-sm">Acheter</a>
    </div>

    <!-- 25€ featured -->
    <div class="gift-card gift-card-featured">
      <div class="gift-card-glow"></div>
      <div class="gift-card-top" style="position:relative;z-index:1">
        <div class="gift-card-icon">
          <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
        </div>
        <div class="gift-card-validity">Le plus offert</div>
      </div>
      <div class="gift-card-amount gradient-text">25 €</div>
      <div class="gift-card-name">Carte Gamer</div>
      <div class="gift-card-desc">Un mois d'abonnement Gamer offert, avec accès à tous les jeux et streaming 4K 144 FPS.</div>
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-sm">Acheter</a>
    </div>

    <!-- 50€ -->
    <div class="gift-card">
      <div class="gift-card-top">
        <div class="gift-card-icon">
          <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
        </div>
        <div class="gift-card-validity">12 mois</div>
      </div>
      <div class="gift-card-amount">50 €</div>
      <div class="gift-card-name">Carte Ultra</div>
      <div class="gift-card-desc">Un mois Ultra complet ou deux mois Gamer. L'abonnement premium pour les passionnés.</div>
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-outline btn-sm">Acheter</a>
    </div>
  </div>

  <p class="text-center text-muted" style="margin-top:24px;font-size:.82rem">
    Cartes cadeaux livrées par e-mail en quelques minutes. Non remboursables mais cumulables.
  </p>
</div>

<!-- ══════════════════════════ SUB CTA ══════════════════════════ -->
<div class="boutique-sub-cta">
  <div class="boutique-sub-cta-inner">
    <div class="boutique-sub-cta-eyebrow">Configurateur</div>
    <h2 class="boutique-sub-cta-title">Composez votre bouquet sur mesure</h2>
    <p class="boutique-sub-cta-sub">Choisissez uniquement les genres et options qui vous intéressent. Payez exactement pour ce que vous utilisez.</p>
    <a href="/NEBULA/configurateur.php" class="btn btn-primary btn-lg">
      <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
      Configurer mon bouquet
    </a>
  </div>
</div>

</div><!-- /.boutique-page -->

<?php require 'includes/footer.php'; ?>
