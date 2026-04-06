<?php
require 'includes/auth_check.php';
require 'includes/db.php';

$pageTitle = 'Mon espace';
$pageCSS   = ['dashboard'];
$pageJS    = [];

$pdo    = getPDO();
$userId = (int)$_SESSION['user_id'];

$stmtUser = $pdo->prepare('SELECT * FROM utilisateur WHERE id_user = :id');
$stmtUser->execute([':id' => $userId]);
$user = $stmtUser->fetch();

if (!$user) {
    session_destroy();
    header('Location: /NEBULA/auth.php');
    exit;
}

try {
    $stmtCmds = $pdo->prepare(
        'SELECT c.id_commande, c.date_commande, c.total_ttc, c.statut, o.nom_offre
         FROM commande c
         LEFT JOIN offre o ON c.id_offre = o.id_offre
         WHERE c.id_user = :id
         ORDER BY c.date_commande DESC
         LIMIT 10'
    );
    $stmtCmds->execute([':id' => $userId]);
    $commandes = $stmtCmds->fetchAll();
} catch (Exception $e) {
    $commandes = [];
}

try {
    $jeux = $pdo->query('SELECT * FROM jeu LIMIT 6')->fetchAll();
} catch (Exception $e) {
    $jeux = [];
}

$abonnement = null;
foreach ($commandes as $cmd) {
    if (!empty($cmd['nom_offre']) && $cmd['statut'] === 'payee') {
        $abonnement = $cmd;
        break;
    }
}

$nbCommandes  = count($commandes);
$planName     = $abonnement ? $abonnement['nom_offre'] : 'Starter';
$planGradient = [
    'Starter' => 'linear-gradient(135deg,#374151,#1f2937)',
    'Gamer'   => 'linear-gradient(135deg,#7c3aed,#4c1d95)',
    'Ultra'   => 'linear-gradient(135deg,#9f1239,#7c3aed)',
][$planName] ?? 'linear-gradient(135deg,#374151,#1f2937)';

require 'includes/header.php';
?>

