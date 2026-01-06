<?php
declare(strict_types=1);
$pageKey = 'blog';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/partials/header.php';

$blog = hb5_load_blog();
$posts = $blog['posts'] ?? [];
$posts = array_values(array_filter($posts, fn($p) => !empty($p['published'])));
usort($posts, fn($a,$b) => strcmp((string)($b['date']??''),(string)($a['date']??'')));
?>
<section class="max-w-3xl">
  <p class="text-sm font-semibold tracking-wide" style="color: var(--accent);">Blog</p>
  <h1 class="mt-2 text-3xl font-semibold tracking-tight">Updates & articles</h1>
  <p class="mt-3 muted">Short, helpful posts that build trust and boost SEO over time.</p>
</section>

<section class="mt-8 grid gap-4">
  <?php if (!$posts): ?>
    <div class="card rounded-2xl p-6">
      <p class="muted">No posts yet.</p>
    </div>
  <?php endif; ?>

  <?php foreach ($posts as $p):
    $slug = (string)($p['slug'] ?? '');
    $href = '/post.php?slug=' . rawurlencode($slug);
  ?>
    <article class="card rounded-2xl p-6">
      <div class="flex items-center gap-3 text-sm muted">
        <time datetime="<?= h((string)($p['date'] ?? '')) ?>"><?= h((string)($p['date'] ?? '')) ?></time>
        <?php if (!empty($p['tags']) && is_array($p['tags'])): ?>
          <span aria-hidden="true">•</span>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($p['tags'] as $t): ?>
              <span class="rounded-full border border-[color:var(--border)] px-2 py-0.5 text-xs"><?= h((string)$t) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <h2 class="mt-2 text-xl font-semibold">
        <a class="hover:opacity-80" href="<?= h($href) ?>"><?= h((string)($p['title'] ?? '')) ?></a>
      </h2>
      <p class="mt-2 muted"><?= h((string)($p['excerpt'] ?? '')) ?></p>

      <div class="mt-4">
        <a class="inline-flex items-center gap-2 font-semibold hover:opacity-80" href="<?= h($href) ?>">
          Read more <span aria-hidden="true">→</span>
        </a>
      </div>
    </article>
  <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
