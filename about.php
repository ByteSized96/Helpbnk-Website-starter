<?php
    declare(strict_types=1);
    $pageKey = 'about';
    require_once __DIR__ . '/includes/bootstrap.php';
    require_once __DIR__ . '/partials/header.php';
    $about = $site['pages']['about'] ?? [];
?>
<section class="rounded-3xl border border-white/10 bg-white/5 p-8 md:p-12">
  <h1 class="text-3xl md:text-4xl font-semibold"><?= h($about['headline'] ?? 'About') ?></h1>
  <p class="mt-5 text-gray-200 whitespace-pre-line text-lg"><?= h($about['body'] ?? '') ?></p>
</section>

<section class="mt-8 grid gap-4 md:grid-cols-3">
  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">Value</div>
    <div class="mt-2 text-xl font-semibold">Trust</div>
    <p class="mt-2 text-gray-300">Replace this with your value or promise.</p>
  </div>
  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">Value</div>
    <div class="mt-2 text-xl font-semibold">Quality</div>
    <p class="mt-2 text-gray-300">Replace this with your value or promise.</p>
  </div>
  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">Value</div>
    <div class="mt-2 text-xl font-semibold">Speed</div>
    <p class="mt-2 text-gray-300">Replace this with your value or promise.</p>
  </div>
</section>
<?php

    require_once __DIR__ . '/partials/footer.php';
?>