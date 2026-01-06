<?php
declare(strict_types=1);

/**
 * includes/bootstrap.php
 * - Safe session cookie params
 * - JSON content loading helpers
 */

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
  || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

$host = strtolower($_SERVER['HTTP_HOST'] ?? '');
$isLocalHost = (bool)preg_match('/^(localhost|127\.0\.0\.1|::1)(:\d+)?$/', $host);
$sessionDomain = $isLocalHost ? '' : '.' . preg_replace('/^www\./', '', $host);

session_name('HB5SESSID');
if (PHP_VERSION_ID >= 70300) {
  session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $sessionDomain,
    'secure' => $https,
    'httponly' => true,
    'samesite' => 'Lax',
  ]);
} else {
  ini_set('session.cookie_lifetime', '0');
  ini_set('session.cookie_path', '/');
  if ($sessionDomain !== '') ini_set('session.cookie_domain', $sessionDomain);
  ini_set('session.cookie_secure', $https ? '1' : '0');
  ini_set('session.cookie_httponly', '1');
  ini_set('session.cookie_samesite', 'Lax');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once __DIR__ . '/helpers.php';

define('HB5_BASE', dirname(__DIR__));
define('HB5_DATA', HB5_BASE . '/data');
define('HB5_UPLOADS', HB5_BASE . '/uploads');

define('HB5_BLOG', HB5_DATA . '/blog.json');

function hb5_load_blog(): array {
  return hb5_json_read(HB5_BLOG, ['posts' => []]);
}

function hb5_save_blog(array $blog): void {
  hb5_json_write(HB5_BLOG, $blog);
}


$site = hb5_load_site();
?>