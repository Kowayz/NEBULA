// configurateur.js — Configurateur de bouquet Nebula

// ── Variables pour stocker les prix de chaque étape ──
var prixBoutique = 0;   // total des accessoires
var prixOffre    = 0;   // prix de l'abonnement choisi
var prixOptions  = 0;   // total des options
var nomOffre     = '';   // nom du plan (Starter, Gamer, Ultra)

// ── Fonction qui met à jour le résumé sidebar ──
function majResume() {
  var total = prixBoutique + prixOffre + prixOptions;

  // Afficher le total
  document.getElementById('summaryTotal').textContent = total.toFixed(2).replace('.', ',') + ' €';

  // Afficher le nom et le prix de l'offre
  document.getElementById('summaryPlanName').textContent = nomOffre || '-';
  document.getElementById('summaryPlanPrice').textContent = prixOffre > 0 ? prixOffre.toFixed(2).replace('.', ',') + ' €' : '-';

  // Afficher la liste des accessoires sélectionnés
  var elBoutique = document.getElementById('summaryBoutique');
  var cartesProduits = document.querySelectorAll('.merch-card.selected');
  if (cartesProduits.length === 0) {
    elBoutique.innerHTML = '<span class="summary-empty">Aucun article</span>';
  } else {
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

  // Afficher la liste des options sélectionnées
  var elOptions = document.getElementById('summaryOptionsList');
  var cartesOptions = document.querySelectorAll('.option-card.selected');
  if (cartesOptions.length === 0) {
    elOptions.innerHTML = '<span class="summary-empty">Aucune option</span>';
  } else {
    var htmlOpt = '';
    for (var j = 0; j < cartesOptions.length; j++) {
      var nomOpt  = cartesOptions[j].querySelector('.option-name').textContent;
      var prixOpt = parseFloat(cartesOptions[j].querySelector('button').dataset.price);
      htmlOpt += '<div class="summary-line">';
      htmlOpt += '<span class="summary-line-name">' + nomOpt + '</span>';
      htmlOpt += '<span class="summary-line-price">+' + prixOpt.toFixed(2).replace('.', ',') + ' €</span>';
      htmlOpt += '</div>';
    }
    elOptions.innerHTML = htmlOpt;
  }
}


// ══════════════════════════════════════════════════════════════
// ÉTAPE 1 : ACCESSOIRES (on peut en sélectionner plusieurs)
// ══════════════════════════════════════════════════════════════

// On récupère tous les boutons "Ajouter" des cartes accessoires
var btnsProduits = document.querySelectorAll('.merch-card button');

// Pour chaque bouton, on écoute le clic
for (var p = 0; p < btnsProduits.length; p++) {
  btnsProduits[p].addEventListener('click', function() {
    var btn  = this;                              // le bouton cliqué
    var card = btn.closest('.merch-card');         // la carte parente
    var prix = parseFloat(btn.dataset.prix);      // le prix depuis data-prix

    // Si déjà sélectionné → on désélectionne
    if (card.classList.contains('selected')) {
      card.classList.remove('selected');
      btn.textContent = 'Ajouter';
      btn.classList.remove('btn-primary');
      btn.classList.add('btn-outline');
      prixBoutique = prixBoutique - prix;
    }
    // Sinon → on sélectionne
    else {
      card.classList.add('selected');
      btn.textContent = 'Ajouté';
      btn.classList.remove('btn-outline');
      btn.classList.add('btn-primary');
      prixBoutique = prixBoutique + prix;
    }

    majResume();
  });
}


// ══════════════════════════════════════════════════════════════
// ÉTAPE 2 : OFFRE (un seul choix possible à la fois)
// ══════════════════════════════════════════════════════════════

var btnsOffres  = document.querySelectorAll('.pricing-card button');
var cardsOffres = document.querySelectorAll('.pricing-card');

for (var i = 0; i < btnsOffres.length; i++) {
  btnsOffres[i].addEventListener('click', function() {
    var btn = this;

    // D'abord on remet TOUTES les cartes à l'état "non sélectionné"
    for (var j = 0; j < cardsOffres.length; j++) {
      cardsOffres[j].classList.remove('selected');
      btnsOffres[j].textContent = 'Sélectionner';
      btnsOffres[j].classList.remove('btn-primary');
      btnsOffres[j].classList.add('btn-outline');
    }

    // Puis on sélectionne celle qui a été cliquée
    btn.textContent = 'Sélectionné';
    btn.classList.remove('btn-outline');
    btn.classList.add('btn-primary');
    btn.closest('.pricing-card').classList.add('selected');

    // On enregistre le prix et le nom du plan
    prixOffre = parseFloat(btn.dataset.price);
    var plans = { starter: 'Starter', gamer: 'Gamer', ultra: 'Ultra' };
    nomOffre  = plans[btn.dataset.plan];

    majResume();
  });
}

// Au chargement, on sélectionne Gamer par défaut (le 2e bouton)
if (btnsOffres.length > 1) {
  btnsOffres[1].click();
}


// ══════════════════════════════════════════════════════════════
// ÉTAPE 3 : OPTIONS (on peut en sélectionner plusieurs)
// ══════════════════════════════════════════════════════════════

var btnsOptions = document.querySelectorAll('.option-card button');

for (var k = 0; k < btnsOptions.length; k++) {
  btnsOptions[k].addEventListener('click', function() {
    var btn  = this;
    var card = btn.closest('.option-card');
    var prix = parseFloat(btn.dataset.price);

    if (card.classList.contains('selected')) {
      card.classList.remove('selected');
      btn.textContent = 'Ajouter';
      btn.classList.remove('btn-primary');
      btn.classList.add('btn-outline');
      prixOptions = prixOptions - prix;
    } else {
      card.classList.add('selected');
      btn.textContent = 'Ajouté';
      btn.classList.remove('btn-outline');
      btn.classList.add('btn-primary');
      prixOptions = prixOptions + prix;
    }

    majResume();
  });
}


// ══════════════════════════════════════════════════════════════
// BOUTON COMMANDER → envoie le total au panier
// ══════════════════════════════════════════════════════════════

var btnCommander = document.getElementById('summaryOrderBtn');
if (btnCommander) {
  btnCommander.addEventListener('click', function(e) {
    e.preventDefault();
    var total = prixBoutique + prixOffre + prixOptions;
    window.location.href = '/NEBULA/panier.php?add=999&cat=boutique&nom=Bouquet+Nebula&prix=' + total.toFixed(2);
  });
}

// Premier affichage du résumé
majResume();
