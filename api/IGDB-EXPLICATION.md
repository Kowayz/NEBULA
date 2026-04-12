# IGDB.PHP — Explication détaillée

## Sommaire

| # | Section | Lignes | Rôle |
|---|---------|--------|------|
| 1 | Chargement des clés API | 4-14 | Lire `.env.local` et définir les constantes |
| 2 | `igdb_curl()` | 17-32 | Envoyer une requête HTTP |
| 3 | `igdb_token()` | 35-54 | Obtenir un token Twitch (avec cache) |
| 4 | `igdb_query()` | 57-69 | Envoyer une requête à IGDB |
| 5 | `igdb_get_games()` | 72-90 | Liste des jeux du catalogue |
| 6 | `igdb_get_game()` | 93-143 | Fiche détaillée d'un jeu |
| 7 | `igdb_get_trending_games()` | 146-164 | Jeux tendances pour l'accueil |
| 8 | `igdb_get_streaming_games()` | 167-185 | Jeux inclus dans l'abonnement |
| 9 | `igdb_get_premium_games()` | 188-208 | Jeux achetables à l'unité |

---

## Qu'est-ce que IGDB ?

IGDB (Internet Game Database) est une base de données de jeux vidéo appartenant à Twitch (Amazon).
Elle fournit une API gratuite pour récupérer des infos sur les jeux : nom, cover, screenshots, note, etc.

Pour l'utiliser il faut :
1. Un **Client ID** et un **Client Secret** (obtenus sur le portail développeur Twitch)
2. Un **token OAuth2** qu'on demande à Twitch avec ces identifiants
3. Envoyer des **requêtes POST** à `api.igdb.com/v4/` avec ce token

---

## Section 1 — Chargement des clés API (lignes 4-14)

```php
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
```

### Ligne par ligne :

| Ligne | Code | Explication |
|-------|------|-------------|
| 5 | `dirname(__DIR__) . '/.env.local'` | Chemin vers le fichier `.env.local` situé à la racine du projet. `__DIR__` = dossier `api/`, `dirname()` = remonte d'un cran |
| 6 | `$env = []` | Tableau vide qui va stocker les clés lues |
| 7 | `file($envPath, ...)` | Lit le fichier ligne par ligne et retourne un tableau. Les flags ignorent les lignes vides et les retours à la ligne |
| 8 | `strpos($line, '=')` | Vérifie que la ligne contient un `=` (format `CLE=valeur`) |
| 8 | `$line[0] !== '#'` | Ignore les lignes qui commencent par `#` (commentaires) |
| 9 | `explode('=', $line, 2)` | Coupe la ligne en 2 au premier `=`. Ex: `IGDB_CLIENT_ID=abc123` → `['IGDB_CLIENT_ID', 'abc123']` |
| 9 | `[$k, $v] = ...` | Déstructuration : la clé va dans `$k`, la valeur dans `$v` |
| 10 | `trim()` | Supprime les espaces en début/fin |
| 13-14 | `define(...)` | Crée des constantes PHP accessibles partout dans le fichier |

### Contenu du `.env.local` :

```
IGDB_CLIENT_ID=votre_client_id
IGDB_CLIENT_SECRET=votre_client_secret
```

---

## Section 2 — `igdb_curl()` (lignes 17-32)

```php
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
```

### C'est quoi cURL ?

cURL est une bibliothèque PHP qui permet d'envoyer des requêtes HTTP (comme un navigateur le fait quand on visite un site). On s'en sert pour parler à l'API IGDB.

### Ligne par ligne :

| Ligne | Code | Explication |
|-------|------|-------------|
| 17 | `function igdb_curl(...)` | 4 paramètres : l'URL, la méthode (GET ou POST), les en-têtes HTTP, le corps de la requête |
| 17 | `: string` | La fonction retourne toujours une chaîne de caractères |
| 18 | `curl_init($url)` | Crée une session cURL vers l'URL donnée |
| 19-24 | `curl_setopt_array(...)` | Configure la session en une seule fois |
| 20 | `CURLOPT_RETURNTRANSFER` | Retourne le résultat au lieu de l'afficher directement |
| 21 | `CURLOPT_TIMEOUT` | Abandon si pas de réponse après 15 secondes |
| 22 | `CURLOPT_SSL_VERIFYPEER` | Désactive la vérification du certificat SSL |
| 23 | `CURLOPT_HTTPHEADER` | Ajoute des en-têtes personnalisés (ex: Authorization) |
| 25-28 | `if ($method === 'POST')` | Si c'est un POST, on active le mode POST et on envoie le body |
| 29 | `curl_exec($ch)` | Exécute la requête et stocke la réponse |
| 30 | `curl_close($ch)` | Ferme la session cURL (libère la mémoire) |
| 31 | `return $res` | Retourne la réponse brute (du JSON en général) |

