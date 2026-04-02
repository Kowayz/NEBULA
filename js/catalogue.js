/* ============================================================
   catalogue.js — Fetch API IGDB + filtres genre + recherche
   ============================================================ */

(function () {
  'use strict';

  const API_URL    = '/NEBULA/api/games.php?limit=50';
  const grid       = document.getElementById('catalogueGrid');
  const noResults  = document.getElementById('noResults');
  const genresWrap = document.getElementById('filterGenres');
  const searchInput= document.getElementById('searchInput');
  const countEl    = document.getElementById('gameCount');

  let games       = [];
  let activeGenre = 'tous';
  let searchTerm  = '';

  // ── HTML escape ─────────────────────────────────────────────
  function esc(str) {
    return (str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ── Build genre filter buttons ───────────────────────────────
  function buildFilters() {
    const genres = new Set();
    games.forEach(g => {
      (g.genre || '').split(',').forEach(t => {
        const tag = t.trim();
        if (tag) genres.add(tag);
      });
    });

    genresWrap.innerHTML = '<button class="filter-btn active" data-genre="tous">Tous</button>';

    [...genres].sort().forEach(genre => {
      const btn = document.createElement('button');
      btn.className    = 'filter-btn';
      btn.dataset.genre = genre.toLowerCase();
      btn.textContent  = genre;
      genresWrap.appendChild(btn);
    });

    genresWrap.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        genresWrap.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeGenre = btn.dataset.genre;
        applyFilters();
      });
    });
  }

  // ── Render all game cards ────────────────────────────────────
  function renderCards() {
    grid.innerHTML = '';

    games.forEach(g => {
      const tags       = (g.genre || '').split(',').map(t => t.trim()).filter(Boolean);
      const firstGenre = tags[0] ? tags[0].toLowerCase() : '';

      const card = document.createElement('a');
      card.className         = 'catalogue-card';
      card.href              = '/NEBULA/produit.php?id=' + g.id_jeu;
      card.dataset.genre     = firstGenre;
      card.dataset.genres    = tags.map(t => t.toLowerCase()).join('|');
      card.dataset.title     = (g.titre || '').toLowerCase();

      const tagsHtml = tags.length
        ? '<div class="catalogue-card-tags">' +
            tags.slice(0, 2).map(t => '<span>' + esc(t) + '</span>').join('') +
          '</div>'
        : '';

      const imgHtml = g.image_url
        ? '<img src="' + esc(g.image_url) + '" alt="' + esc(g.titre) + '" loading="lazy">'
        : '<div class="catalogue-card-placeholder"></div>';

      card.innerHTML =
        '<div class="catalogue-card-poster">' + imgHtml + '</div>' +
        '<div class="catalogue-card-overlay">' +
          tagsHtml +
          '<div class="catalogue-card-title">' + esc(g.titre) + '</div>' +
          (g.description ? '<div class="catalogue-card-desc">' + esc(g.description) + '</div>' : '') +
          '<div class="catalogue-play-btn">' +
            '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>' +
            'Jouer' +
          '</div>' +
        '</div>';

      grid.appendChild(card);
    });

    if (countEl) countEl.textContent = games.length;
    applyFilters();
  }

  // ── Filter visible cards ─────────────────────────────────────
  function applyFilters() {
    const cards = grid.querySelectorAll('.catalogue-card');
    let visible = 0;

    cards.forEach(card => {
      const genres   = (card.dataset.genres || card.dataset.genre || '').toLowerCase().split('|');
      const title    = (card.dataset.title || '').toLowerCase();
      const genreOk  = activeGenre === 'tous' || genres.includes(activeGenre);
      const searchOk = searchTerm === '' || title.includes(searchTerm);

      if (genreOk && searchOk) {
        card.removeAttribute('hidden');
        visible++;
      } else {
        card.setAttribute('hidden', '');
      }
    });

    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
  }

  // ── Fetch games from API ─────────────────────────────────────
  async function loadGames() {
    try {
      const res = await fetch(API_URL);
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();
      if (!Array.isArray(data)) throw new Error('Réponse invalide');
      games = data;
    } catch (err) {
      grid.innerHTML =
        '<p class="catalogue-api-error">Impossible de charger le catalogue. Réessayez plus tard.</p>';
      if (countEl) countEl.textContent = '0';
      return;
    }

    buildFilters();
    renderCards();
  }

  // ── Search input ─────────────────────────────────────────────
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      searchTerm = searchInput.value.toLowerCase().trim();
      applyFilters();
    });
  }

  loadGames();
})();
