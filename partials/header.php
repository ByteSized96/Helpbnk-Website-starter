<?php
/** @var array $site */
$brand  = $site['site']['brand_name'] ?? 'Website';
$assets = $site['site']['assets'] ?? [];
$logo   = $assets['logo_path'] ?? '';

$pageKey = $pageKey ?? 'home';
$seo = $site['page_seo'][$pageKey] ?? [];
$globalSeo = $site['seo'] ?? [];

$suffix = (string)($globalSeo['default_title_suffix'] ?? '');
$baseTitle = (string)($seo['title'] ?? ucfirst($pageKey));
$title = trim($baseTitle . ' ' . $suffix);

$desc  = (string)($seo['description'] ?? ($globalSeo['default_description'] ?? ''));
$ogTitle = (string)($globalSeo['default_og_title'] ?? $brand);
$ogDesc  = (string)($globalSeo['default_og_description'] ?? ($desc ?: ''));

$ogImg   = (string)($assets['og_image_path'] ?? '');
$favicon = (string)($assets['favicon_path'] ?? '');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$host = $_SERVER['HTTP_HOST'] ?? '';
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
  || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$base = ($https ? 'https://' : 'http://') . $host;
$canonical = rtrim($base, '/') . $path;

function nav_active(string $key, string $pageKey): string {
  return $key === $pageKey
    ? 'bg-[color:var(--bg)] text-[color:var(--text)] border border-[color:var(--border)]'
    : 'text-[color:var(--muted)] hover:text-[color:var(--text)] hover:bg-[color:var(--bg)]/60';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= h($title) ?></title>

  <?php if ($desc): ?>
    <meta name="description" content="<?= h($desc) ?>">
  <?php endif; ?>

  <link rel="canonical" href="<?= h($canonical) ?>">

  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= h($canonical) ?>">
  <meta property="og:title" content="<?= h($ogTitle ?: $title) ?>">
  <meta property="og:description" content="<?= h($ogDesc ?: $desc) ?>">
  <?php if ($ogImg): ?><meta property="og:image" content="<?= h($ogImg) ?>"><?php endif; ?>

  <meta name="twitter:card" content="<?= $ogImg ? 'summary_large_image' : 'summary' ?>">
  <meta name="twitter:title" content="<?= h($ogTitle ?: $title) ?>">
  <meta name="twitter:description" content="<?= h($ogDesc ?: $desc) ?>">
  <?php if ($ogImg): ?><meta name="twitter:image" content="<?= h($ogImg) ?>"><?php endif; ?>

  <?php if ($favicon): ?><link rel="icon" href="<?= h($favicon) ?>"><?php endif; ?>

  <script src="https://cdn.tailwindcss.com"></script>

  <?php $theme = $site['site']['theme'] ?? []; ?>
  <style>
    <?= hb5_theme_css($theme) ?>
    .container { max-width: 72rem; }

    /* nicer keyboard focus without changing your design */
    :focus-visible { outline: 2px solid color-mix(in srgb, var(--accent) 60%, white); outline-offset: 2px; }
  </style>
</head>

<body class="paper ink">
<header class="sticky top-0 z-50 border-b border-[color:var(--border)] bg-[color:var(--card)]/92 backdrop-blur">
  <div class="mx-auto container px-4 py-3 flex items-center justify-between gap-4">
    <a href="/index.php" class="group flex items-center gap-3">
      <?php if ($logo): ?>
        <img src="<?= h($logo) ?>" alt="<?= h($brand) ?>" class="h-9 w-auto rounded-xl border border-[color:var(--border)] bg-white/5" />
      <?php else: ?>
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[color:var(--border)] bg-[color:var(--bg)] font-semibold">★</span>
      <?php endif; ?>

      <div class="leading-tight">
        <div class="text-[11px] font-semibold tracking-wide text-[color:var(--muted)] group-hover:text-[color:var(--text)]/80">Company</div>
        <div class="text-lg font-semibold tracking-tight"><?= h($brand) ?></div>
      </div>
    </a>

    <!-- Desktop nav -->
    <nav class="hidden md:flex items-center gap-2 text-sm font-semibold">
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('home',$pageKey) ?>" href="/index.php">Home</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('about',$pageKey) ?>" href="/about.php">About</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('services',$pageKey) ?>" href="/services.php">Services</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('gallery',$pageKey) ?>" href="/gallery.php">Gallery</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('blog',$pageKey) ?>" href="/blog.php">Blog</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('contact',$pageKey) ?>" href="/contact.php">Contact</a>
    </nav>

    <!-- Mobile menu button -->
    <button
      id="menuBtn"
      class="md:hidden rounded-xl border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-sm font-semibold hover:opacity-90"
      aria-controls="mnav"
      aria-expanded="false"
      type="button"
    >
      Menu
    </button>
  </div>

  <!-- Mobile nav -->
  <div id="mnav" class="hidden md:hidden border-t border-[color:var(--border)] bg-[color:var(--card)]">
    <div class="mx-auto container px-4 py-4 grid gap-2 text-sm font-semibold">
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('home',$pageKey) ?>" href="/index.php">Home</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('about',$pageKey) ?>" href="/about.php">About</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('services',$pageKey) ?>" href="/services.php">Services</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('gallery',$pageKey) ?>" href="/gallery.php">Gallery</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('blog',$pageKey) ?>" href="/blog.php">Blog</a>
      <a class="rounded-xl px-3 py-2 transition <?= nav_active('contact',$pageKey) ?>" href="/contact.php">Contact</a>
    </div>
  </div>
</header>

<script>
(() => {
  const btn = document.getElementById('menuBtn');
  const nav = document.getElementById('mnav');
  if (!btn || !nav) return;

  function close() {
    nav.classList.add('hidden');
    btn.setAttribute('aria-expanded', 'false');
  }

  btn.addEventListener('click', () => {
    const isOpen = !nav.classList.contains('hidden');
    if (isOpen) close();
    else {
      nav.classList.remove('hidden');
      btn.setAttribute('aria-expanded', 'true');
    }
  });

  // Close when clicking a link
  nav.querySelectorAll('a').forEach(a => a.addEventListener('click', close));

  // Click outside to close
  document.addEventListener('click', (e) => {
    if (nav.classList.contains('hidden')) return;
    if (btn.contains(e.target) || nav.contains(e.target)) return;
    close();
  });

  // ESC to close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
})();
</script>

<main class="mx-auto container px-4 py-10">
