<?php
session_start();
require_once 'includes/db.php';

$error = '';
$success = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont requis";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion : " . $e->getMessage();
        }
    }
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = trim($_POST['reg_email']);
    $nom = trim($_POST['nom']);
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($email) || empty($nom) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id_user FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé";
            } else {
                // Créer le compte
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilisateur (email, nom, password, role) VALUES (?, ?, ?, 'client')");
                $stmt->execute([$email, $nom, $hashed_password]);
                
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription - Nebula</title>
</head>
<body>
    <h1>NEBULA</h1>
    
    <div>
        <button onclick="showForm('login')" id="btn-login">Se connecter</button>
        <button onclick="showForm('register')" id="btn-register">Créer un compte</button>
    </div>
    
    <hr>
    
    <?php if ($error): ?>
        <p style="color: red; background: #ffe0e0; padding: 10px; border: 1px solid red;">
            ❌ <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green; background: #e0ffe0; padding: 10px; border: 1px solid green;">
            ✅ <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <!-- Login Form -->
    <form id="login-form" method="POST" style="display: block;">
        <h2>Se connecter</h2>
        
        <div>
            <label>Email :</label><br>
            <input type="email" name="email" placeholder="votre@email.com" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <div>
            <label>Mot de passe :</label><br>
            <input type="password" name="password" placeholder="••••••••" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <button type="submit" name="login" style="padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer;">
            Connexion
        </button>
    </form>

    <!-- Register Form -->
    <form id="register-form" method="POST" style="display: none;">
        <h2>Créer un compte</h2>
        
        <div>
            <label>Nom complet :</label><br>
            <input type="text" name="nom" placeholder="Jean Dupont" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <div>
            <label>Email :</label><br>
            <input type="email" name="reg_email" placeholder="votre@email.com" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <div>
            <label>Mot de passe :</label><br>
            <input type="password" name="reg_password" placeholder="••••••••" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <div>
            <label>Confirmer le mot de passe :</label><br>
            <input type="password" name="confirm_password" placeholder="••••••••" required style="width: 300px; padding: 8px;">
        </div>
        <br>
        
        <button type="submit" name="register" style="padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer;">
            Créer le compte
        </button>
    </form>

    <hr>
    <p><a href="index.php">← Retour à l'accueil</a></p>

    <script>
        function showForm(formType) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const btnLogin = document.getElementById('btn-login');
            const btnRegister = document.getElementById('btn-register');

            if (formType === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                btnLogin.style.fontWeight = 'bold';
                btnRegister.style.fontWeight = 'normal';
            } else {
                registerForm.style.display = 'block';
                loginForm.style.display = 'none';
                btnRegister.style.fontWeight = 'bold';
                btnLogin.style.fontWeight = 'normal';
            }
        }
    </script>
</body>
</html>
