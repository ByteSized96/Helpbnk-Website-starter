<?php
declare(strict_types=1);

$pageKey = 'services';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/partials/header.php';

$svc   = $site['pages']['services'] ?? [];
$items = is_array($svc['items'] ?? null) ? $svc['items'] : [];

$headline = h($svc['headline'] ?? 'Services');
?>

<!-- HERO -->
<section class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5">
  <div class="absolute inset-0 opacity-30"
       style="background:
         radial-gradient(circle at 12% 25%, rgba(79,70,229,.35), transparent 55%),
         radial-gradient(circle at 88% 15%, rgba(14,165,233,.22), transparent 55%),
         radial-gradient(circle at 50% 90%, rgba(236,72,153,.18), transparent 60%);"></div>

  <div class="relative p-6 sm:p-8 md:p-12">
    <p class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-gray-950/30 px-4 py-2 text-sm text-gray-200">
      <span class="h-2 w-2 rounded-full bg-accent"></span>
      Services
    </p>

    <div class="mt-4 flex flex-col gap-3">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
        <?= $headline ?>
      </h1>

      <p class="max-w-2xl text-base sm:text-lg text-gray-200 leading-relaxed">
        Browse what we offer below. If you’re not sure what you need, message us and we’ll point you in the right direction.
      </p>

      <div class="mt-2 flex flex-col sm:flex-row gap-3">
        <a href="/contact.php"
           class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
          Get a quote
        </a>
        <a href="/contact.php"
           class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
          Ask a question
        </a>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES LIST -->
<section class="mt-8">
  <?php if (!$items): ?>
    <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6 text-gray-300">
      No services added yet. Add them in Admin → Services.
    </div>
  <?php else: ?>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($items as $it): ?>
        <?php
          $title = trim((string)($it['title'] ?? ''));
          $price = trim((string)($it['price'] ?? ''));
          $text  = trim((string)($it['text'] ?? ''));
          if ($title === '') continue;
        ?>

        <article class="group rounded-3xl border border-white/10 bg-gray-950/40 p-6 flex flex-col overflow-hidden">
          <!-- top glow (subtle, modern) -->
          <div class="pointer-events-none absolute opacity-0 group-hover:opacity-100 transition-opacity duration-300"
               style="inset:-40px; background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.10), transparent 45%);">
          </div>

          <div class="relative">
            <div class="flex items-start justify-between gap-3">
              <h2 class="text-xl font-semibold leading-snug"><?= h($title) ?></h2>

              <?php if ($price !== ''): ?>
                <span class="shrink-0 text-sm rounded-full border border-white/10 bg-white/5 px-3 py-1 text-gray-100">
                  <?= h($price) ?>
                </span>
              <?php endif; ?>
            </div>

            <?php if ($text !== ''): ?>
              <p class="mt-3 text-gray-300 leading-relaxed">
                <?= h($text) ?>
              </p>
            <?php endif; ?>

            <div class="mt-6 flex flex-col gap-2">
              <a href="/contact.php"
                 class="inline-flex items-center justify-center rounded-2xl bg-accent px-4 py-2 font-semibold text-white hover:opacity-90">
                Enquire
              </a>
              <div class="text-xs text-gray-400 text-center">
                Quick reply • Clear pricing • No pressure
              </div>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<!-- CTA STRIP -->
<section class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-7 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
  <div>
    <div class="text-2xl font-semibold">Not sure what to book?</div>
    <div class="mt-1 text-gray-300">Tell us what you’re trying to achieve and we’ll recommend the best option.</div>
  </div>
  <a href="/contact.php" class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white hover:opacity-90">
    Message us
  </a>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
