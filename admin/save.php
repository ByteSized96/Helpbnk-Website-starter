<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
hb5_require_admin();

header('Content-Type: application/json');

$raw = file_get_contents('php://input') ?: '';
$payload = json_decode($raw, true);

if (!is_array($payload)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Invalid JSON.']);
  exit;
}

// CSRF (from JSON)
$_POST['csrf'] = $payload['csrf'] ?? '';
hb5_csrf_verify();

$action = (string)($payload['action'] ?? '');

function hb5_hex_or_empty(mixed $v): string {
  $v = strtoupper(trim((string)$v));
  if ($v === '') return '';
  if (!str_starts_with($v, '#')) $v = '#' . $v;
  return preg_match('/^#[0-9A-F]{6}$/', $v) ? $v : '';
}

function hb5_clamp_int(mixed $v, int $min, int $max, int $fallback): int {
  $n = (int)($v ?? $fallback);
  if ($n < $min) $n = $min;
  if ($n > $max) $n = $max;
  return $n;
}

try {
  $site = hb5_load_site();

  if ($action === 'pages') {
    $site['pages']['home']['hero_title']        = (string)($payload['home']['hero_title'] ?? '');
    $site['pages']['home']['hero_subtitle']     = (string)($payload['home']['hero_subtitle'] ?? '');
    $site['pages']['home']['cta_primary_text']  = (string)($payload['home']['cta_primary_text'] ?? '');
    $site['pages']['home']['cta_primary_href']  = (string)($payload['home']['cta_primary_href'] ?? '');
    $site['pages']['home']['cta_secondary_text']= (string)($payload['home']['cta_secondary_text'] ?? '');
    $site['pages']['home']['cta_secondary_href']= (string)($payload['home']['cta_secondary_href'] ?? '');

    $site['pages']['home']['feature_blocks'] = is_array($payload['home']['feature_blocks'] ?? null)
      ? $payload['home']['feature_blocks']
      : [];

    $site['pages']['home']['sections'] = is_array($payload['home']['sections'] ?? null)
      ? $payload['home']['sections']
      : [];

    // Slideshow
    $ss = $payload['home']['slideshow'] ?? [];
    if (!is_array($ss)) $ss = [];

    $imgs = $ss['images'] ?? [];
    if (!is_array($imgs)) $imgs = [];
    $imgs = array_values(array_filter(array_map(static fn($v) => trim((string)$v), $imgs)));

    $interval = hb5_clamp_int($ss['interval'] ?? 4, 2, 20, 4);
    $height   = hb5_clamp_int($ss['height'] ?? 360, 200, 1200, 360);

    $site['pages']['home']['slideshow'] = [
      'enabled'  => !empty($ss['enabled']),
      'interval' => $interval,
      'height'   => $height,
      'images'   => $imgs,
    ];

    $site['pages']['about']['headline'] = (string)($payload['about']['headline'] ?? '');
    $site['pages']['about']['body']     = (string)($payload['about']['body'] ?? '');

    $site['pages']['services']['headline'] = (string)($payload['services']['headline'] ?? ($site['pages']['services']['headline'] ?? ''));
    $site['pages']['gallery']['headline']  = (string)($payload['gallery']['headline'] ?? ($site['pages']['gallery']['headline'] ?? ''));

    $site['pages']['contact']['headline']            = (string)($payload['contact']['headline'] ?? '');
    $site['pages']['contact']['intro']               = (string)($payload['contact']['intro'] ?? '');
    $site['pages']['contact']['form_success_message']= (string)($payload['contact']['form_success_message'] ?? ($site['pages']['contact']['form_success_message'] ?? ''));

    hb5_save_site($site);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'services') {
    $items = $payload['items'] ?? [];
    if (!is_array($items)) $items = [];

    $clean = [];
    foreach ($items as $it) {
      if (!is_array($it)) continue;
      $title = trim((string)($it['title'] ?? ''));
      if ($title === '') continue;

      $clean[] = [
        'title' => $title,
        'price' => trim((string)($it['price'] ?? '')),
        'text'  => trim((string)($it['text'] ?? '')),
      ];
    }

    $site['pages']['services']['items'] = $clean;
    hb5_save_site($site);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'gallery') {
    $items = $payload['items'] ?? [];
    if (!is_array($items)) $items = [];

    $clean = [];
    foreach ($items as $it) {
      if (!is_array($it)) continue;
      $img = trim((string)($it['image'] ?? ''));
      if ($img === '') continue;

      $clean[] = [
        'image'   => $img,
        'caption' => trim((string)($it['caption'] ?? '')),
      ];
    }

    $site['pages']['gallery']['items'] = $clean;
    hb5_save_site($site);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'settings') {
    $site['site']['brand_name']            = (string)($payload['brand_name'] ?? '');
    $site['site']['tagline']               = (string)($payload['tagline'] ?? '');
    $site['site']['phone']                 = (string)($payload['phone'] ?? '');
    $site['site']['email']                 = (string)($payload['email'] ?? '');
    $site['site']['address']               = (string)($payload['address'] ?? '');
    $site['site']['google_maps_embed_url'] = (string)($payload['google_maps_embed_url'] ?? '');

    $accent = strtoupper(trim((string)($payload['accent_hex'] ?? '#4f46e5')));
    if (!str_starts_with($accent, '#')) $accent = '#' . $accent;
    if (!preg_match('/^#[0-9A-F]{6}$/', $accent)) $accent = '#4F46E5';
    $site['site']['theme']['accent_hex'] = $accent;

    $allowedPalettes = ['custom','indigo','emerald','rose','amber','sky','violet','teal'];
    $allowedModes    = ['light','warm','dark'];

    $pal = (string)($payload['palette'] ?? 'custom');
    $mode= (string)($payload['mode'] ?? 'light');
    if (!in_array($pal, $allowedPalettes, true)) $pal = 'custom';
    if (!in_array($mode, $allowedModes, true)) $mode = 'light';

    $site['site']['theme']['palette'] = $pal;
    $site['site']['theme']['mode']    = $mode;

    $bg = hb5_hex_or_empty($payload['bg_hex'] ?? '');
    $site['site']['theme']['bg_hex']  = $bg;

    $socials = $payload['socials'] ?? [];
    if (!is_array($socials)) $socials = [];
    foreach (['instagram','facebook','tiktok','linkedin'] as $k) {
      $site['site']['socials'][$k] = (string)($socials[$k] ?? '');
    }

    $site['site']['footer_credit_enabled'] = !empty($payload['credit_enabled']);
    $site['site']['footer_credit_text']    = (string)($payload['credit_text'] ?? '');

    hb5_save_site($site);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'seo') {
    $site['seo']['default_title_suffix'] = (string)($payload['default_title_suffix'] ?? '');
    $site['seo']['default_description']  = (string)($payload['default_description'] ?? '');

    $pageSeo = $payload['page_seo'] ?? [];
    if (!is_array($pageSeo)) $pageSeo = [];

    foreach ($pageSeo as $k => $v) {
      if (!is_array($v)) continue;
      $site['page_seo'][$k]['title']       = (string)($v['title'] ?? '');
      $site['page_seo'][$k]['description'] = (string)($v['description'] ?? '');
    }

    hb5_save_site($site);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'clear_leads') {
    hb5_json_write_atomic(HB5_DATA . '/leads.json', []);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'admin_login') {
    $email    = trim((string)($payload['email'] ?? ''));
    $password = (string)($payload['password'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new RuntimeException('Invalid email.');
    }
    if (strlen($password) < 10) {
      throw new RuntimeException('Password too short.');
    }

    hb5_set_admin_password($email, $password);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'delete_upload') {
    $filename = (string)($payload['filename'] ?? '');
    $filename = basename($filename);

    if ($filename === '' || $filename === '.htaccess') {
      throw new RuntimeException('Invalid filename.');
    }
    if (preg_match('/\.(php|phtml|phar)$/i', $filename)) {
      throw new RuntimeException('Invalid filename.');
    }

    $path = HB5_UPLOADS . '/' . $filename;
    if (!is_file($path)) throw new RuntimeException('File not found.');
    if (!unlink($path)) throw new RuntimeException('Delete failed.');

    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'reset_demo') {
    $demoPath = HB5_DATA . '/site.demo.json';
    if (!is_file($demoPath)) throw new RuntimeException('Demo file missing.');

    $demo = hb5_json_read($demoPath, []);
    if (!$demo) throw new RuntimeException('Demo content invalid.');

    hb5_save_site($demo);
    echo json_encode(['ok' => true]);
    exit;
  }

  if ($action === 'blog') {
    $posts = $payload['posts'] ?? [];
    if (!is_array($posts)) throw new RuntimeException('Invalid posts payload.');

    $clean = [];
    $slugs = [];

    foreach ($posts as $p) {
      if (!is_array($p)) continue;
      $title = trim((string)($p['title'] ?? ''));
      if ($title === '') continue;

      $slug = strtolower(trim((string)($p['slug'] ?? '')));
      $slug = preg_replace('/[^a-z0-9\-]+/', '-', $slug);
      $slug = trim($slug, '-');

      if ($slug === '') throw new RuntimeException('Post slug missing.');
      if (isset($slugs[$slug])) throw new RuntimeException('Duplicate slug: ' . $slug);
      $slugs[$slug] = true;

      $date = trim((string)($p['date'] ?? ''));
      if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = date('Y-m-d');
      }

      $tags = $p['tags'] ?? [];
      if (is_string($tags)) $tags = array_filter(array_map('trim', explode(',', $tags)));
      if (!is_array($tags)) $tags = [];
      $tags = array_values(array_filter(array_map(static fn($t) => trim((string)$t), $tags)));

      $id = (string)($p['id'] ?? '');
      if ($id === '') $id = 'p_' . bin2hex(random_bytes(4));
      $id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $id);

      $clean[] = [
        'id'        => $id,
        'title'     => $title,
        'slug'      => $slug,
        'date'      => $date ?: date('Y-m-d'),
        'excerpt'   => (string)($p['excerpt'] ?? ''),
        'content'   => (string)($p['content'] ?? ''),
        'cover'     => (string)($p['cover'] ?? ''),
        'tags'      => $tags,
        'published' => !empty($p['published']),
      ];
    }

    hb5_save_blog(['posts' => $clean]);
    echo json_encode(['ok' => true]);
    exit;
  }

  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Unknown action.']);
  exit;

} catch (RuntimeException $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
  exit;

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Server error.']);
  // optional: error_log($e);
  exit;
}
?>