### Qui appelle cette fonction ?

- `igdb_token()` → pour demander un token à Twitch
- `igdb_query()` → pour envoyer des requêtes à IGDB

---

## Section 3 — `igdb_token()` (lignes 35-54)

```php
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
```

### Pourquoi un token ?

IGDB appartient à Twitch. Pour utiliser l'API, il faut s'authentifier avec un token OAuth2. Ce token est une chaîne de caractères qui prouve qu'on a le droit d'accéder à l'API.

### Pourquoi le cache ?

Sans cache, chaque page du site ferait un appel à Twitch juste pour obtenir le token, **avant même** de demander les jeux. Le cache évite ça : on stocke le token dans un fichier temporaire et on le réutilise tant qu'il n'a pas expiré.

### Ligne par ligne :

| Ligne | Code | Explication |
|-------|------|-------------|
| 36 | `sys_get_temp_dir()` | Retourne le dossier temporaire du système (ex: `C:\Windows\Temp`) |
| 36 | `'/nebula_igdb_token.json'` | Nom du fichier de cache |
| 37 | `file_exists(...)` | Vérifie si le fichier de cache existe |
| 38 | `json_decode(file_get_contents(...), true)` | Lit le fichier et le décode en tableau PHP |
| 39 | `$cached['expires'] > time()` | Compare la date d'expiration avec l'heure actuelle. `time()` retourne le timestamp Unix actuel |
| 39 | `return $cached['token']` | Si le token est encore valide, on le retourne directement (pas besoin d'appeler Twitch) |
| 42-45 | Construction de l'URL | On envoie nos identifiants à Twitch dans les paramètres GET |
| 47 | `igdb_curl($url)` | Envoie la requête POST à Twitch |
| 47 | `json_decode(..., true)` | Décode la réponse JSON en tableau PHP |
| 49-52 | `file_put_contents(...)` | Sauvegarde le token et sa date d'expiration dans le fichier de cache |
| 53 | `return $data['access_token']` | Retourne le nouveau token |

### Format de la réponse Twitch :

```json
{
    "access_token": "abc123xyz...",
    "expires_in": 5184000,
    "token_type": "bearer"
}
```

`expires_in` est en secondes (5184000 = ~60 jours).

---

## Section 4 — `igdb_query()` (lignes 57-69)

```php
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
```

### C'est la fonction centrale du fichier.

Toutes les fonctions suivantes (5 à 9) passent par `igdb_query()` pour parler à IGDB.

| Paramètre | Exemple | Rôle |
|-----------|---------|------|
| `$endpoint` | `'games'` | Le type de donnée demandé à IGDB |
| `$body` | `'fields id,name; limit 10;'` | La requête en langage IGDB |

### Les en-têtes HTTP :

| En-tête | Rôle |
|---------|------|
| `Client-ID` | Identifiant de notre application Twitch |
| `Authorization: Bearer ...` | Le token obtenu par `igdb_token()` |
| `Content-Type: text/plain` | Le body est du texte brut (pas du JSON) |

### Le langage de requête IGDB :

C'est un langage simplifié inspiré de SQL :

```
fields id, name, cover.url;     ← les champs qu'on veut récupérer
where cover != null;             ← filtre (jeux qui ont une cover)
sort rating_count desc;          ← tri par popularité décroissante
limit 24;                        ← nombre max de résultats
```

---

## Section 5 — `igdb_get_games()` (lignes 72-90)

**Utilisée par** : `jeux.php` (page catalogue)

```php
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
```

### Explication :

- `strtotime('2021-01-01')` → convertit une date en timestamp Unix (nombre de secondes depuis 1970)
- La requête IGDB demande les jeux sortis après 2021, triés par nombre de notes
- `str_replace('t_thumb', 't_1080p', ...)` → l'API retourne des miniatures (`t_thumb`), on remplace par la version HD (`t_1080p`)
- Le `'https:'` ajouté devant l'URL est nécessaire car IGDB retourne des URLs sans protocole (ex: `//images.igdb.com/...`)

### Retour :

```php
[
    ['id_jeu' => 1942, 'titre' => 'The Witcher 3', 'image_url' => 'https://images.igdb.com/.../t_1080p/...jpg'],
    ['id_jeu' => 732,  'titre' => 'Elden Ring',     'image_url' => '...'],
    ...
]
```

---

## Section 6 — `igdb_get_game()` (lignes 93-143)

**Utilisée par** : `produit.php` (page de détail d'un jeu)

C'est la fonction la plus longue car elle récupère beaucoup de données. Voici ce qu'elle fait étape par étape :

### 1. Requête IGDB (lignes 94-99)

On demande tous les champs nécessaires pour la page produit :

| Champ IGDB | Donnée récupérée |
|------------|------------------|
| `id` | Identifiant du jeu |
| `name` | Nom du jeu |
| `summary` | Description textuelle |
| `cover.url` | Image de couverture |
| `screenshots.url` | Captures d'écran |
| `rating` | Note moyenne (0-100) |
| `first_release_date` | Date de sortie (timestamp) |
| `involved_companies.company.name` | Noms des développeurs/éditeurs |
| `artworks.url` | Illustrations officielles |
| `videos.video_id` | ID de vidéos YouTube |

### 2. Développeurs (lignes 104-107)

```php
$devs = [];
foreach ($g['involved_companies'] ?? [] as $ic) {
    $devs[] = $ic['company']['name'];
}
```

- `?? []` → si `involved_companies` n'existe pas, on utilise un tableau vide (évite une erreur)
- On boucle sur chaque entreprise et on récupère son nom

### 3. Date de sortie (ligne 109)

```php
$dateSortie = isset($g['first_release_date']) ? date('Y-m-d', $g['first_release_date']) : null;
```

- `isset()` → vérifie que le champ existe
- `date('Y-m-d', ...)` → convertit un timestamp Unix en date lisible (ex: `2024-06-15`)
- `? ... : null` → opérateur ternaire : si le champ existe on formate, sinon `null`

### 4. Cover (ligne 110)

```php
$coverUrl = isset($g['cover']['url']) ? 'https:' . str_replace('t_thumb', 't_1080p', $g['cover']['url']) : null;
```

Même logique : on vérifie que la cover existe, et on remplace la miniature par la version HD.

### 5. Hero (lignes 112-117)

```php
$heroUrl = null;
if (isset($g['artworks'][0]['url'])) {
    $heroUrl = 'https:' . str_replace('t_thumb', 't_1080p', $g['artworks'][0]['url']);
} elseif (isset($g['screenshots'][0]['url'])) {
    $heroUrl = 'https:' . str_replace('t_thumb', 't_1080p', $g['screenshots'][0]['url']);
}
```

Pour la grande bannière en haut de la page produit :
- On prend l'artwork officiel en priorité (plus beau)
- Si y'en a pas, on prend le premier screenshot
- `[0]` → premier élément du tableau

### 6. Screenshots (lignes 119-122)

```php
$screenshots = [];
foreach ($g['screenshots'] ?? [] as $shot) {
    $screenshots[] = 'https:' . str_replace('t_thumb', 't_720p', $shot['url']);
}
```

On récupère tous les screenshots en résolution 720p (suffisant pour un carrousel).

### 7. Trailer (lignes 124-128)

```php
$trailerId = null;
foreach ($g['videos'] ?? [] as $vid) {
    $trailerId = $vid['video_id'];
    break;
}
```

- On prend le premier `video_id` trouvé (c'est un ID YouTube)
- `break` → on s'arrête au premier résultat

### 8. Retour (lignes 130-142)

Le tableau retourné contient toutes les données formatées pour `produit.php` :

| Clé | Source | Utilisé pour |
|-----|--------|-------------|
| `id_jeu` | `$g['id']` | Liens et identifiant |
| `titre` | `$g['name']` | Titre de la page |
| `description` | `$g['summary']` | Bloc description |
| `image_url` / `cover_url` | Cover HD | Sidebar + vignette |
| `hero_url` | Artwork ou screenshot | Grande bannière hero |
| `screenshots` | Tableau d'URLs | Carrousel d'images |
| `trailer_id` | ID YouTube | Lecteur vidéo intégré |
| `rating` | Note 0-100 | Affichage de la note |
| `developpeur` | Noms joints par `, ` | Info développeur |
| `date_sortie` | Format `YYYY-MM-DD` | Info date de sortie |

---

## Sections 7, 8, 9 — Fonctions spécialisées

Ces trois fonctions suivent toutes le même schéma :

```
1. Requête IGDB avec des filtres spécifiques
2. Boucle foreach pour formater chaque jeu
3. Retourne un tableau de jeux
```

### 7. `igdb_get_trending_games()` → `index.php`

- Jeux sortis depuis **2025**, triés par popularité
- Affichés sur la page d'accueil (vitrine)

### 8. `igdb_get_streaming_games()` → `boutique.php`

- Jeux avec une **note > 70**
- Considérés comme "inclus" dans l'abonnement streaming
- Limite de 200 jeux

### 9. `igdb_get_premium_games()` → `boutique.php`

- Jeux sortis depuis **2023** avec une **note > 85**
- Proposés à l'achat individuel à **39.99 €** (prix fixe)
- Limite de 50 jeux

---

## Flux d'appel global

```
Page PHP (ex: jeux.php)
   │
   ├── require 'api/igdb.php'     ← charge les clés + définit les fonctions
   │
   └── igdb_get_games(24)          ← appelle la fonction voulue
          │
          └── igdb_query('games', '...')    ← construit la requête IGDB
                 │
                 ├── igdb_token()            ← récupère le token (cache ou Twitch)
                 │      │
                 │      └── igdb_curl(twitch_url)   ← appel HTTP à Twitch
                 │
                 └── igdb_curl(igdb_url)     ← appel HTTP à IGDB avec le token
                        │
                        └── Réponse JSON → json_decode → tableau PHP
```

---

## Fonctions / méthodes PHP utilisées

| Fonction | Rôle |
|----------|------|
| `file()` | Lit un fichier et retourne un tableau de lignes |
| `explode()` | Découpe une chaîne en tableau selon un séparateur |
| `trim()` | Supprime les espaces en début/fin de chaîne |
| `define()` | Crée une constante globale |
| `curl_init()` | Crée une session cURL |
| `curl_setopt_array()` | Configure plusieurs options cURL en une fois |
| `curl_setopt()` | Configure une seule option cURL |
| `curl_exec()` | Exécute la requête cURL |
| `curl_close()` | Ferme la session cURL |
| `json_decode()` | Convertit du JSON en tableau/objet PHP |
| `json_encode()` | Convertit un tableau PHP en JSON |
| `file_exists()` | Vérifie si un fichier existe |
| `file_get_contents()` | Lit tout le contenu d'un fichier en une chaîne |
| `file_put_contents()` | Écrit une chaîne dans un fichier |
| `sys_get_temp_dir()` | Retourne le chemin du dossier temporaire système |
| `time()` | Retourne le timestamp Unix actuel |
| `strtotime()` | Convertit une date texte en timestamp Unix |
| `date()` | Formate un timestamp en chaîne de date |
| `str_replace()` | Remplace une sous-chaîne par une autre |
| `isset()` | Vérifie qu'une variable/clé existe et n'est pas `null` |
| `empty()` | Vérifie qu'une variable est vide |
| `implode()` | Joint les éléments d'un tableau en une chaîne |
| `dirname()` | Retourne le dossier parent d'un chemin |

### Opérateur `??` (null coalescing)

```php
$g['summary'] ?? ''
```

Si `$g['summary']` existe et n'est pas `null`, on le prend. Sinon, on utilise `''` (chaîne vide).

### Opérateur ternaire `? :`

```php
isset($g['first_release_date']) ? date('Y-m-d', $g['first_release_date']) : null
```

Équivalent de :

```php
if (isset($g['first_release_date'])) {
    $dateSortie = date('Y-m-d', $g['first_release_date']);
} else {
    $dateSortie = null;
}
```
