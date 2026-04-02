<?php
/**
 * includes/igdb.php — Helper partagé IGDB / SteamGridDB
 * Utilise cURL (plus fiable qu'url_fopen sous Apache/Laragon).
 */

// ── Charger .env.local ─────────────────────────────────────────
$_igdbEnv     = [];
$_igdbEnvPath = dirname(__DIR__) . '/.env.local';
if (file_exists($_igdbEnvPath)) {
    foreach (file($_igdbEnvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_igdbEnv[trim($k)] = trim($v);
    }
}

define('IGDB_CLIENT_ID',     $_igdbEnv['IGDB_CLIENT_ID']      ?? '');
define('IGDB_CLIENT_SECRET', $_igdbEnv['IGDB_CLIENT_SECRET']  ?? '');
define('SGDB_API_KEY',       $_igdbEnv['STEAMGRIDDB_API_KEY'] ?? '');

unset($_igdbEnv, $_igdbEnvPath);

// ── Helper cURL interne ────────────────────────────────────────
function _igdb_curl(string $url, string $method = 'GET', array $headers = [], string $body = ''): ?string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER     => $headers,
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($body !== '') curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $res = curl_exec($ch);
    curl_close($ch);
    return ($res !== false) ? $res : null;
}

// ── Token Twitch/IGDB (mis en cache) ──────────────────────────
function igdb_token(): ?string
{
    $cacheFile = sys_get_temp_dir() . '/nebula_igdb_token.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && ($cached['expires'] ?? 0) > time() + 60) {
            return $cached['token'];
        }
    }

    $url = 'https://id.twitch.tv/oauth2/token'
         . '?client_id='     . urlencode(IGDB_CLIENT_ID)
         . '&client_secret=' . urlencode(IGDB_CLIENT_SECRET)
         . '&grant_type=client_credentials';

    $res = _igdb_curl($url, 'POST');
    if (!$res) return null;

    $data  = json_decode($res, true);
    $token = $data['access_token'] ?? null;
    if ($token) {
        file_put_contents($cacheFile, json_encode([
            'token'   => $token,
            'expires' => time() + ($data['expires_in'] ?? 3600),
        ]));
    }
    return $token;
}

// ── Requête IGDB ───────────────────────────────────────────────
function igdb_query(string $endpoint, string $body, string $token): array
{
    $res = _igdb_curl(
        'https://api.igdb.com/v4/' . $endpoint,
        'POST',
        [
            'Client-ID: '    . IGDB_CLIENT_ID,
            'Authorization: Bearer ' . $token,
            'Content-Type: text/plain',
        ],
        $body
    );
    return ($res && ($d = json_decode($res, true)) && is_array($d)) ? $d : [];
}

// ── Logo SteamGridDB ───────────────────────────────────────────
function igdb_logo(string $name): ?string
{
    $key = SGDB_API_KEY;
    if (!$key || !$name) return null;

    $cacheFile = sys_get_temp_dir() . '/nebula_logo_' . md5($name) . '.json';
    if (file_exists($cacheFile) && filemtime($cacheFile) > time() - 86400 * 7) {
        return json_decode(file_get_contents($cacheFile), true)['url'] ?? null;
    }

    // Étape 1 : trouver l'ID SteamGridDB via autocomplete
    $url    = null;
    $search = _igdb_curl(
        'https://www.steamgriddb.com/api/v2/search/autocomplete/' . urlencode($name),
        'GET',
        ['Authorization: Bearer ' . $key]
    );
    if ($search) {
        $results = json_decode($search, true);
        $sgdbId  = $results['data'][0]['id'] ?? null;

        // Étape 2 : récupérer le logo par ID
        if ($sgdbId) {
            $logos = _igdb_curl(
                'https://www.steamgriddb.com/api/v2/logos/game/' . $sgdbId . '?limit=1',
                'GET',
                ['Authorization: Bearer ' . $key]
            );
            if ($logos) {
                $data = json_decode($logos, true);
                $url  = $data['data'][0]['url'] ?? null;
            }
        }
    }

    file_put_contents($cacheFile, json_encode(['url' => $url]));
    return $url;
}

// ── Helpers URL ────────────────────────────────────────────────
function igdb_cover(mixed $url, string $size = 't_cover_big'): ?string
{
    if (!$url) return null;
    return 'https:' . str_replace('t_thumb', $size, (string)$url);
}

// ── Mapper un résultat IGDB → tableau normalisé ────────────────
function igdb_map(array $g): array
{
    return [
        'id_jeu'      => $g['id'],
        'titre'       => $g['name'] ?? '',
        'genre'       => implode(',', array_column($g['genres'] ?? [], 'name')),
        'description' => $g['summary'] ?? '',
        'image_url'   => igdb_cover($g['cover']['url'] ?? null),
        'developpeur' => $g['involved_companies'][0]['company']['name'] ?? null,
        'date_sortie' => isset($g['first_release_date'])
            ? date('Y-m-d', $g['first_release_date'])
            : null,
    ];
}
