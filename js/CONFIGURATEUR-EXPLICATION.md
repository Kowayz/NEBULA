# Explication de configurateur.js

## À quoi sert ce fichier ?

Ce script JavaScript gère la page **Configurateur** (`configurateur.php`) de Nebula.
L'utilisateur compose son bouquet personnalisé en **3 étapes** :

1. Choisir des **accessoires** (t-shirt, mug, etc.)
2. Choisir une **offre** d'abonnement (Starter, Gamer ou Ultra)
3. Ajouter des **options** supplémentaires (ray tracing, support, etc.)

À droite de la page, un **résumé sidebar** affiche en temps réel les éléments sélectionnés et le prix total. Ce résumé se met à jour **à chaque clic** grâce à la fonction `majResume()`.

---

## Structure du fichier

Le fichier est découpé en **5 blocs** simples, dans cet ordre :

| Bloc | Lignes | Rôle |
|---|---|---|
| Variables | 4-7 | Stocker les prix et le nom du plan |
| `majResume()` | 10-55 | Mettre à jour l'affichage du résumé |
| Étape 1 | 63-91 | Gérer les clics sur les accessoires |
| Étape 2 | 98-131 | Gérer les clics sur les offres |
| Étape 3 | 138-162 | Gérer les clics sur les options |
| Commander | 169-176 | Rediriger vers le panier |

---

## Les 4 variables globales (lignes 4-7)

```js
var prixBoutique = 0;   // total des accessoires sélectionnés
var prixOffre    = 0;   // prix de l'abonnement choisi
var prixOptions  = 0;   // total des options cochées
var nomOffre     = '';   // nom du plan (ex: "Gamer")
```

Ce sont les seules données du script. On a **un compteur par étape** et le **nom du plan**.
À chaque clic utilisateur, on modifie ces variables, puis on appelle `majResume()` pour rafraîchir la sidebar.

---

## La fonction `majResume()` (lignes 10-55)

C'est **la fonction centrale** du script. Elle est appelée après chaque action et fait 4 choses :

### 1. Afficher le total (ligne 14)

```js
var total = prixBoutique + prixOffre + prixOptions;
document.getElementById('summaryTotal').textContent = total.toFixed(2).replace('.', ',') + ' €';
```

- On additionne les 3 prix pour obtenir le total.
- `document.getElementById('summaryTotal')` → récupère l'élément HTML qui a l'id `summaryTotal`.
- `.textContent = ...` → remplace le texte affiché.
- `total.toFixed(2)` → arrondit à 2 décimales. Exemple : `24.9` devient `"24.90"`.
- `.replace('.', ',')` → remplace le point par une virgule pour le format français : `"24,90 €"`.

### 2. Afficher le nom et le prix de l'offre (lignes 17-18)

```js
document.getElementById('summaryPlanName').textContent = nomOffre || '-';
document.getElementById('summaryPlanPrice').textContent = prixOffre > 0 ? prixOffre.toFixed(2).replace('.', ',') + ' €' : '-';
```

- `nomOffre || '-'` → si `nomOffre` est vide (`""`), on affiche un tiret `"-"` à la place. C'est le cas au tout premier chargement.
- `prixOffre > 0 ? ... : '-'` → c'est un **opérateur ternaire** (un `if` raccourci). Si le prix est supérieur à 0, on affiche le prix formaté. Sinon, un tiret.

### 3. Afficher la liste des accessoires sélectionnés (lignes 21-36)

```js
var elBoutique = document.getElementById('summaryBoutique');
var cartesProduits = document.querySelectorAll('.merch-card.selected');
```

- `document.querySelectorAll('.merch-card.selected')` → récupère **toutes les cartes accessoires** qui ont la classe CSS `selected`. Si l'utilisateur a sélectionné le T-Shirt et le Mug, ça retourne ces 2 cartes.

```js
if (cartesProduits.length === 0) {
  elBoutique.innerHTML = '<span class="summary-empty">Aucun article</span>';
}
```

- Si aucune carte n'est sélectionnée (`.length === 0`), on affiche "Aucun article".

```js
else {
  var html = '';
  for (var i = 0; i < cartesProduits.length; i++) {
    var nom  = cartesProduits[i].querySelector('button').dataset.nom;
    var prix = parseFloat(cartesProduits[i].querySelector('button').dataset.prix);
    html += '<div class="summary-line">';
    html += '<span class="summary-line-name">' + nom + '</span>';
    html += '<span class="summary-line-price">+' + prix.toFixed(2).replace('.', ',') + ' €</span>';
    html += '</div>';
  }
  elBoutique.innerHTML = html;
}
```

- Sinon, on **boucle** sur chaque carte sélectionnée.
- `cartesProduits[i].querySelector('button')` → dans la carte, on cherche le bouton pour lire ses `data-` attributs.
- `.dataset.nom` → lit la valeur de `data-nom` (ex: `data-nom="T-Shirt Nebula"` → `"T-Shirt Nebula"`).
- On construit du HTML ligne par ligne en concaténant des chaînes avec `+`.
- `elBoutique.innerHTML = html` → on injecte le HTML construit dans le conteneur du résumé.

