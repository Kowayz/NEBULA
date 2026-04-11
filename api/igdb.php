<?php
// igdb.php — Connexion à l'API IGDB (jeux vidéo)

// ── 1. Chargement des clés API depuis .env.local ──
$envPath = dirname(__DIR__) . '/.env.local';
$env = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (strpos($line, '=') && $line[0] !== '#') {
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
}
define('IGDB_CLIENT_ID', $env['IGDB_CLIENT_ID']);
define('IGDB_CLIENT_SECRET', $env['IGDB_CLIENT_SECRET']);

// ── 2. Appel HTTP (curl) ──
function igdb_curl(string $url, string $method = 'POST', array $headers = [], string $body = ''): string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

// ── 3. Token OAuth2 Twitch (avec cache fichier) ──
function igdb_token(): string {
    $cacheFile = sys_get_temp_dir() . '/nebula_igdb_token.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached['expires'] > time()) return $cached['token'];
    }

    $url = 'https://id.twitch.tv/oauth2/token'
         . '?client_id=' . IGDB_CLIENT_ID
         . '&client_secret=' . IGDB_CLIENT_SECRET
         . '&grant_type=client_credentials';

    $data = json_decode(igdb_curl($url), true);

    file_put_contents($cacheFile, json_encode([
        'token' => $data['access_token'],
        'expires' => time() + $data['expires_in'],
    ]));
    return $data['access_token'];
}

// ── 4. Requête à l'API IGDB ──
function igdb_query(string $endpoint, string $body): array {
    $res = igdb_curl(
        'https://api.igdb.com/v4/' . $endpoint,
        'POST',
        [
            'Client-ID: ' . IGDB_CLIENT_ID,
            'Authorization: Bearer ' . igdb_token(),
            'Content-Type: text/plain',
        ],
        $body
    );
    return json_decode($res, true);
}

// ── 5. Liste des jeux du catalogue (jeux.php) ──
function igdb_get_games(int $limit = 24): array {
    $since = strtotime('2021-01-01');
    $data = igdb_query('games', "
        fields id,name,cover.url;
        where cover != null & first_release_date > {$since};
        sort rating_count desc;
        limit {$limit};
    ");

    $games = [];
    foreach ($data as $g) {
        $games[] = [
            'id_jeu' => $g['id'],
            'titre' => $g['name'],
            'image_url' => 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']),
        ];
    }
    return $games;
}

// ── 6. Fiche détaillée d'un jeu (produit.php) ──
function igdb_get_game(int $id): ?array {
    $data = igdb_query('games', "
        fields id,name,summary,cover.url,screenshots.url,rating,
               first_release_date,involved_companies.company.name,
               artworks.url,videos.video_id;
        where id = {$id};
    ");

    if (empty($data[0])) return null;
    $g = $data[0];

    $devs = [];
    foreach ($g['involved_companies'] ?? [] as $ic) {
        $devs[] = $ic['company']['name'];
    }

    $dateSortie = isset($g['first_release_date']) ? date('Y-m-d', $g['first_release_date']) : null;
    $coverUrl = isset($g['cover']['url']) ? 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']) : null;

    $heroUrl = null;
    if (isset($g['artworks'][0]['url'])) {
        $heroUrl = 'https:' . str_replace('t_thumb', 't_1080p', $g['artworks'][0]['url']);
    } elseif (isset($g['screenshots'][0]['url'])) {
        $heroUrl = 'https:' . str_replace('t_thumb', 't_1080p', $g['screenshots'][0]['url']);
    }

    $screenshots = [];
    foreach ($g['screenshots'] ?? [] as $shot) {
        $screenshots[] = 'https:' . str_replace('t_thumb', 't_720p', $shot['url']);
    }

    $trailerId = null;
    foreach ($g['videos'] ?? [] as $vid) {
        $trailerId = $vid['video_id'];
        break;
    }

    return [
        'id_jeu' => $g['id'],
        'titre' => $g['name'],
        'description' => $g['summary'] ?? '',
        'image_url' => $coverUrl,
        'cover_url' => $coverUrl,
        'hero_url' => $heroUrl,
        'screenshots' => $screenshots,
        'trailer_id' => $trailerId,
        'rating' => $g['rating'] ?? null,
        'developpeur' => implode(', ', $devs),
        'date_sortie' => $dateSortie,
    ];
}

// ── 7. Jeux tendances (index.php) ──
function igdb_get_trending_games(int $limit = 3): array {
    $since = strtotime('2025-01-01');
    $data = igdb_query('games', "
        fields id,name,cover.url;
        where cover != null & first_release_date > {$since};
        sort rating_count desc;
        limit {$limit};
    ");

    $games = [];
    foreach ($data as $g) {
        $games[] = [
            'id_jeu' => $g['id'],
            'titre' => $g['name'],
            'image_url' => 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']),
        ];
    }
    return $games;
}

// ── 8. Jeux inclus streaming (boutique.php) ──
function igdb_get_streaming_games(int $limit = 200): array {
    $data = igdb_query('games', "
        fields id,name,cover.url,rating;
        where cover != null & rating > 70;
        sort rating_count desc;
        limit {$limit};
    ");

    $games = [];
    foreach ($data as $g) {
        $games[] = [
            'id_jeu' => $g['id'],
            'titre' => $g['name'],
            'image_url' => 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']),
            'type' => 'streaming',
        ];
    }
    return $games;
}

// ── 9. Jeux achetables premium (boutique.php) ──
function igdb_get_premium_games(int $limit = 50): array {
    $since = strtotime('2023-01-01');
    $data = igdb_query('games', "
        fields id,name,cover.url,rating,first_release_date;
        where cover != null & rating > 85 & first_release_date > {$since};
        sort rating_count desc;
        limit {$limit};
    ");

    $games = [];
    foreach ($data as $g) {
        $games[] = [
            'id_jeu' => $g['id'],
            'titre' => $g['name'],
            'image_url' => 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']),
            'type' => 'premium',
            'prix' => 39.99,
        ];
    }
    return $games;
}
