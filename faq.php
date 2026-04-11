<?php
/* ============================================================
   FAQ.PHP — Page Foire Aux Questions
   Contient : hero, présentation de Nebula, étapes de fonctionnement,
   avantages, accordéon de questions/réponses et CTA final.
   Le JS (faq.js) gère l'ouverture/fermeture de l'accordéon.
   ============================================================ */

// -- Inclure le header commun --
require 'includes/header.php';
?>

<!-- CSS et JS spécifiques à la page FAQ -->
<link rel="stylesheet" href="/NEBULA/css/faq.css">
<script src="/NEBULA/js/faq.js" defer></script>

<div class="faq-page">

<!-- ══════════════════════════ HERO ══════════════════════════
     Bannière titre de la page FAQ
     ══════════════════════════════════════════════════════════ -->
<div class="faq-hero">
    <h1 class="faq-hero-title">Questions <span class="gradient-text">fréquentes</span></h1>
    <p class="faq-hero-sub">Tout ce que vous devez savoir sur Nebula, le cloud gaming et votre abonnement.</p>
</div>

<!-- ══════════════════════════ QU'EST-CE QUE NEBULA ═══════════
     3 cartes expliquant le concept de cloud gaming Nebula
     ══════════════════════════════════════════════════════════ -->
<section class="section features-section">
  <div class="faq-section-head container">
    <h2 class="faq-section-title">Qu'est-ce que Nebula ?</h2>
    <p class="faq-section-sub">Une plateforme de cloud gaming — jouer sans télécharger, depuis n'importe quel appareil.</p>
  </div>
  <div class="features-grid container">
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/4K.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Streaming haute performance</h3>
      <div class="feature-desc">Vos jeux tournent sur nos serveurs GPU et sont diffusés en direct sur votre écran, comme Netflix mais interactif. Moins de 20 ms de latence.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/platforms/multi.png" alt="icon" width="22" height="22" class="platform-icon"></div>
      <h3>Tous vos appareils</h3>
      <div class="feature-desc">PC, Mac, Smart TV, smartphone, tablette. Reprenez votre partie là où vous l'avez laissée, sur l'appareil de votre choix.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/contact/document-legal.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Sans engagement</h3>
      <div class="feature-desc">Aucun téléchargement, aucun matériel coûteux, aucun engagement. Abonnez-vous et commencez à jouer en quelques secondes.</div>
    </div>
  </div>
</section>

<!-- ══════════════════════════ COMMENT ÇA MARCHE ══════════════
     3 étapes pour commencer à utiliser Nebula
     ══════════════════════════════════════════════════════════ -->
<section class="section features-section faq-section--alt">
  <div class="faq-section-head container">
    <h2 class="faq-section-title">Comment ça marche ?</h2>
    <p class="faq-section-sub">En 4 étapes, passez de nouveau joueur à gamer cloud en moins de 5 minutes.</p>
  </div>
  <div class="features-grid container">
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/utilisateur.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>01 · Créer un compte</h3>
      <p>Inscrivez-vous en 60 secondes avec votre adresse e-mail. Aucune carte bancaire requise pour l'offre Starter.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/clic.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>02 · Choisir un jeu</h3>
      <p>Parcourez notre catalogue de +200 titres. Filtrez par genre, nouveautés ou popularité.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/platforms/bouton-play.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>03 · Appuyer sur Jouer</h3>
      <p>Le jeu démarre en quelques secondes directement dans votre navigateur. Aucun téléchargement.</p>
    </div>
  </div>
</section>

<!-- ══════════════════════════ POURQUOI NEBULA ═════════════════
     Grille de 6 avantages (même structure que index.php)
     ══════════════════════════════════════════════════════════ -->
<section class="section features-section">
  <div class="faq-section-head container">
    <h2 class="faq-section-title">Tout ce dont vous avez besoin</h2>
    <p class="faq-section-sub">Une technologie de pointe pour une expérience de jeu sans friction</p>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/horloge.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Latence ultra-faible</h3>
      <p>Moins de 20ms grâce à notre infrastructure distribuée, peu importe votre localisation.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/4K.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>4K 144 FPS</h3>
      <p>Qualité d'image exceptionnelle jusqu'en 4K Ultra HD à 144 FPS, sur n'importe quel écran.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/platforms/multi.png" alt="icon" width="22" height="22" class="platform-icon"></div>
      <h3>Multi-appareils</h3>
      <p>PC, Mac, TV, smartphone — reprenez votre partie là où vous l'avez laissée.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/ecommerce/serveur.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Sauvegardes cloud</h3>
      <p>Vos sauvegardes sont synchronisées automatiquement. Ne perdez plus jamais votre progression.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/dashboard/game.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Compatible manettes</h3>
      <p>DualSense, Xbox Series, contrôleurs Bluetooth — plug & play garanti.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><img src="/NEBULA/public/assets/img/icons/contact/document-legal.png" alt="icon" width="22" height="22" class="icon-img"></div>
      <h3>Sans engagement</h3>
      <p>Résiliez quand vous voulez. Nos abonnements sont flexibles, sans frais cachés.</p>
    </div>
  </div>
