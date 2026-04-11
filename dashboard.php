<?php
/* ============================================================
   DASHBOARD.PHP — Espace personnel de l'utilisateur
   Accessible uniquement aux utilisateurs connectés (auth_check).
   Affiche : hero avec avatar, statistiques, jeux récents,
   chat amis, abonnement, accès rapides et notifications.
   ============================================================ */

// -- Vérifier que l'utilisateur est connecté (sinon redirige vers auth.php) --
require 'includes/auth_check.php';
require 'includes/db.php';

// -- Configuration de la page --
$pageTitle = 'Mon espace';
$pageCSS   = ['dashboard'];

// -- Récupérer les infos de l'utilisateur depuis la BDD --
$pdo    = getPDO();
$userId = (int)$_SESSION['user_id'];

$stmtUser = $pdo->prepare('SELECT nom FROM utilisateur WHERE id_user = :id');
$stmtUser->execute([':id' => $userId]);
$user = $stmtUser->fetch();

// -- Si l'utilisateur n'existe plus en BDD, détruire la session --
if (!$user) {
    session_destroy();
    header('Location: /NEBULA/auth.php');
    exit;
}

// -- Inclure le header commun --
require 'includes/header.php';
?>

<div class="dashboard-page">

  <!-- ── Hero : avatar, nom, email et badge du plan ── -->
  <div class="db-hero">
    <div class="db-hero-inner">
      <!-- Avatar avec la première lettre du nom -->
      <div class="db-hero-avatar">
        <?= mb_strtoupper(mb_substr($user['nom'], 0, 1)) ?>
      </div>

      <!-- Infos utilisateur -->
      <div class="db-hero-info">
        <div class="db-hero-welcome">Bienvenue sur Nebula</div>
        <div class="db-hero-name"><?= htmlspecialchars($user['nom']) ?></div>
        <div class="db-hero-pseudo">@67TungtungSahur</div>
      </div>

      <!-- Badge de l'abonnement actuel -->
      <div class="db-hero-plan" style="background: #020024;background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);">
        <img src="/NEBULA/public/assets/img/icons/dashboard/star.png" alt="icon" width="14" height="14" class="icon-img">
        Plan ULTRA
      </div>
    </div>
  </div>

  <!-- ── Layout principal (stats + 2 colonnes) ── -->
  <div class="db-layout">

    <!-- Ligne de statistiques (4 cartes) : jeux joués, commandes, heures, abonnement -->
    <div class="db-stats-row">
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background: RGBA(124, 58, 237, .5);background: linear-gradient(150deg, rgba(124, 58, 237, 1) 0%, rgba(30, 11, 54, 1)  100%);">
          <img src="/NEBULA/public/assets/img/icons/dashboard/game.png" alt="icon" width="24" height="24" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">24</div>
          <div class="db-stat-label">Jeux joués</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background: #a61b42; background: linear-gradient(149deg, rgba(166, 27, 66, 1) 0%, rgba(38, 5, 13, 1) 100%);">
          <img src="/NEBULA/public/assets/img/icons/ecommerce/panier.png" alt="icon" width="24" height="24" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">12</div>
          <div class="db-stat-label">Commandes</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background: #ad1da1; background: linear-gradient(149deg, rgba(173, 29, 161, 1) 0%, rgba(71, 4, 65, 1) 100%);">
          <img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="24" height="24" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">85</div>
          <div class="db-stat-label">Heures jouées</div>
        </div>
      </div>
      <div class="db-stat-card">
        <div class="db-stat-icon" style="background: #020024; background: linear-gradient(150deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);">
          <img src="/NEBULA/public/assets/img/icons/dashboard/star.png" alt="icon" width="24" height="24" class="icon-img">
        </div>
        <div>
          <div class="db-stat-num">ULTRA</div>
          <div class="db-stat-label">Abonnement</div>
        </div>
      </div>
    </div>

    <!-- ── Colonne gauche : jeux récents + chat amis ── -->
    <div class="db-left">

        <!-- Carte : Jeux récemment joués (données statiques de démo) -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Récemment joué</div>
          </div>
          <div class="db-recent-grid">

            <!-- Jeu 1: Animal Crossing -->
            <div class="db-recent-game">
              <div class="db-recent-game-img">
                <img src="/NEBULA/public/assets/img/animal-crossing.png" alt="Animal Crossing" loading="lazy">
              </div>
              <div class="db-recent-game-info">
                <div class="db-recent-game-title">Animal Crossing: New Horizons</div>
                <div class="db-recent-game-genre">Simulation / Aventure</div>
                <div class="db-recent-game-time">42h jouées</div>
              </div>
              <a href="/NEBULA/produit.php?id=109462" class="btn btn-outline btn-full btn-sm">Reprendre</a>
            </div>

            <!-- Jeu 2: The Last of Us 2 -->
            <div class="db-recent-game">
              <div class="db-recent-game-img">
                <img src="/NEBULA/public/assets/img/the-last-of-us2.png" alt="The Last of Us Part II" loading="lazy">
              </div>
              <div class="db-recent-game-info">
                <div class="db-recent-game-title">The Last of Us Part II</div>
                <div class="db-recent-game-genre">Action / Aventure</div>
                <div class="db-recent-game-time">28h jouées</div>
              </div>
              <a href="/NEBULA/produit.php?id=26192" class="btn btn-outline btn-full btn-sm">Reprendre</a>
            </div>

            <!-- Jeu 3: Wolverine -->
            <div class="db-recent-game">
              <div class="db-recent-game-img">
                <img src="/NEBULA/public/assets/img/wolverine.png" alt="Wolverine" loading="lazy">
              </div>
              <div class="db-recent-game-info">
                <div class="db-recent-game-title">Marvel's Wolverine</div>
                <div class="db-recent-game-genre">Action / Combat</div>
                <div class="db-recent-game-time">15h jouées</div>
              </div>
              <a href="/NEBULA/produit.php?id=168667" class="btn btn-outline btn-full btn-sm">Reprendre</a>
            </div>

          </div>
        </div>

        <!-- Carte : Chat amis (interface de démonstration) -->
        <div class="db-card db-chat-container">
          <div class="db-chat-sidebar">
            <div class="db-chat-sidebar-header">Amis</div>
            <div class="db-chat-friends">
              <div class="db-chat-friend-item active">
                <div class="db-chat-friend-dot" style="background:#9f1239; color:#fff;">
                  M
                  <div class="db-friend-online"></div>
                </div>
                <div class="db-chat-friend-name">MaxGamer</div>
              </div>
              <div class="db-chat-friend-item">
                <div class="db-chat-friend-dot" style="background:#0e7490; color:#fff;">
                  S
                  <div class="db-friend-online"></div>
                </div>
                <div class="db-chat-friend-name">SarahLvl99</div>
              </div>
              <div class="db-chat-friend-item">
                <div class="db-chat-friend-dot" style="background:#475569; color:#fff;">T</div>
                <div class="db-chat-friend-name offline">ThomasR</div>
              </div>
            </div>
          </div>
          <div class="db-chat-main">
            <div class="db-chat-empty">
              <div class="db-chat-empty-icon">
                <img src="/NEBULA/public/assets/img/icons/dashboard/chat.png" alt="chat" width="28" height="28" class="icon-img">
              </div>
              <div class="db-chat-empty-title">Commencer une discussion</div>
              <div class="db-chat-empty-sub">Sélectionnez un ami pour discuter</div>
            </div>
          </div>
        </div>

      </div><!-- /.db-left -->

      <!-- ── Colonne droite (sidebar) : abonnement, accès rapides, notifications ── -->
      <div class="db-sidebar">

        <!-- Carte : Détails de l'abonnement actuel -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Mon abonnement</div>
          </div>
          <div class="db-plan-box">
            <div class="db-plan-name">ULTRA</div>
            <div class="db-plan-price">44,99<span> €/mois</span></div>
            <div class="db-plan-date">Actif depuis le 15/03/2024</div>
          </div>
          <a href="/NEBULA/offres.php" class="btn btn-outline btn-full btn-sm">Changer d'offre</a>
        </div>

        <!-- Carte : Liens d'accès rapide vers les pages principales -->
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">Accès rapides</div>
          </div>
          <div class="db-quick-links">
            <a href="/NEBULA/jeux.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/dashboard/bibliotheque.png" alt="icon" width="18" height="18" style="opacity: 0.8;" class="icon-img">
              </div>
              <span class="db-quick-label">Bibliothèque de jeux</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="arrow" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/offres.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/dashboard/offre.png" alt="icon" width="18" height="18" class="icon-img">
              </div>
              <span class="db-quick-label">Changer d'offre</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="arrow" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/configurateur.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/dashboard/colis.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label">Configurateur bouquet</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="arrow" width="12" height="12" class="icon-img">
            </a>
            <a href="/NEBULA/contact.php" class="db-quick-link">
              <div class="db-quick-icon">
                <img src="/NEBULA/public/assets/img/icons/dashboard/support.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label">Support & Contact</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="arrow" width="12" height="12" class="icon-img">
            </a>
            <a href="?logout=1" class="db-quick-link">
              <div class="db-quick-icon" style="background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2)">
                <img src="/NEBULA/public/assets/img/icons/dashboard/deconnexion.png" alt="icon" width="20" height="20" class="icon-img">
              </div>
              <span class="db-quick-label" style="color:var(--danger)">Déconnexion</span>
              <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="arrow" width="12" height="12" class="icon-img">
            </a>
          </div>
        </div>

        <!-- Carte : Notifications / jeux à ne pas manquer -->
                          <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">À ne pas manquer</div>
          </div>
          <div class="db-notif-list">
            <div class="db-notif-item">
              <img src="/NEBULA/public/assets/img/animal-crossing.png" alt="game" class="db-notif-img">
              <div>
                <div class="db-notif-title">Animal Crossing</div>
                <div class="db-notif-desc">Nouvelle mise à jour disponible</div>
              </div>
            </div>
            <div class="db-notif-item">
              <img src="/NEBULA/public/assets/img/minecraft-dungeons.png" alt="game" class="db-notif-img">
              <div>
                <div class="db-notif-title">Minecraft Dungeons</div>
                <div class="db-notif-desc">Nouveau DLC dispo</div>
              </div>
            </div>
            <div class="db-notif-item">
              <img src="/NEBULA/public/assets/img/escape-from-duckov.png" alt="game" class="db-notif-img">
              <div>
                <div class="db-notif-title">Escape from Duckov</div>
                <div class="db-notif-desc">Jeu à ne pas manquer</div>
              </div>
            </div>
          </div>
        </div>

    </div><!-- /.db-layout -->

</div><!-- /.dashboard-page -->

<?php require 'includes/footer.php'; ?>
