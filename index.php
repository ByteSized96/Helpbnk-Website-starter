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
if ($ssHeight < 240) $ssHeight = 240;

// Content blocks
$features = is_array($home['feature_blocks'] ?? null) ? $home['feature_blocks'] : [];
$sections = is_array($home['sections'] ?? null) ? $home['sections'] : [];
?>

<style>
  /* Tiny polish without changing your build pipeline */
  .hb5-container { max-width: 1100px; }
  .hb5-soft-ring { box-shadow: 0 20px 60px rgba(0,0,0,.22); }
  .hb5-glass { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
</style>

<main class="mx-auto hb5-container px-4 py-8 md:py-12">

  <!-- HERO: split layout, strong hierarchy, real “designed” feel -->
  <section class="relative overflow-hidden rounded-[32px] border border-white/10 bg-gradient-to-br from-white/10 to-white/5">
    <div class="absolute inset-0 opacity-35"
      style="background:
        radial-gradient(circle at 20% 15%, rgba(79,70,229,.55), transparent 55%),
        radial-gradient(circle at 85% 25%, rgba(14,165,233,.42), transparent 52%),
        radial-gradient(circle at 55% 85%, rgba(236,72,153,.30), transparent 58%);">
    </div>

    <div class="relative p-6 md:p-10 grid gap-8 md:grid-cols-2 md:items-center">
      <!-- Left -->
      <div>
        <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-gray-950/30 px-4 py-2 text-sm text-gray-200 hb5-glass">
          <span class="h-2 w-2 rounded-full bg-accent"></span>
          Editable website you can hand to clients
        </p>

        <h1 class="mt-5 text-3xl md:text-5xl font-semibold tracking-tight leading-tight">
          <?= h($home['hero_title'] ?? 'A beautiful website ready today.') ?>
        </h1>

        <p class="mt-4 max-w-xl text-gray-200 text-lg leading-relaxed">
          <?= h($home['hero_subtitle'] ?? 'Edit everything from your admin panel. Mobile-first layout, fast load times, and no plugin bloat.') ?>
        </p>

        <div class="mt-7 flex flex-wrap gap-3">
          <a href="<?= h($home['cta_primary_href'] ?? '/contact.php') ?>"
             class="rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/25 hover:opacity-90">
            <?= h($home['cta_primary_text'] ?? 'Get in touch') ?>
          </a>

          <a href="<?= h($home['cta_secondary_href'] ?? '/services.php') ?>"
             class="rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/25 hover:opacity-90">
            <?= h($home['cta_secondary_text'] ?? 'View services') ?>
          </a>
        </div>

        <div class="mt-7 flex flex-wrap gap-3 text-sm text-gray-300">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2">Mobile-first</span>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2">Fast + lightweight</span>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2">SEO + leads</span>
        </div>
      </div>

      <!-- Right: visual panel (uses slideshow if available, otherwise a “designed” placeholder) -->
      <div class="relative">
        <div class="rounded-[28px] border border-white/10 bg-gray-950/30 hb5-glass overflow-hidden hb5-soft-ring">
          <div class="p-4 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="h-2 w-2 rounded-full bg-red-400/70"></span>
              <span class="h-2 w-2 rounded-full bg-yellow-400/70"></span>
              <span class="h-2 w-2 rounded-full bg-green-400/70"></span>
            </div>
            <div class="text-xs text-gray-300">Live Preview</div>
          </div>

          <?php if ($ssEnabled && !empty($ssImages)): ?>
            <div class="relative w-full" style="height: <?= (int)$ssHeight ?>px;">
              <?php foreach ($ssImages as $i => $src): ?>
                <img
                  src="<?= h((string)$src) ?>"
                  class="hb5-slide absolute inset-0 h-full w-full object-cover transition-opacity duration-700 <?= $i===0 ? '' : 'opacity-0' ?>"
                  alt=""
                  loading="<?= $i===0 ? 'eager' : 'lazy' ?>"
                />
              <?php endforeach; ?>
              <div class="absolute inset-0"
                style="background: linear-gradient(180deg, rgba(0,0,0,.05), rgba(0,0,0,.35));"></div>
            </div>
          <?php else: ?>
            <div class="p-6">
              <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm text-gray-200 font-semibold">Edit → Save → Publish</div>
                <div class="mt-1 text-sm text-gray-300">Clients can update text, images, services, and SEO from their phone.</div>
              </div>
              <div class="mt-4 grid gap-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                  <div class="text-xs text-gray-300">Home hero</div>
                  <div class="mt-2 h-3 w-4/5 rounded bg-white/10"></div>
                  <div class="mt-2 h-3 w-3/5 rounded bg-white/10"></div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                  <div class="text-xs text-gray-300">Services</div>
                  <div class="mt-2 h-3 w-2/3 rounded bg-white/10"></div>
                  <div class="mt-2 h-3 w-1/2 rounded bg-white/10"></div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <?php if ($ssEnabled && !empty($ssImages)): ?>
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

  <!-- FEATURE STRIP: better spacing + hierarchy -->
  <?php if (!empty($features)): ?>
    <section class="mt-10">
      <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
          <h2 class="text-2xl font-semibold">Built for modern businesses</h2>
          <p class="mt-2 text-gray-300">Fast, editable, and lead-ready without complexity.</p>
        </div>
      </div>

      <div class="mt-6 grid gap-4 md:grid-cols-3">
        <?php foreach ($features as $f): ?>
          <div class="rounded-3xl border border-white/10 bg-white/5 p-6 hover:bg-white/10 transition">
            <div class="h-11 w-11 rounded-2xl bg-accent/20 grid place-items-center text-lg">✦</div>
            <h3 class="mt-4 font-semibold text-xl"><?= h($f['title'] ?? '') ?></h3>
            <p class="mt-2 text-gray-300 leading-relaxed"><?= h($f['text'] ?? '') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- “WHY / WHAT” section: make it feel intentional -->
  <?php if (!empty($sections)): ?>
    <section class="mt-12">
      <div class="grid gap-4 md:grid-cols-2">
        <?php foreach ($sections as $i => $s): ?>
          <div class="rounded-3xl border border-white/10 <?= $i === 0 ? 'bg-gray-950/30 hb5-glass' : 'bg-white/5' ?> p-7">
            <div class="text-xs text-gray-400 uppercase tracking-widest">
              <?= $i === 0 ? 'Why people choose you' : 'What you offer' ?>
            </div>
            <h3 class="mt-2 font-semibold text-2xl"><?= h($s['heading'] ?? '') ?></h3>
            <p class="mt-3 text-gray-300 whitespace-pre-line leading-relaxed"><?= h($s['text'] ?? '') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- CTA: standout decision moment -->
  <section class="mt-12 relative overflow-hidden rounded-[32px] border border-white/10 bg-gradient-to-br from-white/10 to-white/5">
    <div class="absolute inset-0 opacity-30"
      style="background: radial-gradient(circle at 30% 30%, rgba(79,70,229,.45), transparent 55%), radial-gradient(circle at 70% 70%, rgba(14,165,233,.35), transparent 55%);"></div>

    <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
      <div>
        <div class="text-2xl md:text-3xl font-semibold">Ready to start?</div>
        <div class="text-gray-300 mt-2">Pop a message over and we’ll reply quickly.</div>
      </div>
      <a href="/contact.php"
         class="inline-flex items-center justify-center rounded-2xl bg-accent px-6 py-3 font-semibold text-white shadow-lg shadow-black/25 hover:opacity-90">
        Contact us
      </a>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
