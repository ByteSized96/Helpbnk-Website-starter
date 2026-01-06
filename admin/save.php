<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
hb5_require_admin();

header('Content-Type: application/json');

$raw = file_get_contents('php://input') ?: '';
$payload = json_decode($raw, true);
if (!is_array($payload)) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'Invalid JSON.']);
  exit;
}

// CSRF (from JSON)
$_POST['csrf'] = $payload['csrf'] ?? '';
hb5_csrf_verify();

$action = (string)($payload['action'] ?? '');

try {
  $site = hb5_load_site();

if ($action === 'pages') {
  $site['pages']['home']['hero_title'] = (string)($payload['home']['hero_title'] ?? '');
  $site['pages']['home']['hero_subtitle'] = (string)($payload['home']['hero_subtitle'] ?? '');
  $site['pages']['home']['cta_primary_text'] = (string)($payload['home']['cta_primary_text'] ?? '');
  $site['pages']['home']['cta_primary_href'] = (string)($payload['home']['cta_primary_href'] ?? '');
  $site['pages']['home']['cta_secondary_text'] = (string)($payload['home']['cta_secondary_text'] ?? '');
  $site['pages']['home']['cta_secondary_href'] = (string)($payload['home']['cta_secondary_href'] ?? '');
  $site['pages']['home']['feature_blocks'] = is_array($payload['home']['feature_blocks'] ?? null) ? $payload['home']['feature_blocks'] : [];
  $site['pages']['home']['sections'] = is_array($payload['home']['sections'] ?? null) ? $payload['home']['sections'] : [];

  // ✅ Save Home slideshow (NEW)
  $ss = $payload['home']['slideshow'] ?? [];
  if (!is_array($ss)) $ss = [];

  $imgs = $ss['images'] ?? [];
  if (!is_array($imgs)) $imgs = [];
  $imgs = array_values(array_filter(array_map(fn($v) => trim((string)$v), $imgs)));

  $interval = (int)($ss['interval'] ?? 4);
  if ($interval < 2) $interval = 2;

  $height = (int)($ss['height'] ?? 360);
  if ($height < 200) $height = 200;

  $site['pages']['home']['slideshow'] = [
    'enabled'  => !empty($ss['enabled']),
    'interval' => $interval,
    'height'   => $height,
    'images'   => $imgs,
  ];

  $site['pages']['about']['headline'] = (string)($payload['about']['headline'] ?? '');
  $site['pages']['about']['body'] = (string)($payload['about']['body'] ?? '');
  $site['pages']['services']['headline'] = (string)($payload['services']['headline'] ?? ($site['pages']['services']['headline'] ?? ''));
  $site['pages']['gallery']['headline'] = (string)($payload['gallery']['headline'] ?? ($site['pages']['gallery']['headline'] ?? ''));
  $site['pages']['contact']['headline'] = (string)($payload['contact']['headline'] ?? '');
  $site['pages']['contact']['intro'] = (string)($payload['contact']['intro'] ?? '');
  $site['pages']['contact']['form_success_message'] = (string)($payload['contact']['form_success_message'] ?? ($site['pages']['contact']['form_success_message'] ?? ''));

  hb5_save_site($site);
  echo json_encode(['ok'=>true]);
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
    echo json_encode(['ok'=>true]);
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
        'image' => $img,
        'caption' => trim((string)($it['caption'] ?? '')),
      ];
    }
    $site['pages']['gallery']['items'] = $clean;
    hb5_save_site($site);
    echo json_encode(['ok'=>true]);
    exit;
  }

  if ($action === 'settings') {
    $site['site']['brand_name'] = (string)($payload['brand_name'] ?? '');
    $site['site']['tagline'] = (string)($payload['tagline'] ?? '');
    $site['site']['phone'] = (string)($payload['phone'] ?? '');
    $site['site']['email'] = (string)($payload['email'] ?? '');
    $site['site']['address'] = (string)($payload['address'] ?? '');
    $site['site']['google_maps_embed_url'] = (string)($payload['google_maps_embed_url'] ?? '');

    $hex = strtoupper(trim((string)($payload['accent_hex'] ?? '#4f46e5')));
    if (!preg_match('/^#[0-9A-F]{6}$/', $hex)) $hex = '#4f46e5';
    $site['site']['theme']['accent_hex'] = $hex;
    $site['site']['theme']['palette'] = (string)($payload['palette'] ?? 'custom');
    $site['site']['theme']['mode'] = (string)($payload['mode'] ?? 'light');
    $site['site']['theme']['bg_hex'] = (string)($payload['bg_hex'] ?? '');

    $socials = $payload['socials'] ?? [];
    if (!is_array($socials)) $socials = [];
    foreach (['instagram','facebook','tiktok','linkedin'] as $k) {
      $site['site']['socials'][$k] = (string)($socials[$k] ?? '');
    }

    $site['site']['footer_credit_enabled'] = !empty($payload['credit_enabled']);
    $site['site']['footer_credit_text'] = (string)($payload['credit_text'] ?? '');

    hb5_save_site($site);
    echo json_encode(['ok'=>true]);
    exit;
  }

  if ($action === 'seo') {
    $site['seo']['default_title_suffix'] = (string)($payload['default_title_suffix'] ?? '');
    $site['seo']['default_description'] = (string)($payload['default_description'] ?? '');
    $pageSeo = $payload['page_seo'] ?? [];
    if (!is_array($pageSeo)) $pageSeo = [];
    foreach ($pageSeo as $k=>$v) {
      if (!is_array($v)) continue;
      $site['page_seo'][$k]['title'] = (string)($v['title'] ?? '');
      $site['page_seo'][$k]['description'] = (string)($v['description'] ?? '');
    }
    hb5_save_site($site);
    echo json_encode(['ok'=>true]);
    exit;
  }

  if ($action === 'clear_leads') {
    hb5_json_write_atomic(HB5_DATA . '/leads.json', []);
    echo json_encode(['ok'=>true]);
    exit;
  }

  if ($action === 'admin_login') {
    $email = trim((string)($payload['email'] ?? ''));
    $password = (string)($payload['password'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new RuntimeException('Invalid email.');
    }
    if (strlen($password) < 10) {
      throw new RuntimeException('Password too short.');
    }
    hb5_set_admin_password($email, $password);
    echo json_encode(['ok'=>true]);
    exit;
  }

if ($action === 'delete_upload') {
  $filename = (string)($payload['filename'] ?? '');
  if ($filename === '' || preg_match('/\.(php|phtml|phar)$/i', $filename)) {
    throw new RuntimeException('Invalid filename.');
  }
  $filename = basename($filename);
  if ($filename === '.htaccess') throw new RuntimeException('Not allowed.');
  $path = HB5_UPLOADS . '/' . $filename;
  if (!is_file($path)) throw new RuntimeException('File not found.');
  if (!unlink($path)) throw new RuntimeException('Delete failed.');
  echo json_encode(['ok'=>true]);
  exit;
}

if ($action === 'reset_demo') {
  $demoPath = HB5_DATA . '/site.demo.json';
  if (!is_file($demoPath)) throw new RuntimeException('Demo file missing.');
  $demo = hb5_json_read($demoPath, []);
  if (!$demo) throw new RuntimeException('Demo content invalid.');
  hb5_save_site($demo);
  echo json_encode(['ok'=>true]);
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
    if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = date('Y-m-d');

    $tags = $p['tags'] ?? [];
    if (is_string($tags)) $tags = array_filter(array_map('trim', explode(',', $tags)));
    if (!is_array($tags)) $tags = [];
    $tags = array_values(array_filter(array_map(fn($t)=>trim((string)$t), $tags)));

    $clean[] = [
      'id' => (string)($p['id'] ?? ('p_' . bin2hex(random_bytes(4)))),
      'title' => $title,
      'slug' => $slug,
      'date' => $date ?: date('Y-m-d'),
      'excerpt' => (string)($p['excerpt'] ?? ''),
      'content' => (string)($p['content'] ?? ''),
      'cover' => (string)($p['cover'] ?? ''),
      'tags' => $tags,
      'published' => !empty($p['published']),
    ];
  }

  hb5_save_blog(['posts' => $clean]);
  echo json_encode(['ok'=>true]);
  exit;
}

  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'Unknown action.']);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
?>