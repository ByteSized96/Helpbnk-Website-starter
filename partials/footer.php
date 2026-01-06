<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$site = hb5_load_site();
$s = $site['site'] ?? [];

$name    = $s['brand_name'] ?? $s['name'] ?? 'Website';
$tagline = $s['tagline'] ?? '';
$socials = $s['socials'] ?? [];
?>
</main>

<footer class="border-t border-[color:var(--border)] bg-[color:var(--bg)]">
  <div class="mx-auto max-w-6xl px-4 py-12">

    <!-- Top row -->
    <div class="grid gap-8 md:grid-cols-3">

      <!-- Brand -->
      <div>
        <div class="text-base font-semibold text-[color:var(--text)]">
          <?= h($name) ?>
        </div>
        <?php if ($tagline): ?>
          <p class="mt-2 max-w-sm text-sm text-[color:var(--muted)]">
            <?= h($tagline) ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Navigation -->
      <div>
        <div class="text-sm font-medium text-[color:var(--text)] mb-3">
          Explore
        </div>
        <ul class="space-y-2 text-sm">
          <li><a href="/about.php" class="text-[color:var(--muted)] hover:text-[color:var(--text)]">About</a></li>
          <li><a href="/services.php" class="text-[color:var(--muted)] hover:text-[color:var(--text)]">Services</a></li>
          <li><a href="/gallery.php" class="text-[color:var(--muted)] hover:text-[color:var(--text)]">Gallery</a></li>
          <li><a href="/blog.php" class="text-[color:var(--muted)] hover:text-[color:var(--text)]">Blog</a></li>
          <li><a href="/contact.php" class="text-[color:var(--muted)] hover:text-[color:var(--text)]">Contact</a></li>
        </ul>
      </div>

      <!-- Social / Admin -->
      <div>
        <div class="text-sm font-medium text-[color:var(--text)] mb-3">
          Connect
        </div>

        <div class="flex flex-wrap gap-3 text-sm">
          <?php foreach (['instagram','facebook','linkedin','tiktok'] as $k): ?>
            <?php if (!empty($socials[$k])): ?>
              <a
                href="<?= h($socials[$k]) ?>"
                target="_blank"
                rel="noopener"
                class="rounded-full border border-[color:var(--border)] px-3 py-1 text-[color:var(--muted)] hover:border-[color:var(--accent)] hover:text-[color:var(--accent)]"
              >
                <?= ucfirst($k) ?>
              </a>
            <?php endif; ?>
          <?php endforeach; ?>

          <a
            href="/admin/"
            class="rounded-full border border-[color:var(--border)] px-3 py-1 text-[color:var(--muted)] hover:border-[color:var(--text)] hover:text-[color:var(--text)]"
          >
            Admin
          </a>
        </div>
      </div>

    </div>

    <!-- Divider -->
    <div class="my-8 h-px w-full bg-[color:var(--border)]"></div>

    <!-- Bottom row -->
    <div class="flex flex-col gap-3 text-xs text-[color:var(--muted)] md:flex-row md:items-center md:justify-between">
      <div>
        © <?= date('Y') ?> <?= h($name) ?>. All rights reserved.
      </div>

      <div class="flex gap-4">
        <a href="/privacy.php" class="hover:text-[color:var(--text)]">Privacy</a>
        <a href="/terms.php" class="hover:text-[color:var(--text)]">Terms</a>
      </div>
    </div>

  </div>
</footer>

</body>
</html>
