// Sélectionner tous les boutons de question et écouter le clic
document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', () => {
    // Récupérer l'élément parent .faq-item du bouton cliqué
    const item = btn.closest('.faq-item');
    // Vérifier si cette question est déjà ouverte
    const open = item.classList.contains('open');
    // Fermer toutes les questions ouvertes
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    // Si la question était fermée, l'ouvrir
    if (!open) item.classList.add('open');
  });
});
