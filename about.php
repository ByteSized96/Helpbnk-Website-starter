<?php
declare(strict_types=1);

$pageKey = 'about';
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/partials/header.php';

$about = $site['pages']['about'] ?? [];
$headline = h($about['headline'] ?? 'About');
$body = trim((string)($about['body'] ?? ''));
?>

<!-- HERO -->
<section class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5">
  <div class="absolute inset-0 opacity-30"
       style="background:
         radial-gradient(circle at 10% 20%, rgba(79,70,229,.40), transparent 55%),
         radial-gradient(circle at 90% 10%, rgba(14,165,233,.25), transparent 50%),
         radial-gradient(circle at 50% 90%, rgba(236,72,153,.20), transparent 55%);"></div>

  <div class="relative p-6 sm:p-8 md:p-12">
    <div class="flex flex-col gap-4">
      <p class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-gray-950/30 px-4 py-2 text-sm text-gray-200">
        <span class="h-2 w-2 rounded-full bg-accent"></span>
        About
      </p>

      <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
        <?= $headline ?>
      </h1>

      <?php if ($body !== ''): ?>
        <div class="max-w-3xl text-base sm:text-lg leading-relaxed text-gray-200 whitespace-pre-line">
          <?= h($body) ?>
        </div>
      <?php else: ?>
        <p class="max-w-3xl text-base sm:text-lg leading-relaxed text-gray-300">
          Add your story in the admin panel — keep it human: why you started, what you care about, and what people can expect.
        </p>
      <?php endif; ?>

      <div class="mt-2 flex flex-col sm:flex-row gap-3">
        <a href="/contact.php"
           class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
          Get in touch
        </a>
        <a href="/services.php"
           class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white shadow-lg shadow-black/20 hover:opacity-90">
          View services
        </a>
      </div>
    </div>
  </div>
</section>

<!-- “REAL” CONTENT (mobile-first) -->
<section class="mt-8 grid gap-4 md:grid-cols-3">
  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">What we do</div>
    <div class="mt-2 text-xl font-semibold">Clear, honest work</div>
    <p class="mt-2 text-gray-300 leading-relaxed">
      We focus on doing the job properly — no fluff, no confusion. You’ll always know what’s happening and why.
    </p>
  </div>

  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">How we work</div>
    <div class="mt-2 text-xl font-semibold">Simple process</div>
    <p class="mt-2 text-gray-300 leading-relaxed">
      Quick chat → clear plan → get it done. We keep things straightforward and keep you updated throughout.
    </p>
  </div>

  <div class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
    <div class="text-sm text-gray-300">What you get</div>
    <div class="mt-2 text-xl font-semibold">A result you’re proud of</div>
    <p class="mt-2 text-gray-300 leading-relaxed">
      The goal isn’t “good enough”. It’s something that looks right, works right, and lasts.
    </p>
  </div>
</section>

<!-- Small “stats” strip (feels premium, not AI) -->
<section class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
  <div class="grid gap-4 sm:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-gray-950/30 p-5">
      <div class="text-2xl font-semibold">Fast replies</div>
      <div class="mt-1 text-sm text-gray-300">No chasing around</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-gray-950/30 p-5">
      <div class="text-2xl font-semibold">Transparent quotes</div>
      <div class="mt-1 text-sm text-gray-300">Clear pricing, upfront</div>
    </div>
    <div class="rounded-2xl border border-white/10 bg-gray-950/30 p-5">
      <div class="text-2xl font-semibold">Quality first</div>
      <div class="mt-1 text-sm text-gray-300">Built to last</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-7 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
  <div>
    <div class="text-2xl font-semibold">Want to talk it through?</div>
    <div class="mt-1 text-gray-300">Send a message — we’ll reply quickly with next steps.</div>
  </div>
  <a href="/contact.php" class="inline-flex items-center justify-center rounded-2xl bg-accent px-5 py-3 font-semibold text-white hover:opacity-90">
    Contact us
  </a>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
