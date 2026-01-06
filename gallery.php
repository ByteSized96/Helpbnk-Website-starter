<?php
    declare(strict_types=1);
    $pageKey = 'gallery';
    require_once __DIR__ . '/includes/bootstrap.php';
    require_once __DIR__ . '/partials/header.php';
    $gal = $site['pages']['gallery'] ?? [];
$items = $gal['items'] ?? [];
?>
<section class="rounded-3xl border border-white/10 bg-white/5 p-8 md:p-12">
  <h1 class="text-3xl md:text-4xl font-semibold"><?= h($gal['headline'] ?? 'Gallery') ?></h1>
  <p class="mt-3 text-gray-300">Upload and manage images in Admin → Gallery.</p>

  <div class="mt-8 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
    <?php foreach ($items as $it): ?>
      <figure class="rounded-3xl overflow-hidden border border-white/10 bg-gray-950/40">
        <div class="aspect-[4/3] bg-black/20">
          <img src="<?= h($it['image'] ?? '') ?>" alt="" class="h-full w-full object-cover">
        </div>
        <figcaption class="p-4 text-sm text-gray-300"><?= h($it['caption'] ?? '') ?></figcaption>
      </figure>
    <?php endforeach; ?>
  </div>
</section>
<?php

    require_once __DIR__ . '/partials/footer.php';
?>