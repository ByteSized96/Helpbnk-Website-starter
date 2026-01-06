<?php
declare(strict_types=1);

$pageKey = 'home';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/partials/header.php';

$home = $site['pages']['home'] ?? [];

/* Slideshow config */
$ss = $home['slideshow'] ?? [];
$ssEnabled  = !empty($ss['enabled']);
$ssImages   = is_array($ss['images'] ?? null) ? array_values(array_filter($ss['images'])) : [];
$ssInterval = (int)($ss['interval'] ?? 4);
if ($ssInterval < 2) $ssInterval = 2;
$ssHeight   = (int)($ss['height'] ?? 360);
if ($ssHeight < 200) $ssHeight = 200;
?>

<!-- HERO (comes first now) -->
<section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 p-8 md:p-12">
  <div class="absolute inset-0 opacity-30" style="background: radial-gradient(circle at 20% 20%, rgba(79,70,229,.55), transparent 55%), radial-gradient(circle at 80% 30%, rgba(14,165,233,.4), transparent 50%), radial-gradient(circle at 50% 80%, rgba(236,72,153,.35), transparent 55%);"></div>
  <div class="relative">
    <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-gray-950/30 px-4 py-2 text-sm text-gray-200">
      <span class="h-2 w-2 rounded-full bg-accent"></span>
      Editable 5-page website starter
    </p>
    <h1 class="mt-5 text-3xl md:text-5xl font-semibold tracking-tight"><?= h($home['hero_title'] ?? 'Welcome') ?></h1>
    <p class="mt-4 max-w-2xl text-gray-200 text-lg"><?= h($home['hero_subtitle'] ?? '') ?></p>

    <div class="mt-7 flex flex-wrap gap-3">
      <a href="<?= h($home['cta_primary_href'] ?? '/contact.php') ?>"
         class="rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
        <?= h($home['cta_primary_text'] ?? 'Get in touch') ?>
      </a>
      <a href="<?= h($home['cta_secondary_href'] ?? '/services.php') ?>"
         class="rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
        <?= h($home['cta_secondary_text'] ?? 'View services') ?>
      </a>
    </div>
  </div>
</section>

<!-- SLIDESHOW (moved BELOW hero) -->
<?php if ($ssEnabled && !empty($ssImages)): ?>
<section class="mt-8 rounded-3xl overflow-hidden border"
  style="border-color:var(--border); background: var(--card); box-shadow: var(--shadow);">
  <div class="relative w-full" style="height: <?= (int)$ssHeight ?>px;">
    <?php foreach ($ssImages as $i => $src): ?>
      <img
        src="<?= h((string)$src) ?>"
        class="hb5-slide absolute inset-0 h-full w-full object-cover transition-opacity duration-700 <?= $i===0 ? '' : 'opacity-0' ?>"
        alt=""
        loading="<?= $i===0 ? 'eager' : 'lazy' ?>"
      />
    <?php endforeach; ?>
    <div class="absolute inset-0" style="background: linear-gradient(90deg, color-mix(in srgb, var(--bg) 70%, transparent), transparent 60%);"></div>
  </div>
</section>

<script>
(() => {
  const slides = Array.from(document.querySelectorAll('.hb5-slide'));
  if (slides.length <= 1) return;
  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  let i = 0;
  const interval = <?= (int)$ssInterval ?> * 1000;
  setInterval(() => {
    slides[i].classList.add('opacity-0');
    i = (i + 1) % slides.length;
    slides[i].classList.remove('opacity-0');
  }, interval);
})();
</script>
<?php endif; ?>

<?php $features = $home['feature_blocks'] ?? []; ?>
<section class="mt-10 grid gap-4 md:grid-cols-3">
  <?php foreach ($features as $f): ?>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <div class="h-10 w-10 rounded-2xl bg-accent/20 grid place-items-center">✦</div>
      <h3 class="mt-4 font-semibold text-xl"><?= h($f['title'] ?? '') ?></h3>
      <p class="mt-2 text-gray-300"><?= h($f['text'] ?? '') ?></p>
    </div>
  <?php endforeach; ?>
</section>

<?php $sections = $home['sections'] ?? []; ?>
<section class="mt-10 grid gap-4 md:grid-cols-2">
  <?php foreach ($sections as $s): ?>
    <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
      <h3 class="font-semibold text-xl"><?= h($s['heading'] ?? '') ?></h3>
      <p class="mt-3 text-gray-300 whitespace-pre-line"><?= h($s['text'] ?? '') ?></p>
    </div>
  <?php endforeach; ?>
</section>

<section class="mt-10 rounded-3xl border border-white/10 bg-white/5 p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
    <div class="text-2xl font-semibold">Ready to start?</div>
    <div class="text-gray-300 mt-1">Pop a message over and we’ll reply quickly.</div>
  </div>
  <a href="/contact.php" class="rounded-2xl bg-accent px-5 py-3 font-semibold text-white hover:opacity-90">Contact us</a>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
