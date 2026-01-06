<?php
    declare(strict_types=1);
    $pageKey = 'services';
    require_once __DIR__ . '/includes/bootstrap.php';
    require_once __DIR__ . '/partials/header.php';
    $svc = $site['pages']['services'] ?? [];
$items = $svc['items'] ?? [];
?>
<section class="rounded-3xl border border-white/10 bg-white/5 p-8 md:p-12">
  <h1 class="text-3xl md:text-4xl font-semibold"><?= h($svc['headline'] ?? 'Services') ?></h1>
  <p class="mt-3 text-gray-300">Edit services in Admin → Services.</p>

  <div class="mt-8 grid gap-4 md:grid-cols-3">
    <?php foreach ($items as $it): ?>
      <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6 flex flex-col">
        <div class="flex items-start justify-between gap-3">
          <h3 class="text-xl font-semibold"><?= h($it['title'] ?? '') ?></h3>
          <span class="text-sm rounded-full border border-white/10 bg-white/5 px-3 py-1"><?= h($it['price'] ?? '') ?></span>
        </div>
        <p class="mt-3 text-gray-300 flex-1"><?= h($it['text'] ?? '') ?></p>
        <a href="/contact.php" class="mt-5 inline-flex justify-center rounded-2xl bg-accent px-4 py-2 font-semibold hover:opacity-90">Enquire</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php

    require_once __DIR__ . '/partials/footer.php';
?>