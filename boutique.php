<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Récupération des catégories pour les filtres
$categories = $pdo->query("SELECT * FROM categorie")->fetchAll();
// Récupération des produits
$produits = $pdo->query("SELECT p.*, c.libelle as cat_nom FROM produit p JOIN categorie c ON p.id_cat = c.id_cat")->fetchAll();
?>

<section class="shop-header">
    <div class="container center-text">
        <h1>Boutique Matériel</h1>
        <p>Accessoires et équipements optimisés pour votre expérience NEBULA</p>
    </div>
</section>

<section class="shop-filters">
    <div class="container">
        <div class="filter-bar">
            <button class="filter-btn active" data-filter="all">Tous</button>
            <?php foreach($categories as $cat): ?>
                <button class="filter-btn" data-filter="<?= $cat['id_cat'] ?>"><?= $cat['libelle'] ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="shop-grid-section">
    <div class="container">
        <div class="products-grid">
            <?php foreach($produits as $p): ?>
            <article class="product-card" data-category="<?= $p['id_cat'] ?>">
                <div class="card-image">
                    <span class="badge-category"><?= $p['cat_nom'] ?></span>
                    <span class="badge-status">En stock</span>
                    <img src="<?= $p['image_url'] ?>" alt="<?= $p['nom_produit'] ?>">
                </div>
                <div class="card-content">
                    <h3><?= $p['nom_produit'] ?></h3>
                    <p class="short-desc"><?= substr($p['description'], 0, 60) ?>...</p>
                    <div class="card-footer-shop">
                        <span class="price"><?= $p['prix_unitaire'] ?>€</span>
                        <img src="assets/img/icon-box.png" alt="Box" class="mini-icon">
                    </div>
                    <a href="produit.php?id=<?= $p['id_produit'] ?>" class="btn-primary-small">Voir le produit</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>