### 4. Afficher la liste des options sélectionnées (lignes 39-54)

Exactement le même principe que pour les accessoires, mais :
- On cible `.option-card.selected` au lieu de `.merch-card.selected`.
- Le nom est lu depuis `.option-name` (un `<div>` dans la carte) au lieu de `data-nom`.
- Le prix est lu depuis `data-price` au lieu de `data-prix` (c'est un choix de nommage dans le HTML).

---

## Étape 1 : Accessoires — choix multiple (lignes 63-91)

### Récupérer les boutons

```js
var btnsProduits = document.querySelectorAll('.merch-card button');
```

`querySelectorAll` retourne **tous les éléments** qui correspondent au sélecteur CSS. Ici : tous les `<button>` qui sont à l'intérieur d'un élément `.merch-card`. Il y en a 6 (t-shirt, hoodie, mug, casquette, mousepad, stickers).

### Boucle pour écouter les clics

```js
for (var p = 0; p < btnsProduits.length; p++) {
  btnsProduits[p].addEventListener('click', function() {
```

- On parcourt chaque bouton avec une boucle `for`.
- `addEventListener('click', function() { ... })` → on dit au navigateur : "quand ce bouton est cliqué, exécute cette fonction".

### Dans la fonction de clic

```js
var btn  = this;                           // "this" = le bouton qui a été cliqué
var card = btn.closest('.merch-card');      // on remonte au parent .merch-card
var prix = parseFloat(btn.dataset.prix);   // on lit le prix depuis data-prix
```

- `this` → mot-clé JavaScript qui, dans un écouteur d'événement, représente **l'élément qui a déclenché l'événement** (ici, le bouton cliqué).
- `btn.closest('.merch-card')` → remonte dans l'arbre HTML pour trouver le premier parent qui a la classe `merch-card`. C'est la carte complète.
- `btn.dataset.prix` → les attributs HTML `data-xxx` sont accessibles en JS via `element.dataset.xxx`. Exemple : `<button data-prix="29.99">` → `btn.dataset.prix` retourne `"29.99"` (une chaîne de texte).
- `parseFloat("29.99")` → convertit la chaîne `"29.99"` en nombre `29.99`. Sans ça, `"29.99" + "14.99"` donnerait `"29.9914.99"` (concaténation de texte, pas addition).

### Toggle sélection / désélection

```js
if (card.classList.contains('selected')) {
  // DÉSÉLECTIONNER
  card.classList.remove('selected');       // on retire la classe CSS "selected"
  btn.textContent = 'Ajouter';            // on remet le texte du bouton
  btn.classList.remove('btn-primary');     // on retire le style "sélectionné"
  btn.classList.add('btn-outline');        // on remet le style "non sélectionné"
  prixBoutique = prixBoutique - prix;     // on soustrait le prix
}
else {
  // SÉLECTIONNER
  card.classList.add('selected');          // on ajoute la classe CSS "selected"
  btn.textContent = 'Ajouté';             // on change le texte du bouton
  btn.classList.remove('btn-outline');     // on retire le style "non sélectionné"
  btn.classList.add('btn-primary');        // on met le style "sélectionné"
  prixBoutique = prixBoutique + prix;     // on ajoute le prix
}
```

- `classList.contains('selected')` → retourne `true` si la carte a déjà la classe `selected`, `false` sinon.
- C'est un **toggle** : si c'est sélectionné on désélectionne, sinon on sélectionne.
- Les classes `btn-primary` (bouton violet/plein) et `btn-outline` (bouton bordure) viennent du CSS `base.css`.

### Appel à majResume()

```js
majResume();
```

Après chaque clic, on appelle `majResume()` pour que la sidebar se mette à jour avec les bons noms, prix et total.

---

## Étape 2 : Offre — choix unique (lignes 98-131)

### Différence avec l'étape 1

Ici on ne peut choisir qu'**un seul plan**. Donc au lieu de toggle, on :
1. **Désélectionne TOUT** d'abord
2. **Sélectionne** uniquement celui qui a été cliqué

### Désélectionner tous les plans

```js
for (var j = 0; j < cardsOffres.length; j++) {
  cardsOffres[j].classList.remove('selected');
  btnsOffres[j].textContent = 'Sélectionner';
  btnsOffres[j].classList.remove('btn-primary');
  btnsOffres[j].classList.add('btn-outline');
}
```

On boucle sur les 3 cartes (Starter, Gamer, Ultra) et on les remet toutes à l'état "non sélectionné".

### Sélectionner celle cliquée

```js
btn.textContent = 'Sélectionné';
btn.classList.remove('btn-outline');
btn.classList.add('btn-primary');
btn.closest('.pricing-card').classList.add('selected');
```

### Enregistrer le choix

```js
prixOffre = parseFloat(btn.dataset.price);
var plans = { starter: 'Starter', gamer: 'Gamer', ultra: 'Ultra' };
nomOffre  = plans[btn.dataset.plan];
```

- `btn.dataset.price` → lit `data-price` (ex: `"24.99"`).
- `btn.dataset.plan` → lit `data-plan` (ex: `"gamer"`).
- `plans` est un **objet** qui fait la correspondance entre la clé technique (`"gamer"`) et le nom affiché (`"Gamer"`).
- `plans["gamer"]` retourne `"Gamer"`.

### Sélection par défaut au chargement

```js
if (btnsOffres.length > 1) {
  btnsOffres[1].click();
}
```

Au chargement de la page, on **simule un clic** sur le 2e bouton (index `[1]` = Gamer).
`btnsOffres[1].click()` déclenche exactement le même code que si l'utilisateur avait cliqué dessus avec sa souris. Comme ça, le plan Gamer est pré-sélectionné.

---

## Étape 3 : Options — choix multiple (lignes 138-162)

**Exactement le même principe que l'étape 1**, mais :

| | Étape 1 (Accessoires) | Étape 3 (Options) |
|---|---|---|
| Sélecteur CSS | `.merch-card button` | `.option-card button` |
| Attribut prix | `data-prix` | `data-price` |
| Variable modifiée | `prixBoutique` | `prixOptions` |

Le reste du code (toggle selected, changer le texte, appeler `majResume()`) est identique.

---

## Bouton Commander (lignes 169-176)

```js
var btnCommander = document.getElementById('summaryOrderBtn');
if (btnCommander) {
  btnCommander.addEventListener('click', function(e) {
    e.preventDefault();
    var total = prixBoutique + prixOffre + prixOptions;
    window.location.href = '/NEBULA/panier.php?add=999&cat=boutique&nom=Bouquet+Nebula&prix=' + total.toFixed(2);
  });
}
```

- `document.getElementById('summaryOrderBtn')` → récupère le bouton "Commander" par son id.
- `if (btnCommander)` → vérifie que le bouton existe (sécurité).
- `e.preventDefault()` → le bouton est un lien `<a>`. Sans cette ligne, le navigateur suivrait le `href` du lien. Avec, on **empêche ce comportement** pour gérer la redirection nous-mêmes.
- On calcule le total final.
- `window.location.href = '...'` → redirige le navigateur vers cette URL. Le prix est passé dans l'URL comme **paramètre GET** (`?prix=69.98`). La page `panier.php` récupère ce prix avec `$_GET['prix']`.

---

## Résumé visuel du flux

```
L'utilisateur clique sur un bouton (accessoire, offre ou option)
        │
        ▼
Le code vérifie : est-ce que la carte est déjà sélectionnée ?
        │
   ┌────┴────┐
   │ OUI     │ NON
   ▼         ▼
Retirer      Ajouter
"selected"   "selected"
Texte →      Texte →
"Ajouter"    "Ajouté"
Prix - X     Prix + X
   │         │
   └────┬────┘
        │
        ▼
majResume() est appelée
        │
        ▼
La sidebar se met à jour :
  - Liste des accessoires sélectionnés
  - Nom et prix de l'offre
  - Liste des options sélectionnées
  - Total général recalculé
```

---

## Méthodes JavaScript utilisées (résumé)

| Méthode | Ce qu'elle fait | Exemple |
|---|---|---|
| `document.getElementById('x')` | Récupère un élément par son id | `getElementById('summaryTotal')` |
| `document.querySelectorAll('.x')` | Récupère tous les éléments qui matchent le sélecteur CSS | `querySelectorAll('.merch-card button')` |
| `element.closest('.x')` | Remonte dans le DOM pour trouver le parent le plus proche | `btn.closest('.merch-card')` |
| `element.querySelector('.x')` | Cherche le premier enfant qui matche | `card.querySelector('button')` |
| `element.classList.add('x')` | Ajoute une classe CSS | `card.classList.add('selected')` |
| `element.classList.remove('x')` | Retire une classe CSS | `card.classList.remove('selected')` |
| `element.classList.contains('x')` | Vérifie si l'élément a cette classe | `card.classList.contains('selected')` |
| `element.textContent = '...'` | Change le texte affiché | `btn.textContent = 'Ajouté'` |
| `element.innerHTML = '...'` | Change le contenu HTML | `elBoutique.innerHTML = html` |
| `element.dataset.xxx` | Lit un attribut `data-xxx` du HTML | `btn.dataset.prix` |
| `element.addEventListener('click', fn)` | Exécute `fn` quand on clique | Voir toutes les étapes |
| `parseFloat('24.99')` | Convertit un texte en nombre décimal | `parseFloat(btn.dataset.prix)` |
| `n.toFixed(2)` | Arrondit à 2 décimales | `24.9` → `"24.90"` |
| `str.replace('.', ',')` | Remplace un caractère dans un texte | `"24.90"` → `"24,90"` |
| `e.preventDefault()` | Empêche le comportement par défaut | Empêche le lien de naviguer |
| `window.location.href = '...'` | Redirige le navigateur | Vers `panier.php` |
