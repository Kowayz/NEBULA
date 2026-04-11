<?php
/* ============================================================
   AUTH.PHP — Page de connexion / inscription
   Gère l'authentification (login + register) via POST,
   puis affiche le formulaire correspondant selon ?tab=login|register.
   ============================================================ */

// -- Connexion à la base de données --
require 'includes/db.php';
session_start();

// -- Si l'utilisateur est déjà connecté, rediriger vers le dashboard --
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// -- Initialisation --
$pdo = getPDO();
$error = '';
$tab = $_GET['tab'] ?? 'login'; // Onglet actif : "login" ou "register"

// -- Traitement du formulaire de CONNEXION --
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    // Vérifier email + mot de passe dans la table utilisateur
    $stmt = $pdo->prepare('SELECT id_user, nom FROM utilisateur WHERE email = ? AND password = ?');
    $stmt->execute([$_POST['email'], $_POST['password']]);
    $user = $stmt->fetch();
    if ($user) {
        // Stocker l'utilisateur en session et rediriger
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_nom'] = $user['nom'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}

// -- Traitement du formulaire d'INSCRIPTION --
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    try {
        $stmt = $pdo->prepare('INSERT INTO utilisateur (nom, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['password']]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_nom'] = $_POST['nom'];
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        // Code 23000 = doublon (email déjà utilisé)
        $error = str_contains($e->getCode(), '23000')
            ? 'Cet email est déjà utilisé'
            : 'Erreur lors de l\'inscription';
        $tab = 'register';
    }
}

// -- Configuration de la page et inclusion du header --
$pageTitle = 'Connexion';
$pageCSS = ['auth'];
require 'includes/header.php';
?>

<!-- Conteneur centré avec la carte du formulaire -->
<div class='auth-wrapper'>
  <div class='auth-card'>
    
<?php if ($tab === 'login'): ?>
    <!-- ── Formulaire de connexion ── -->
    <h2>Connexion</h2>
    <?php if ($error): ?><p style='color:red'><?= $error ?></p><?php endif; ?>
    <form method='POST'>
      <input type='hidden' name='action' value='login'>
      <div class='input-box'><img src='/NEBULA/public/assets/img/icons/contact/email.png' class='input-img'><input type='email' name='email' placeholder='Email' required></div>
      <div class='input-box'><img src='/NEBULA/public/assets/img/icons/dashboard/cadenas.png' class='input-img'><input type='password' name='password' placeholder='Mot de passe' required></div>
      <p><button type='submit' class='btn btn-primary'>Se connecter</button></p>
    </form>
    <p>Pas de compte ? <a href='?tab=register'>S'inscrire</a></p>
    
<?php else: ?>
    <!-- ── Formulaire d'inscription ── -->
    <h2>Inscription</h2>
    <?php if ($error): ?><p style='color:red'><?= $error ?></p><?php endif; ?>
    <form method='POST'>
      <input type='hidden' name='action' value='register'>
      <div class='input-box'><img src='/NEBULA/public/assets/img/icons/dashboard/utilisateur.png' class='input-img'><input type='text' name='nom' placeholder='Nom' required></div>
      <div class='input-box'><img src='/NEBULA/public/assets/img/icons/contact/email.png' class='input-img'><input type='email' name='email' placeholder='Email' required></div>
      <div class='input-box'><img src='/NEBULA/public/assets/img/icons/dashboard/cadenas.png' class='input-img'><input type='password' name='password' placeholder='Mot de passe' required></div>
      <p><button type='submit' class='btn btn-primary'>Créer compte</button></p>
    </form>
    <p>Déjà un compte ? <a href='?tab=login'>Se connecter</a></p>
<?php endif; ?>
    
  </div>
</div>

<?php require 'includes/footer.php'; ?>