</section>

<!-- ══════════════════════════ ACCORDÉON FAQ ═══════════════════
     9 questions/réponses cliquables (gérées par faq.js).
     Chaque faq-item contient un bouton et un bloc réponse.
     ══════════════════════════════════════════════════════════ -->
<section class="faq-section faq-section--alt">
  <div class="faq-section-head container">
    <h2 class="faq-section-title">Questions fréquentes</h2>
    <p class="faq-section-sub">Les réponses aux questions les plus posées par notre communauté.</p>
  </div>

  <div class="faq-list container">
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">01</span>
        <span class="faq-q-text">Ai-je besoin d'un PC puissant ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Non. Nebula fonctionne sur n'importe quel appareil disposant d'une connexion internet : PC bas de gamme, Mac, smartphone, tablette ou Smart TV. Toute la puissance de calcul est sur nos serveurs.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">02</span>
        <span class="faq-q-text">Quelle connexion est recommandée ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Pour une expérience en HD (720p), 10 Mb/s suffisent. Pour du 4K à 144 FPS, nous recommandons 35 Mb/s minimum en fibre ou Ethernet.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">03</span>
        <span class="faq-q-text">Puis-je jouer avec ma manette ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Oui. Nebula est compatible avec toutes les manettes Bluetooth et USB : DualSense PlayStation, Xbox Series, Nintendo Switch Pro et claviers-souris.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">04</span>
        <span class="faq-q-text">Mes sauvegardes sont-elles conservées ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Vos sauvegardes cloud sont conservées 90 jours après la résiliation. En cas de réabonnement dans ce délai, vous retrouvez exactement votre progression.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">05</span>
        <span class="faq-q-text">Puis-je changer d'offre ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Oui, à tout moment depuis votre espace personnel. Les changements sont effectifs immédiatement avec ajustement au prorata.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">06</span>
        <span class="faq-q-text">Y a-t-il des frais cachés ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Aucun. Le prix affiché est tout inclus : aucun frais d'installation, de téléchargement, ou de DLC obligatoire.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">07</span>
        <span class="faq-q-text">Comment fonctionne le bouquet ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Le configurateur vous permet de sélectionner uniquement les genres et fonctionnalités qui vous intéressent. Vous payez exactement pour ce que vous utilisez.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">08</span>
        <span class="faq-q-text">Le service fonctionne-t-il en déplacement ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Oui, Nebula fonctionne depuis n'importe quel réseau — fibre, 4G/5G ou Wi-Fi public. Pour une qualité optimale nous recommandons une connexion stable et non partagée.</p></div>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button">
        <span class="faq-q-num">09</span>
        <span class="faq-q-text">Quelle est la politique de remboursement ?</span>
      </button>
      <div class="faq-answer-wrap">
        <div class="faq-answer"><p>Nous offrons un remboursement complet dans les 7 jours suivant le premier abonnement, sans condition. La demande se fait directement depuis votre espace personnel.</p></div>
      </div>
    </div>
  </div>

  <!-- Lien vers la page contact si la réponse n'est pas trouvée -->
  <div class="faq-not-found">
    Vous ne trouvez pas votre réponse ?
    <a href="/NEBULA/contact.php" style="color:var(--accent);text-decoration:underline">Contactez notre équipe</a>
  </div>
</section>

<!-- ══════════════════════════ CTA ══════════════════════════
     Bandeau final d'appel à l'action (inscription + offres)
     ══════════════════════════════════════════════════════════ -->
<section class="faq-cta"> 
  <div class="faq-cta-inner">
    <h2 class="faq-cta-title">Commencez gratuitement</h2>
    <p class="faq-cta-sub">Rejoignez des milliers de joueurs. Aucune carte bancaire pour démarrer.</p>
    <div class="faq-cta-btns">
      <a href="/NEBULA/auth.php?tab=register" class="btn btn-primary btn-lg">Créer un compte gratuit</a>
      <a href="/NEBULA/offres.php" class="btn btn-outline btn-lg">Voir les offres</a>
    </div>
  </div>
</section>

</div><!-- /.faq-page -->

<?php require 'includes/footer.php'; ?>
