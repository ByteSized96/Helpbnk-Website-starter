<?php
declare(strict_types=1);
$pageKey = 'blog';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/partials/header.php';

$slug = (string)($_GET['slug'] ?? '');
$blog = hb5_load_blog();
$posts = $blog['posts'] ?? [];

$post = null;
foreach ($posts as $p) {
  if (!empty($p['published']) && (string)($p['slug'] ?? '') === $slug) { $post = $p; break; }
}

if (!$post) {
  http_response_code(404);
}
?>
<a class="text-sm font-semibold hover:opacity-80" style="color: var(--accent);" href="/blog.php">← Back to blog</a>

<?php if (!$post): ?>
  <div class="mt-6 card rounded-2xl p-6 max-w-3xl">
    <h1 class="text-2xl font-semibold tracking-tight">Post not found</h1>
    <p class="mt-2 muted">Try returning to the blog and choosing another post.</p>
  </div>
<?php else: ?>
  <article class="mt-6 card rounded-2xl p-7 max-w-3xl">
    <div class="text-sm muted">
      <time datetime="<?= h((string)($post['date'] ?? '')) ?>"><?= h((string)($post['date'] ?? '')) ?></time>
    </div>
    <h1 class="mt-2 text-3xl font-semibold tracking-tight"><?= h((string)($post['title'] ?? '')) ?></h1>
    <?php if (!empty($post['excerpt'])): ?>
      <p class="mt-3 muted"><?= h((string)$post['excerpt']) ?></p>
    <?php endif; ?>

    <?php $safe = nl2br(h((string)($post['content'] ?? ''))); ?>
    <div class="mt-6 prose max-w-none"><?= $safe ?></div>
  </article>

  <script type="application/ld+json">
    <?= json_encode([
      "@context"=>"https://schema.org",
      "@type"=>"BlogPosting",
      "headline"=>$post["title"] ?? "",
      "datePublished"=>$post["date"] ?? "",
      "dateModified"=>$post["date"] ?? "",
      "description"=>$post["excerpt"] ?? "",
      "publisher"=>["@type"=>"Organization","name"=>$site['site']['brand_name'] ?? "Website"]
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>
  </script>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
