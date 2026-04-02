<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require dirname(__DIR__) . '/includes/igdb.php';

// ── Auth ───────────────────────────────────────────────────────
$token = igdb_token();
if (!$token) { echo json_encode(['error' => 'IGDB auth failed']); exit; }

$id      = isset($_GET['id'])    ? (int)$_GET['id'] : null;
$limit   = isset($_GET['limit']) ? max(1, min((int)$_GET['limit'], 100)) : 20;
$related = array_key_exists('related', $_GET);

if ($id) {
    // ── Jeu unique ───────────────────────────────────────────────
    $data = igdb_query('games', "
        fields id,name,summary,genres.name,cover.url,artworks.url,screenshots.url,
               first_release_date,involved_companies.company.name,rating;
        where id = {$id};
    ", $token);

    $g = is_array($data) ? ($data[0] ?? null) : null;
    if (!$g) { echo json_encode(null); exit; }

    $artwork     = isset($g['artworks'][0]) ? igdb_cover($g['artworks'][0]['url'], 't_1080p') : null;
    $logo        = igdb_logo($g['name']);
    $shots = array_map(fn($a) => igdb_cover($a['url'], 't_1080p'), array_slice($g['artworks'] ?? [], 0, 3));
    if (count($shots) < 3) {
        foreach (array_slice($g['screenshots'] ?? [], 0, 3 - count($shots)) as $s) {
            $shots[] = igdb_cover($s['url'], 't_1080p');
        }
    }
    $screenshots = $shots;
    $heroUrl     = $screenshots
        ? igdb_cover(($g['screenshots'][0]['url'] ?? null), 't_1080p')
        : ($artwork ?? igdb_cover($g['cover']['url'] ?? null, 't_1080p'));

    $game = array_merge(igdb_map($g), [
        'image_url'   => igdb_cover($g['cover']['url'] ?? null, 't_1080p'),
        'cover_url'   => igdb_cover($g['cover']['url'] ?? null, 't_original'),
        'artwork_url' => $artwork,
        'hero_url'    => $heroUrl,
        'logo_url'    => $logo,
        'screenshots' => $screenshots,
    ]);

    $relatedGames = [];
    if ($related) {
        $genreNames  = array_column($g['genres'] ?? [], 'name');
        $genreFilter = '';
        if ($genreNames) {
            $names  = implode(',', array_map(fn($n) => '"' . addslashes($n) . '"', $genreNames));
            $genres = igdb_query('genres', "fields id; where name = ({$names}); limit 10;", $token);
            if ($genres) {
                $ids         = implode(',', array_column($genres, 'id'));
                $genreFilter = "genres = ({$ids}) &";
            }
        }
        $relData = igdb_query('games', "
            fields id,name,genres.name,cover.url;
            where {$genreFilter} id != {$id} & cover != null & rating > 75 & rating_count > 50;
            sort rating desc; limit 20;
        ", $token);
        if ($relData) {
            shuffle($relData);
            $relatedGames = array_map('igdb_map', array_slice($relData, 0, 4));
        }
    }

    echo json_encode(['game' => $game, 'related' => $relatedGames]);

} else {
    $fields  = "fields id,name,summary,genres.name,cover.url,artworks.url,screenshots.url,
               first_release_date,involved_companies.company.name,rating,rating_count,hypes;";
    $since   = strtotime('2020-01-01');

    // ── Hits récents (2023+) — jeux tendance actuellement sortis ──
    $released = igdb_query('games', "
        {$fields}
        where cover != null & genres != null & rating > 70 & rating_count > 30
              & first_release_date > {$since};
        sort rating_count desc; limit 50;
    ", $token);

    // ── Les plus attendus — unreleased avec beaucoup de hype ──
    $upcoming = igdb_query('games', "
        {$fields}
        where cover != null & genres != null & hypes > 200 & rating_count > 30;
        sort hypes desc; limit 25;
    ", $token);

    // ── Classiques populaires ──
    $classics = igdb_query('games', "
        {$fields}
        where cover != null & genres != null & rating > 85 & rating_count > 500;
        sort rating_count desc; limit 30;
    ", $token);

    // Fusionner en dédupliquant par id
    $seen   = [];
    $merged = [];
    foreach (array_merge($released ?: [], $upcoming ?: [], $classics ?: []) as $g) {
        if (!isset($seen[$g['id']])) {
            $seen[$g['id']] = true;
            $merged[]       = $g;
        }
    }

    if (empty($merged)) { echo json_encode([]); exit; }

    // DEBUG temporaire — à supprimer après
    header('X-Debug-Released: ' . count($released ?: []));
    header('X-Debug-Upcoming: ' . count($upcoming ?: []));
    header('X-Debug-Classics: ' . count($classics ?: []));
    header('X-Debug-Merged: '   . count($merged));

    $merged = array_slice($merged, 0, 60); // ← CHANGER ce nombre pour afficher plus/moins de jeux sur la page catalogue

    $games = [];
    foreach ($merged as $g) {
        $mapped               = igdb_map($g);
        $mapped['featured_url'] = isset($g['screenshots'][0])
            ? igdb_cover($g['screenshots'][0]['url'], 't_1080p')
            : (isset($g['artworks'][0])
                ? igdb_cover($g['artworks'][0]['url'], 't_1080p')
                : igdb_cover($g['cover']['url'] ?? null, 't_cover_big'));
        $mapped['logo_url']   = igdb_logo($g['name']);
        $games[]              = $mapped;
    }
    echo json_encode($games);
}
