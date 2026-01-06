<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
hb5_require_admin();

$map = [
  HB5_DATA . '/site.json'  => 'data/site.json',
  HB5_DATA . '/leads.json' => 'data/leads.json',
  HB5_DATA . '/admin.json' => 'data/admin.json',
];

$tmp = tempnam(sys_get_temp_dir(), 'hb5bk_');
$zipPath = $tmp . '.zip';

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
  http_response_code(500);
  exit('Could not create zip.');
}

foreach ($map as $src=>$dest) {
  if (is_file($src)) $zip->addFile($src, $dest);
}
$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="website-backup-' . date('Y-m-d') . '.zip"');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);
@unlink($zipPath);
@unlink($tmp);
exit;
?>