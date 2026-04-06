/* dashboard.js */

document.addEventListener('DOMContentLoaded', function() {

    function esc(s) {
        s = s || '';
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    var PRICES    = [9.99, 14.99, 19.99, 24.99, 29.99, 39.99, 49.99, 59.99, 69.99, 74.99];
    var SESSIONS  = ['Il y a 2 heures', 'Hier', 'Il y a 3 jours', 'Il y a 1 semaine', 'Il y a 2 semaines'];
    var PLAYTIMES = [8.5, 14.2, 22.7, 31.4, 47.6, 63.1, 89.4, 112.8, 138.3, 204.9, 267.5, 311.0];

    function getPrice(id) {
        var price = PRICES[Math.abs(id * 7 + 13) % PRICES.length];
        return price.toFixed(2).replace('.', ',') + ' \u20ac';
    }

    function getPlaytime(id) {
        return PLAYTIMES[Math.abs(id * 3 + 7) % PLAYTIMES.length];
    }

    function getSession(id) {
        return SESSIONS[Math.abs(id * 11 + 5) % SESSIONS.length];
    }

    function fmtHours(h) {
        if (h >= 1) {
            var heures = Math.floor(h);
            var minutes = h % 1 > 0 ? Math.round((h % 1) * 60) + 'm' : '';
            return heures + 'h' + minutes;
        }
        return Math.round(h * 60) + 'min';
    }

    fetch('/NEBULA/api/games.php')
        .then(function(r) {
            if (!r.ok) { return; }
            return r.json();
        })
        .then(function(games) {
            if (!games || !Array.isArray(games) || games.length === 0) {
                return;
            }

            var included = [];
            var owned = [];
            for (var i = 0; i < games.length; i++) {
                if (games[i].id_jeu % 2 !== 0) {
                    included.push(games[i]);
                } else {
                    owned.push(games[i]);
                }
            }
            var all = included.concat(owned);

            /* Stats */
            var totalHours = 0;
            var limit = all.length < 8 ? all.length : 8;
            for (var i = 0; i < limit; i++) {
                totalHours += getPlaytime(all[i].id_jeu);
            }
            var statH = document.getElementById('statHeures');
            var statJ = document.getElementById('statJeux');
            if (statH) { statH.textContent = fmtHours(totalHours); }
            if (statJ) { statJ.textContent = all.length < 24 ? all.length : 24; }

            /* Récemment joués */
            var recentEl = document.getElementById('dbRecentGames');
            if (recentEl) {
                recentEl.innerHTML = '';
                var recentLimit = games.length < 3 ? games.length : 3;
                for (var i = 0; i < recentLimit; i++) {
                    var g = games[i];
                    var pt = getPlaytime(g.id_jeu);
                    var pct = Math.min(100, Math.round((pt / 150) * 100));
                    var imgHtml = '';
                    if (g.featured_url || g.image_url) {
                        imgHtml = '<img src="' + esc(g.featured_url || g.image_url) + '" alt="' + esc(g.titre) + '">';
                    } else {
                        imgHtml = '<div class="db-recent-placeholder"></div>';
                    }
                    var genre = (g.genre || '').split(',')[0];
                    var div = document.createElement('div');
                    div.className = 'db-recent-card';
                    div.innerHTML =
                        '<a href="/NEBULA/produit.php?id=' + g.id_jeu + '" class="db-recent-img">' +
                            imgHtml +
                            '<div class="db-recent-img-overlay"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>' +
                        '</a>' +
                        '<div class="db-recent-info">' +
                            '<div class="db-recent-title">' + esc(g.titre) + '</div>' +
                            '<div class="db-recent-genre">' + esc(genre) + '</div>' +
                            '<div class="db-recent-progress">' +
                                '<div class="db-recent-progress-bar"><div class="db-recent-progress-fill" style="width:' + pct + '%"></div></div>' +
                                '<div class="db-recent-hours">' + fmtHours(pt) + ' jou\u00e9es</div>' +
                            '</div>' +
                            '<div class="db-recent-session">' + getSession(g.id_jeu) + '</div>' +
                            '<a href="/NEBULA/produit.php?id=' + g.id_jeu + '" class="db-recent-play">' +
                                '<svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg> Continuer' +
                            '</a>' +
                        '</div>';
                    recentEl.appendChild(div);
                }
            }

            /* Bibliothèque */
            var libGrid = document.getElementById('dbLibGrid');
            var tabs = document.querySelectorAll('#dbLibTabs .db-lib-tab');

            function renderLib(tab) {
                if (!libGrid) { return; }
                var list;
                if (tab === 'included') {
                    list = included;
                } else if (tab === 'owned') {
                    list = owned;
                } else {
                    list = all;
                }
                libGrid.innerHTML = '';
                var max = list.length < 12 ? list.length : 12;
                for (var i = 0; i < max; i++) {
                    var g = list[i];
                    var isPurchase = g.id_jeu % 2 === 0;
                    var imgHtml = g.image_url
                        ? '<img src="' + esc(g.image_url) + '" alt="' + esc(g.titre) + '">'
                        : '<div class="db-game-thumb-placeholder"></div>';
                    var badgeClass = isPurchase ? 'db-thumb-badge--buy' : 'db-thumb-badge--incl';
                    var badgeText = isPurchase ? getPrice(g.id_jeu) : '\u2713';
                    var a = document.createElement('a');
                    a.className = 'db-game-thumb';
                    a.href = '/NEBULA/produit.php?id=' + g.id_jeu;
                    a.title = g.titre || '';
                    a.innerHTML =
                        imgHtml +
                        '<div class="db-thumb-badge ' + badgeClass + '">' + badgeText + '</div>' +
                        '<div class="db-game-thumb-overlay"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>';
                    libGrid.appendChild(a);
                }
            }

            if (tabs.length) {
                for (var i = 0; i < tabs.length; i++) {
                    tabs[i].addEventListener('click', function() {
                        for (var j = 0; j < tabs.length; j++) {
                            tabs[j].classList.remove('active');
                        }
                        this.classList.add('active');
                        renderLib(this.dataset.tab);
                    });
                }
            }
            renderLib('all');

            /* Activité */
            var feed = document.getElementById('dbActivityFeed');
            if (feed) {
                var g0 = games[0] ? games[0].titre : '';
                var g1 = games[1] ? games[1].titre : '';
                var g2 = games[2] ? games[2].titre : '';
                var acts = [
                    { icon: '\u25b6', label: 'Vous avez jou\u00e9 \u00e0 ' + esc(g0), time: 'Il y a 2h',      col: 'var(--accent)' },
                    { icon: '\u2605', label: 'Succ\u00e8s d\u00e9bloqu\u00e9 dans ' + esc(g0),  time: 'Il y a 3h',     col: '#f59e0b' },
                    { icon: '\u25b6', label: 'Vous avez jou\u00e9 \u00e0 ' + esc(g1), time: 'Hier',            col: 'var(--accent)' },
                    { icon: '\u2193', label: esc(g2) + ' ajout\u00e9 \u00e0 la biblioth\u00e8que', time: 'Il y a 2 jours', col: '#34d399' },
                    { icon: '\u2605', label: '3 nouveaux succ\u00e8s d\u00e9bloqu\u00e9s', time: 'Il y a 4 jours', col: '#f59e0b' },
                    { icon: '\u2193', label: 'Biblioth\u00e8que mise \u00e0 jour', time: 'Il y a 1 sem.', col: 'var(--text-muted)' }
                ];
                var rows = '';
                for (var i = 0; i < acts.length; i++) {
                    var a = acts[i];
                    if (a.label.indexOf('undefined') !== -1) { continue; }
                    rows +=
                        '<div class="db-act-row">' +
                            '<div class="db-act-dot" style="color:' + a.col + '">' + a.icon + '</div>' +
                            '<div class="db-act-info"><div class="db-act-label">' + a.label + '</div><div class="db-act-time">' + a.time + '</div></div>' +
                        '</div>';
                }
                feed.innerHTML = rows;
            }
        })
        .catch(function() {});

});
