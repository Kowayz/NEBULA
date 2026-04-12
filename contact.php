<?php
/* ============================================================
   CONTACT.PHP — Page de contact
   Affiche un formulaire pour envoyer un message à l'équipe.
   En POST, le message est enregistré dans la table "message"
   puis la page est redirigée (pattern POST-Redirect-GET).
   ============================================================ */

// -- Démarrer la session si elle n'est pas déjà active --
if (session_status() === PHP_SESSION_NONE) session_start();

// -- Traitement du formulaire de contact (soumission POST) --
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sujet   = trim($_POST['sujet'] ?? '');
    $contenu = trim($_POST['message'] ?? '');

    // Insérer en BDD seulement si les champs sont remplis
    if ($sujet !== '' && $contenu !== '') {
        try {
            require_once 'includes/db.php';
            // Enregistrer le message avec l'id utilisateur (null si non connecté)
            getPDO()->prepare('INSERT INTO message (sujet, contenu, id_user) VALUES (?,?,?)')
                    ->execute([$sujet, $contenu, $_SESSION['user_id'] ?? null]);
        } catch (Exception $e) { error_log('Contact: '.$e->getMessage()); }
    }

    // Redirection POST-Redirect-GET pour éviter la re-soumission du formulaire
    header('Location: /NEBULA/contact.php');
    exit;
}

// -- Configuration de la page --
$pageTitle = 'Contact';
$pageCSS   = ['contact'];
$pageJS    = [];

// -- Inclure le header commun --
require 'includes/header.php';
?>

<section class="section">
  <div class="section-header">
    <h1 class="contact-title">Nous contacter</h1>
    <p class="text-muted">Notre équipe vous répond généralement sous 24h.</p>
  </div>

  <!-- Formulaire de contact -->
  <div class="contact-form-wrapper">
      <h3 class="contact-card-title">Envoyer un message</h3>
      <form method="POST" novalidate>
        <!-- Champ email visible uniquement si l'utilisateur n'est pas connecté -->
        <?php if (empty($_SESSION['user_id'])): ?>
        <div class="form-group">
          <label for="email_contact">Votre e-mail</label>
          <input type="email" id="email_contact" name="email_contact" class="form-control" placeholder="vous@exemple.com">
        </div>
        <?php endif; ?>
        <!-- Sélection du sujet -->
        <div class="form-group">
          <label for="sujet">Sujet</label>
          <select id="sujet" name="sujet" class="form-control" required>
            <option value="">Choisir un sujet…</option>
            <option>Problème technique</option>
            <option>Question sur mon abonnement</option>
            <option>Remboursement</option>
            <option>Suggestion</option>
            <option>Autre</option>
          </select>
        </div>
        <!-- Zone de texte du message -->
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" class="form-control" rows="5" placeholder="Décrivez votre demande…" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-full">
          <img src="/NEBULA/public/assets/img/icons/nav/fleche-droite.png" alt="icon" width="20" height="20" class="icon-img">
          Envoyer le message
        </button>
      </form>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
