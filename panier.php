<?php
/* ============================================================
   PANIER.PHP — Page du panier d'achats
   Gère l'ajout, la suppression et le vidage du panier via
   les paramètres GET (?add=, ?remove=, ?vider=).
   Les articles sont stockés en BDD (table "panier") et liés
   à l'utilisateur connecté via son user_id en session.
   ============================================================ */

// -- Connexion BDD et démarrage de la session --
require 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pdo = getPDO();

// -- Configuration de la page --
$pageTitle = 'Panier';
$pageCSS   = ['panier'];
$pageJS    = [];

// -- Récupérer l'ID utilisateur depuis la session --
$userId = $_SESSION['user_id'] ?? 0;

// -- ACTION : AJOUTER un article au panier (?add=ID&nom=...&prix=...) --
if (isset($_GET['add'])) {
    // Vérifier que l'utilisateur est connecté
    if (empty($_SESSION['user_id'])) {
        header('Location: /NEBULA/auth.php?tab=login&redirect=panier.php');
        exit;
    }
    
    // Récupérer les infos de l'article depuis l'URL
    $jeuId = (int)$_GET['add'];
    $nom = $_GET['nom'] ?? 'Article';
    $prix = (float)($_GET['prix'] ?? 0);
    $categorie = $_GET['cat'] ?? 'jeu';
    
    // Vérifier si l'article existe déjà dans le panier
    $exist = $pdo->query("SELECT id, quantite, categorie FROM panier WHERE jeu_id = $jeuId AND user_id = $userId")->fetch();
    
    if ($exist) {
        // Produit boutique : augmenter la quantité (+1)
        if ($exist['categorie'] === 'boutique') {
            $newQty = $exist['quantite'] + 1;
            $pdo->query("UPDATE panier SET quantite = $newQty WHERE id = {$exist['id']}");
        }
        // Jeu : ne pas ajouter de doublon (on ignore)
    } else {
        // Insérer un nouvel article dans le panier
        $qty = 1;
        $pdo->query("INSERT INTO panier (user_id, jeu_id, nom, prix, quantite, categorie) 
                     VALUES ($userId, $jeuId, '$nom', $prix, $qty, '$categorie')");
    }
    header('Location: panier.php');
    exit;
}

// -- ACTION : SUPPRIMER un article du panier (?remove=ID) --
if (isset($_GET['remove'])) {
    if (empty($_SESSION['user_id'])) {
        header('Location: /NEBULA/auth.php?tab=login');
        exit;
    }
    $jeuId = (int)$_GET['remove'];
    $pdo->query("DELETE FROM panier WHERE jeu_id = $jeuId AND user_id = $userId");
    header('Location: panier.php');
    exit;
}

// -- ACTION : VIDER tout le panier (?vider=1) --
if (isset($_GET['vider'])) {
    if (empty($_SESSION['user_id'])) {
        header('Location: /NEBULA/auth.php?tab=login');
        exit;
    }
    $pdo->query("DELETE FROM panier WHERE user_id = $userId");
    header('Location: panier.php');
    exit;
}

// -- Récupérer les articles du panier depuis la BDD (si connecté) --
if (!empty($_SESSION['user_id'])) {
    $panier = $pdo->query("SELECT * FROM panier WHERE user_id = $userId ORDER BY date_ajout DESC")->fetchAll();
} else {
    $panier = [];
}

// -- Calculs des totaux (sous-total, TVA 20%, total TTC) --
$sousTotal = 0;
foreach ($panier as $item) {
    $sousTotal += $item['prix'] * $item['quantite'];
}
$tva = $sousTotal * 0.20;
$total = $sousTotal + $tva;

// -- Inclure le header commun --
require 'includes/header.php';
?>

<!-- ── Hero du panier ── -->
<section class='section text-center cart-hero'>
  <div class='section-tag'>Panier</div>
  <h1>Votre <span class='gradient-text'>panier</span></h1>
</section>

<section class='section'>

<!-- État 1 : Utilisateur non connecté → message + boutons login/register -->
<?php if (empty($_SESSION['user_id'])): ?>
  <div class='cart-empty'>
    <div class='cart-empty-icon'><img src='/NEBULA/public/assets/img/icons/ecommerce/padlock.png' alt='Connexion' width='64' height='64'></div>
    <h2 class='cart-empty-title'>Connexion requise</h2>
    <p class='cart-empty-sub'>Connectez-vous pour accéder à votre panier et effectuer des achats.</p>
    <div class='cart-empty-actions'>
      <a href='/NEBULA/auth.php?tab=login' class='btn btn-primary'>Se connecter</a>
      <a href='/NEBULA/auth.php?tab=register' class='btn btn-outline'>Créer un compte</a>
    </div>
  </div>

<!-- État 2 : Panier vide → message + liens vers boutique/jeux -->
<?php elseif (empty($panier)): ?>
  <div class='cart-empty'>
    <div class='cart-empty-icon'><img src='/NEBULA/public/assets/img/icons/ecommerce/cadille.png' alt='Panier vide' width='64' height='64'></div>
    <h2 class='cart-empty-title'>Votre panier est vide</h2>
    <p class='cart-empty-sub'>Découvrez nos jeux et commencez à remplir votre panier.</p>
    <div class='cart-empty-actions'>
      <a href='/NEBULA/boutique.php' class='btn btn-primary'>Voir la boutique</a>
      <a href='/NEBULA/jeux.php' class='btn btn-outline'>Parcourir les jeux</a>
    </div>
  </div>

<!-- État 3 : Panier avec articles → liste + résumé des prix -->
<?php else: ?>
  <div class='cart-layout'>
    <!-- Liste des articles du panier -->
    <div class='cart-items'>
      <?php foreach ($panier as $item): ?>
        <div class='cart-item'>
          <div class='cart-item-info'>
            <h4 class='cart-item-name'><?= htmlspecialchars($item['nom']) ?></h4>
            <p class='cart-item-price'><?= number_format($item['prix'], 2) ?> €</p>
          </div>
          <div class='cart-item-actions'>
            <!-- Afficher la quantité si > 1 (produits boutique) -->
            <?php if ($item['categorie'] === 'boutique' && $item['quantite'] > 1): ?>
              <span>x<?= $item['quantite'] ?></span>
            <?php endif; ?>
            <p class='cart-item-total'><?= number_format($item['prix'] * $item['quantite'], 2) ?> €</p>
            <a href='?remove=<?= $item['jeu_id'] ?>' class='cart-remove' title='Supprimer'>
              <img src='/NEBULA/public/assets/img/icons/ecommerce/poubelle.png' alt='Supprimer' width='18' height='18'>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Résumé : sous-total, TVA, total et bouton payer (vide le panier) -->
    <div class='cart-summary'>
      <p>Sous-total: <?= number_format($sousTotal, 2) ?> €</p>
      <p>TVA (20%): <?= number_format($tva, 2) ?> €</p>
      <p><strong>Total: <?= number_format($total, 2) ?> €</strong></p>
      <a href='panier.php?vider=1' class='btn btn-primary'>Payer</a>
    </div>
  </div>
<?php endif; ?>

</section>

<?php require 'includes/footer.php'; ?>
