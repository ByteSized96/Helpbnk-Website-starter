<?php
declare(strict_types=1);

function h(string $v): string {
  return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function hb5_json_read(string $path, $default) {
  if (!is_file($path)) return $default;
  $raw = file_get_contents($path);
  if ($raw === false) return $default;
  $data = json_decode($raw, true);
  return is_array($data) ? $data : $default;
}

function hb5_json_write_atomic(string $path, $data): void {
  $tmp = $path . '.tmp';
  $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  if ($json === false) throw new RuntimeException('JSON encode failed.');
  if (file_put_contents($tmp, $json) === false) throw new RuntimeException('Write failed.');
  if (!rename($tmp, $path)) throw new RuntimeException('Atomic rename failed.');
}
function hb5_json_write(string $path, array $data): void {
  hb5_json_write_atomic($path, $data);
}


function hb5_load_site(): array {
  $path = HB5_DATA . '/site.json';
  $default = ['site'=>[], 'seo'=>[], 'pages'=>[], 'page_seo'=>[]];
  return hb5_json_read($path, $default);
}

function hb5_save_site(array $site): void {
  $path = HB5_DATA . '/site.json';
  hb5_json_write_atomic($path, $site);
}

function hb5_csrf_token(): string {
  if (empty($_SESSION['hb5_csrf'])) {
    $_SESSION['hb5_csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['hb5_csrf'];
}

function hb5_csrf_verify(): void {
  $sent = $_POST['csrf'] ?? '';
  $sess = $_SESSION['hb5_csrf'] ?? '';
  if (!is_string($sent) || !is_string($sess) || $sent === '' || !hash_equals($sess, $sent)) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['ok'=>false, 'error'=>'Invalid CSRF token.']);
    exit;
  }
}

function hb5_nav_active(string $href): string {
  $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
  return str_ends_with($path, $href) ? 'text-white bg-gray-900' : 'text-gray-200 hover:bg-gray-800 hover:text-white';
}

function hb5_rate_limit(string $key, int $limit, int $windowSeconds): bool {
  $now = time();
  if (!isset($_SESSION['hb5_rl'])) $_SESSION['hb5_rl'] = [];
  $bucket = $_SESSION['hb5_rl'][$key] ?? ['t'=>$now, 'c'=>0];

  if (($now - (int)$bucket['t']) > $windowSeconds) {
    $bucket = ['t'=>$now, 'c'=>0];
  }
  $bucket['c'] = (int)$bucket['c'] + 1;
  $_SESSION['hb5_rl'][$key] = $bucket;

  return $bucket['c'] <= $limit;
}

function hb5_require_admin(): void {
  if (empty($_SESSION['hb5_admin'])) {
    header('Location: /admin/login.php');
    exit;
  }
}

function hb5_admin_user(): array {
  // Stored in data/admin.json (created on first run)
  $path = HB5_DATA . '/admin.json';
  $default = ['email'=>'admin@example.com', 'password_hash'=>password_hash('ChangeMe123!', PASSWORD_DEFAULT)];
  $data = hb5_json_read($path, $default);
  if (!is_file($path)) hb5_json_write_atomic($path, $data);
  return $data;
}

function hb5_set_admin_password(string $email, string $password): void {
  $path = HB5_DATA . '/admin.json';
  $data = ['email'=>$email, 'password_hash'=>password_hash($password, PASSWORD_DEFAULT)];
  hb5_json_write_atomic($path, $data);
}

function hb5_add_lead(array $lead): void {
  $path = HB5_DATA . '/leads.json';
  $leads = hb5_json_read($path, []);
  $leads[] = $lead;
  hb5_json_write_atomic($path, $leads);
}


function hb5_theme_css(array $theme): string {
  $accent = (string)($theme['accent_hex'] ?? '#4f46e5');
  $mode   = (string)($theme['mode'] ?? 'light');
  $bgHex  = (string)($theme['bg_hex'] ?? '');

  $bgHex = preg_match('/^#[0-9A-Fa-f]{6}$/', $bgHex) ? strtoupper($bgHex) : '';

  $css = ":root{--accent:{$accent};}";

  if ($mode === 'dark') {
    $css .= ":root{--bg:#0b1020;--card:#0f172a;--border:#1f2937;--text:#e5e7eb;--muted:#a1a1aa;--link:var(--accent);--shadow:0 20px 50px rgba(0,0,0,.35);}";
  } elseif ($mode === 'warm') {
    $css .= ":root{--bg:#fbf6ee;--card:#ffffff;--border:color-mix(in srgb, var(--accent) 14%, #e5e7eb);--text:#111827;--muted:#4b5563;--link:var(--accent);--shadow:0 18px 40px rgba(17,24,39,.07);}";
  } else {
    $css .= ":root{--bg:#fcfcfb;--card:#ffffff;--border:color-mix(in srgb, var(--accent) 12%, #e5e7eb);--text:#111827;--muted:#4b5563;--link:var(--accent);--shadow:0 18px 40px rgba(17,24,39,.07);}";
  }

  if ($bgHex !== '') {
    if ($mode === 'dark') {
      $css .= ":root{--bg:{$bgHex};--card:color-mix(in srgb, #0b1020 55%, var(--bg));--border:color-mix(in srgb, var(--accent) 14%, #1f2937);}";
    } else {
      $css .= ":root{--bg:{$bgHex};--card:color-mix(in srgb, #ffffff 88%, var(--bg));--border:color-mix(in srgb, var(--accent) 14%, #e5e7eb);}";
    }
  }

  $css .= "
"
    . "body{background:var(--bg);color:var(--text);}
"
    . ".paper{background:var(--bg);}
"
    . ".ink{color:var(--text);}
"
    . ".muted{color:var(--muted);}
"
    . ".card{border:1px solid var(--border);background:var(--card);box-shadow:var(--shadow);}
"
    . "a{color:var(--link);}a:hover{opacity:.85;}
"

    // Compatibility overrides for older Tailwind-based templates
    . ".bg-white{background:var(--card)!important;}
"
    . ".bg-white\/5,.bg-white\/10,.bg-white\/15{background:color-mix(in srgb, var(--card) 88%, transparent)!important;}
"
    . ".bg-gray-50{background:var(--bg)!important;}
"
    . ".bg-gray-950,.bg-gray-950\/30,.bg-gray-950\/40,.bg-gray-950\/50{background:color-mix(in srgb, var(--bg) 85%, var(--card))!important;}
"
    . ".bg-black\/20{background:color-mix(in srgb, var(--bg) 80%, #000 20%)!important;}
"
    . ".border-gray-200,.border-white\/10,.border-white\/15{border-color:var(--border)!important;}
"
    . ".text-gray-200,.text-gray-300,.text-gray-400,.text-gray-500,.text-gray-600{color:var(--muted)!important;}
"
    . ".text-gray-900{color:var(--text)!important;}
"
    . ".shadow-black\/20{box-shadow:var(--shadow)!important;}
"
    . ".from-white\/10{--tw-gradient-from:color-mix(in srgb, var(--card) 90%, transparent)!important;}
"
    . ".to-white\/5{--tw-gradient-to:color-mix(in srgb, var(--card) 84%, transparent)!important;}
"
    . ".bg-accent{background:var(--accent)!important;}
"
    . ".bg-accent\/20{background:color-mix(in srgb, var(--accent) 20%, transparent)!important;}
"
    . ".bg-accent\/15{background:color-mix(in srgb, var(--accent) 15%, transparent)!important;}
"
    . ".bg-accent\/10{background:color-mix(in srgb, var(--accent) 10%, transparent)!important;}
"
  ;

  return $css;
}
?>