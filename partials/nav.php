<?php
  require_once __DIR__ . '/../includes/bootstrap.php';
  $site = hb5_load_site();
  $s = $site['site'] ?? [];
  $theme = $s['theme'] ?? [];
  $accent = $theme['accent_hex'] ?? '#4f46e5';
  $name = $s['name'] ?? 'Website';
?>
<header class="border-b border-[color:var(--border)] bg-[color:var(--card)]">
  <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4">
    <a href="/" class="flex items-center gap-3">
      <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[color:var(--border)] bg-[color:var(--bg)] font-semibold" style="color: <?= h($accent) ?>;">★</span>
      <div class="leading-tight">
        <div class="text-sm font-semibold tracking-wide text-[color:var(--muted)]">Company</div>
        <div class="text-lg font-semibold tracking-tight text-[color:var(--text)]"><?= h($name) ?></div>
      </div>
    </a>

    <nav class="hidden items-center gap-5 text-sm font-semibold text-[color:var(--text)] md:flex">
      <a class="hover:text-[color:var(--text)]" href="/">Home</a>
      <a class="hover:text-[color:var(--text)]" href="/about.php">About</a>
      <a class="hover:text-[color:var(--text)]" href="/services.php">Services</a>
      <a class="hover:text-[color:var(--text)]" href="/gallery.php">Gallery</a>
      <a class="hover:text-[color:var(--text)]" href="/blog.php">Blog</a>
      <a class="hover:text-[color:var(--text)]" href="/contact.php">Contact</a>
    </nav>

    <button class="md:hidden rounded-xl border border-[color:var(--border)] bg-[color:var(--card)] px-3 py-2 text-sm font-semibold"
      onclick="document.getElementById('mnav').classList.toggle('hidden')">
      Menu
    </button>
  </div>

  <div id="mnav" class="hidden border-t border-[color:var(--border)] bg-[color:var(--card)] md:hidden">
    <div class="mx-auto max-w-5xl px-4 py-4 grid gap-2 text-sm font-semibold text-[color:var(--text)]">
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/">Home</a>
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/about.php">About</a>
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/services.php">Services</a>
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/gallery.php">Gallery</a>
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/blog.php">Blog</a>
      <a class="rounded-xl px-3 py-2 hover:bg-[color:var(--bg)]" href="/contact.php">Contact</a>
    </div>
  </div>
</header>
