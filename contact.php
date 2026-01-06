<?php
    declare(strict_types=1);
    $pageKey = 'contact';
    require_once __DIR__ . '/includes/bootstrap.php';
    require_once __DIR__ . '/partials/header.php';
    $contact = $site['pages']['contact'] ?? [];
$sent = (isset($_GET['sent']) && $_GET['sent'] === '1');
?>
<section class="grid gap-6 md:grid-cols-2">
  <div class="rounded-3xl border border-white/10 bg-white/5 p-8 md:p-10">
    <h1 class="text-3xl md:text-4xl font-semibold"><?= h($contact['headline'] ?? 'Contact') ?></h1>
    <p class="mt-3 text-gray-300"><?= h($contact['intro'] ?? '') ?></p>

    <?php if ($sent): ?>
      <div class="mt-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200">
        <?= h($contact['form_success_message'] ?? 'Thanks — your message has been sent.') ?>
      </div>
    <?php endif; ?>

    <form class="mt-6 grid gap-3" method="post" action="/contact-submit.php">
      <input type="hidden" name="csrf" value="<?= h(hb5_csrf_token()) ?>">
      <!-- Honeypot -->
      <div class="hidden">
        <label>Leave empty</label>
        <input type="text" name="company" value="">
      </div>

      <label class="text-sm text-gray-300">Name</label>
      <input name="name" required class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3 outline-none focus:ring-2 focus:ring-accent/60" />

      <label class="text-sm text-gray-300">Email</label>
      <input name="email" type="email" required class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3 outline-none focus:ring-2 focus:ring-accent/60" />

      <label class="text-sm text-gray-300">Message</label>
      <textarea name="message" rows="5" required class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3 outline-none focus:ring-2 focus:ring-accent/60"></textarea>

      <button class="mt-2 rounded-2xl bg-accent px-5 py-3 font-semibold hover:opacity-90">Send message</button>
      <p class="text-xs text-gray-400">Protected with basic anti‑spam + rate limiting.</p>
    </form>
  </div>

  <div class="rounded-3xl border border-white/10 bg-gray-950/40 overflow-hidden">
    <iframe
      title="Map"
      src="<?= h($site['site']['google_maps_embed_url'] ?? '') ?>"
      class="h-[420px] w-full"
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"></iframe>

    <div class="p-6 border-t border-white/10">
      <div class="font-semibold">Contact details</div>
      <div class="mt-2 text-gray-300"><?= h($site['site']['address'] ?? '') ?></div>
      <div class="mt-2 text-gray-300"><?= h($site['site']['phone'] ?? '') ?></div>
      <div class="text-gray-300"><?= h($site['site']['email'] ?? '') ?></div>
    </div>
  </div>
</section>
<?php

    require_once __DIR__ . '/partials/footer.php';
