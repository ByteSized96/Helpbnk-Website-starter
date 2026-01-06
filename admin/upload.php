<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
hb5_require_admin();

header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'Method not allowed']);
  exit;
}

hb5_csrf_verify();

$kind = (string)($_POST['kind'] ?? 'asset');
$file = $_FILES['file'] ?? null;

if (!$file || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'No file uploaded.']);
  exit;
}

$maxBytes = 5 * 1024 * 1024;
if ((int)($file['size'] ?? 0) > $maxBytes) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'File too large (max 5MB).']);
  exit;
}

$tmp  = (string)$file['tmp_name'];
$orig = (string)($file['name'] ?? 'upload');

$ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
$allowed = ['png','jpg','jpeg','webp','gif','svg','ico'];

if (!in_array($ext, $allowed, true)) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'Invalid file type.']);
  exit;
}

// Basic image validation (skip for svg/ico)
if (!in_array($ext, ['svg','ico'], true)) {
  $info = @getimagesize($tmp);
  if (!$info) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Invalid image.']);
    exit;
  }
}

// ---------- Choose subfolder based on kind ----------
$subdir = '';
switch ($kind) {
  case 'logo':     $subdir = 'brand'; break;
  case 'og':       $subdir = 'brand'; break;
  case 'favicon':  $subdir = 'brand'; break;

  case 'gallery':  $subdir = 'gallery'; break;

  // NEW: slideshow uploads
  case 'slideshow': $subdir = 'slides'; break;

  default:         $subdir = 'misc'; break;
}

// Ensure upload dir exists
$targetDir = rtrim(HB5_UPLOADS, '/');
if ($subdir !== '') {
  $targetDir .= '/' . $subdir;
}

if (!is_dir($targetDir)) {
  if (!mkdir($targetDir, 0755, true)) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Upload folder not writable.']);
    exit;
  }
}

$slug = preg_replace('/[^a-zA-Z0-9\-]+/', '-', pathinfo($orig, PATHINFO_FILENAME));
$slug = trim($slug, '-');
if ($slug === '') $slug = 'file';

$name = $slug . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
$dest = $targetDir . '/' . $name;

if (!move_uploaded_file($tmp, $dest)) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'Upload failed.']);
  exit;
}

// Public path
$path = '/uploads' . ($subdir !== '' ? '/' . $subdir : '') . '/' . $name;

// Persist asset paths if needed
$site = hb5_load_site();
$site['site']['assets'] = $site['site']['assets'] ?? [];

if ($kind === 'logo')    $site['site']['assets']['logo_path'] = $path;
if ($kind === 'og')      $site['site']['assets']['og_image_path'] = $path;
if ($kind === 'favicon') $site['site']['assets']['favicon_path'] = $path;

if (in_array($kind, ['logo','og','favicon'], true)) {
  hb5_save_site($site);
}

echo json_encode(['ok'=>true,'path'=>$path]);
?>