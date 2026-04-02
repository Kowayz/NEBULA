/* ============================================================
   offres.js — FAQ accordion + interactions pricing
   ============================================================ */

(function () {
  'use strict';

  // ── Accordion FAQ ───────────────────────────────────────────
  document.querySelectorAll('.faq-question').forEach(q => {
    q.addEventListener('click', () => {
      const item    = q.closest('.faq-item');
      const wasOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
      if (!wasOpen) item.classList.add('open');
    });
  });

  // ── Mise en évidence du plan survolé ───────────────────────
  document.querySelectorAll('.pricing-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
      document.querySelectorAll('.pricing-card').forEach(c => {
        if (c !== card) c.style.opacity = '.72';
      });
    });
    card.addEventListener('mouseleave', () => {
      document.querySelectorAll('.pricing-card').forEach(c => {
        c.style.opacity = '';
      });
    });
  });

  // ── Aperçu des jeux (section offres) ───────────────────────
  const gamesGrid = document.getElementById('offresGamesGrid');

  if (gamesGrid) {
    fetch('/NEBULA/api/games.php?limit=6')
      .then(function (res) { return res.ok ? res.json() : Promise.reject(res.status); })
      .then(function (games) {
        if (!Array.isArray(games) || games.length === 0) return;

        gamesGrid.innerHTML = '';

        games.forEach(function (g) {
          const a = document.createElement('a');
          a.className = 'offres-game-card';
          a.href = '/NEBULA/produit.php?id=' + g.id_jeu;

          const imgHtml = g.image_url
            ? '<img src="' + esc(g.image_url) + '" alt="' + esc(g.titre) + '" loading="lazy">'
            : '<div class="offres-game-card-placeholder"></div>';

          a.innerHTML = imgHtml +
            '<div class="offres-game-card-label">' + esc(g.titre) + '</div>';

          gamesGrid.appendChild(a);
        });
      })
      .catch(function () {
        gamesGrid.innerHTML = '';
      });
  }

  function esc(str) {
    return (str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }
})();