<div class="dashboard-page">

  <!-- ── Hero banner ── -->
  <div class="db-hero">
    <div class="db-hero-orb db-hero-orb-a"></div>
    <div class="db-hero-orb db-hero-orb-b"></div>

    <div class="db-hero-inner">
      <!-- Avatar -->
      <div class="db-hero-avatar">
        <?= mb_strtoupper(mb_substr($user['nom'], 0, 1)) ?>
      </div>

      <!-- Info -->
      <div class="db-hero-info">
        <div class="db-hero-welcome">Bienvenue sur Nebula</div>
        <div class="db-hero-name"><?= htmlspecialchars($user['nom']) ?></div>
        <div class="db-hero-email"><?= htmlspecialchars($user['email']) ?></div>
      </div>

      <!-- Plan badge -->
      <div class="db-hero-plan" style="background:<?= $planGradient ?>">
        <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
        Plan <?= htmlspecialchars($planName) ?>
      </div>
    </div>
  </div>

  <!-- ── Main layout ── -->
  <div class="db-layout">

    <!-- Stats row -->
    <div class="db-stats-row">
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background:linear-gradient(135deg,rgba(124,58,237,.5),rgba(124,58,237,.2))">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">—</div>
          <div class="db-stat-label">Jeux joués</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background:linear-gradient(135deg,rgba(159,18,57,.5),rgba(159,18,57,.2))">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="18" height="18" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num"><?= $nbCommandes ?></div>
          <div class="db-stat-label">Commandes</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background:linear-gradient(135deg,rgba(244,114,182,.4),rgba(244,114,182,.15))">
          <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="14" height="14" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">—</div>
          <div class="db-stat-label">Heures jouées</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background:<?= $planGradient ?>">
          <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num"><?= htmlspecialchars($planName) ?></div>
          <div class="db-stat-label">Abonnement</div>
        </div>
      </div>
    </div>

    <!-- Left column -->
    <div class="db-left">

        <!-- Jeux récents -->
        <?php if (!empty($jeux)): ?>
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Récemment joués</div>
          </div>
          <div class="db-recent-list">
            <?php foreach(array_slice($jeux, 0, 2) as $index => $j): ?>
            <div class="db-recent-card">
              <a href="/NEBULA/produit.php?id=<?= $j['id_jeu'] ?>" class="db-recent-img">
                <?php if (!empty($j['image_url'])): ?>
                  <img src="<?= htmlspecialchars((str_starts_with($j['image_url'], 'http') ? '' : '/NEBULA/') . $j['image_url']) ?>" alt="<?= htmlspecialchars($j['titre']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="db-recent-placeholder"></div>
                <?php endif; ?>
              </a>
              <div class="db-recent-info">
                <div class="db-recent-title"><?= htmlspecialchars($j['titre']) ?></div>
                <div class="db-recent-genre"><?= $index === 0 ? 'Action / Aventure' : 'RPG' ?></div>
                <div class="db-recent-progress">
                  <div class="db-recent-progress-bar"><div class="db-recent-progress-fill" style="width: <?= 45 + $index * 25 ?>%"></div></div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center">
                  <span class="db-recent-hours"><?= 14 + $index * 12 ?>h jouées</span>
                  <a href="/NEBULA/produit.php?id=<?= $j['id_jeu'] ?>" class="db-recent-play">Reprendre <img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="play" width="10" height="10" style="filter: brightness(0) saturate(100%) invert(60%) sepia(85%) saturate(3065%) hue-rotate(224deg) brightness(101%) contrast(101%);"></a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Chat Panel -->
        <div class="db-card db-chat-container">
          
          <!-- Left Side: Friend List -->
          <div class="db-chat-sidebar">
            <div class="db-chat-sidebar-header">Messages directs</div>
            
            <div class="db-chat-friends">
              <!-- Active friend -->
              <div class="db-chat-friend-item active">
                <div class="db-act-dot db-chat-friend-dot" style="background:#9f1239; color:#fff;">
                  M
                  <div class="db-friend-online" style="width:10px; height:10px; right:-2px; bottom:-2px; border-width: 2px;"></div>
                </div>
                <div class="db-chat-friend-name">MaxGamer</div>
              </div>
              
              <!-- Other friend -->
              <div class="db-chat-friend-item">
                <div class="db-act-dot db-chat-friend-dot" style="background:#0e7490; color:#fff;">
                  S
                  <div class="db-friend-online" style="width:10px; height:10px; right:-2px; bottom:-2px; border-width: 2px;"></div>
                </div>
                <div class="db-chat-friend-name">SarahLvl99</div>
              </div>

              <!-- Other friend -->
              <div class="db-chat-friend-item">
                <div class="db-act-dot db-chat-friend-dot" style="background:#475569; color:#fff;">
                  T
                </div>
                <div class="db-chat-friend-name" style="color: var(--text-faint);">ThomasR</div>
              </div>
            </div>
          </div>

          <!-- Right Side: Chat Area -->
          <div class="db-chat-main">
            <div class="db-chat-header">
              <div style="display:flex; align-items:center; gap: 12px;">
                <div class="db-act-dot db-chat-header-dot" style="background:#9f1239; color:#fff;">
                  M
                  <div class="db-friend-online" style="width:10px; height:10px; right:-2px; bottom:-2px;"></div>
                </div>
                <div>
                  <div class="db-chat-header-name">MaxGamer</div>
                  <div class="db-chat-header-status">En ligne sur Nebula</div>
                </div>
              </div>
            </div>
            
            <div class="db-chat-messages">
              <div class="db-chat-bubble-wrap left">
                <div class="db-chat-bubble left">
                  Salut ! Hâte d'être à ce soir pour notre partie coop, j'ai débloqué une nouvelle zone !
                </div>
                <div class="db-chat-time">14:32</div>
              </div>
              
              <div class="db-chat-bubble-wrap right">
                <div class="db-chat-bubble right">
                  Génial ! Je finis The Witcher et je me connecte dans 15 minutes à peu près. Invite-moi dès que tu me vois co ! 😎
                </div>
                <div class="db-chat-time">14:34</div>
              </div>
              
              <div class="db-chat-bubble-wrap left">
                <div class="db-chat-bubble left">
                  Parfait, je lance le vocal Discord en t'attendant 🚀
                </div>
                <div class="db-chat-time">14:35</div>
              </div>
            </div>
            
            <div class="db-chat-input-area">
              <input type="text" class="db-chat-input" placeholder="Envoyer un message à MaxGamer...">
              <button class="db-chat-send" title="Envoyer">
                <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="send">
              </button>
            </div>
          </div>
        </div>

      </div><!-- /.db-left -->

      <!-- Right column -->
      <div class="db-sidebar">

        <!-- Abonnement -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Mon abonnement</div>
          </div>

          <?php if ($abonnement): ?>
            <div class="db-sub-band" style="background:<?= $planGradient ?>">
              <div class="db-sub-band-icon">
                <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
              </div>
              <div class="db-sub-name"><?= htmlspecialchars($abonnement['nom_offre']) ?></div>
              <div class="db-sub-price"><?= number_format($abonnement['total_ttc'], 2, ',', ' ') ?> €/mois</div>
            </div>
            <div class="db-sub-meta" style="margin-bottom:16px">
              <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="14" height="14" class="icon-img">
              Actif depuis le <?= date('d/m/Y', strtotime($abonnement['date_commande'])) ?>
            </div>
            <div class="db-sub-badge" style="margin-bottom:16px">
              <img src="/NEBULA/public/assets/img/icons/ecommerce/coche-incluse.png" alt="icon" width="14" height="14" class="icon-img">
              Actif
            </div>
            <a href="/NEBULA/offres.php" class="btn btn-outline btn-full btn-sm">Changer d'offre</a>
          <?php else: ?>
            <div class="sub-empty-card">
              <div class="sub-empty-title">Aucun abonnement actif</div>
              <div class="sub-empty-sub">Choisissez une offre pour accéder à votre bibliothèque complète.</div>
              <a href="/NEBULA/offres.php" class="btn btn-primary btn-full btn-sm">Voir les offres</a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Accès rapides -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Accès rapides</div>
          </div>
          <div class="db-quick-links">
            <a href="/NEBULA/jeux.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img">
              </div>
              <span class="db-quick-label">Bibliothèque de jeux</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/offres.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/platforms/etoile-pleine.png" alt="icon" width="14" height="14" class="icon-img">
              </div>
              <span class="db-quick-label">Changer d'offre</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/configurateur.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label">Configurateur bouquet</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/contact.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/contact/email.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label">Support & Contact</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="12" height="12" class="icon-img">
            </a>
            <a href="?logout=1" class="db-quick-link">
              <div class="db-quick-icon" style="background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2)">
                <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label" style="color:var(--danger)">Déconnexion</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="12" height="12" class="icon-img">
            </a>
          </div>
        </div>

        <!-- Réseau social / Amis -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Réseau social</div>
          </div>
          <div class="db-activity-feed">
            <div class="db-act-row">
              <div class="db-friend-avatar" style="position:relative;">
                <div class="db-act-dot" style="background:#0e7490; color:#fff">S</div>
                <div class="db-friend-online"></div>
              </div>
              <div class="db-act-info">
                <div class="db-act-label">SarahLvl99</div>
                <div class="db-friend-status db-friend-status--online">Joue à The Witcher 3</div>
              </div>
            </div>
            <div class="db-act-row">
               <div class="db-friend-avatar" style="position:relative;">
                <div class="db-act-dot">T</div>
              </div>
              <div class="db-act-info">
                <div class="db-act-label">ThomasR</div>
                <div class="db-friend-status">Hors ligne</div>
              </div>
            </div>
            <div class="db-friend-more">
               <a href="#" style="color:var(--accent); text-decoration:none; font-weight: 600;">Voir tous les amis</a>
            </div>
          </div>
        </div>

  </div><!-- /.db-layout -->

</div><!-- /.dashboard-page -->

<?php require 'includes/footer.php'; ?>
