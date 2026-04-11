<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <a class="navbar-brand" href="/NEBULA/index.php">
        <img class="logo-icon" src="/NEBULA/public/assets/img/favicon.png" alt="Nebula" style="width:30px;height:30px;object-fit:contain">
        <span class="brand-name">Nebula</span>
      </a>
      <p>La plateforme de cloud gaming nouvelle génération. Jouez instantanément sur tous vos appareils.</p>
    </div>

    <div class="footer-col">
      <h4>Produit</h4>
      <ul>
        <li><a href="/NEBULA/jeux.php">Bibliothèque</a></li>
        <li><a href="/NEBULA/offres.php">Tarifs</a></li>
        <li><a href="/NEBULA/configurateur.php">Configurateur</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Support</h4>
      <ul>
        <li><a href="/NEBULA/faq.php">FAQ</a></li>
        <li><a href="/NEBULA/contact.php">Contact</a></li>
        <li><a href="/NEBULA/mentions.php">Mentions légales</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Compte</h4>
      <ul>
        <li><a href="/NEBULA/auth.php">Connexion</a></li>
        <li><a href="/NEBULA/auth.php?tab=register">Inscription</a></li>
        <li><a href="/NEBULA/dashboard.php">Mon espace</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <span>&copy; <?= date('Y') ?> Nebula. Tous droits réservés.</span>
    <div class="social-links">
      <!-- X / Twitter -->
      <a href="#" class="social-link" aria-label="Twitter / X">
        <img src="/NEBULA/public/assets/img/icons/contact/twitter.png" alt="icon" width="15" height="15" class="icon-img">
      </a>
      <!-- Discord -->
      <a href="#" class="social-link" aria-label="Discord">
        <img src="/NEBULA/public/assets/img/icons/contact/discord.png" alt="icon" width="15" height="15" class="icon-img">
      </a>
      <!-- YouTube -->
      <a href="#" class="social-link" aria-label="YouTube">
        <img src="/NEBULA/public/assets/img/icons/contact/youtube.png" alt="icon" width="15" height="15" class="icon-img">
      </a>
    </div>
  </div>
</footer>

<?php foreach ($pageJS ?? [] as $script): ?>
<script src="/NEBULA/js/<?= htmlspecialchars($script) ?>.js"></script>
<?php endforeach; ?>

</body>
</html>
