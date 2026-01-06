<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
hb5_require_admin();

$tab = $_GET['tab'] ?? 'pages';
$allowed = ['pages','settings','services','gallery','blog','seo','leads','security','media','backup'];
if (!in_array($tab, $allowed, true)) $tab = 'pages';

function active_tab(string $t, string $tab): string {
  return $t === $tab ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</head>
<body class="min-h-screen bg-gray-950 text-white">
  <header class="border-b border-white/10 bg-gray-950/60 sticky top-0 z-50">
    <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between gap-4">
      <div>
        <div class="font-semibold">Admin Panel</div>
        <div class="text-xs text-gray-400">Edit pages • Upload images • Manage SEO • View leads</div>
      </div>
      <div class="flex items-center gap-2">
        <a href="/" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">View site</a>
        <a href="/admin/logout.php" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Logout</a>
      </div>
    </div>
  </header>

  <div class="mx-auto max-w-6xl px-4 py-8 grid gap-6 md:grid-cols-[240px,1fr]">
    <aside class="rounded-3xl border border-white/10 bg-white/5 p-3 h-fit">
      <nav class="grid gap-1 text-sm">
        <a class="rounded-2xl px-3 py-2 <?= active_tab('pages',$tab) ?>" href="/admin/?tab=pages">Pages</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('services',$tab) ?>" href="/admin/?tab=services">Services</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('gallery',$tab) ?>" href="/admin/?tab=gallery">Gallery</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('blog',$tab) ?>" href="/admin/?tab=blog">Blog</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('settings',$tab) ?>" href="/admin/?tab=settings">Site Settings</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('seo',$tab) ?>" href="/admin/?tab=seo">SEO</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('leads',$tab) ?>" href="/admin/?tab=leads">Leads</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('security',$tab) ?>" href="/admin/?tab=security">Security</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('media',$tab) ?>" href="/admin/?tab=media">Media Library</a>
        <a class="rounded-2xl px-3 py-2 <?= active_tab('backup',$tab) ?>" href="/admin/?tab=backup">Backup</a>
      </nav>
    </aside>

    <section class="rounded-3xl border border-white/10 bg-gray-950/40 p-6">
      <div id="toast" class="hidden mb-4 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-3 text-emerald-200"></div>

      <?php if ($tab === 'pages'): ?>
        <?php $pages = $site['pages'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Pages</h1>
          <button onclick="savePages()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
        </div>

        <p class="mt-2 text-sm text-gray-300">Edit hero, CTAs, features, and page headers here.</p>

        <div class="mt-6 grid gap-6">
          <!-- HOME -->
          <?php
            $home = $pages['home'] ?? [];
            $homeFeatures = $home['feature_blocks'] ?? [];
            $homeSections = $home['sections'] ?? [];
            $ss = $home['slideshow'] ?? [];
          ?>
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Home</div>

            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Hero title</label>
              <input id="home_hero_title" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($home['hero_title'] ?? '') ?>">

              <label class="text-sm text-gray-300">Hero subtitle</label>
              <textarea id="home_hero_subtitle" rows="3" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3"><?= h($home['hero_subtitle'] ?? '') ?></textarea>

              <div class="grid gap-3 md:grid-cols-2">
                <div class="grid gap-2">
                  <label class="text-sm text-gray-300">Primary CTA text</label>
                  <input id="home_cta_primary_text" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($home['cta_primary_text'] ?? '') ?>">
                </div>
                <div class="grid gap-2">
                  <label class="text-sm text-gray-300">Primary CTA link</label>
                  <input id="home_cta_primary_href" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($home['cta_primary_href'] ?? '') ?>">
                </div>
                <div class="grid gap-2">
                  <label class="text-sm text-gray-300">Secondary CTA text</label>
                  <input id="home_cta_secondary_text" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($home['cta_secondary_text'] ?? '') ?>">
                </div>
                <div class="grid gap-2">
                  <label class="text-sm text-gray-300">Secondary CTA link</label>
                  <input id="home_cta_secondary_href" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($home['cta_secondary_href'] ?? '') ?>">
                </div>
              </div>
            </div>

            <div class="mt-6 rounded-3xl border border-white/10 bg-gray-950/30 p-4">
              <div class="flex items-center justify-between gap-3">
                <div class="font-semibold">Home slideshow (optional)</div>
                <label class="inline-flex items-center gap-2 text-sm text-gray-200">
                  <input id="home_slideshow_enabled" type="checkbox" <?= !empty($ss['enabled']) ? 'checked' : '' ?>>
                  Enable
                </label>
              </div>

              <div class="mt-3 grid gap-3">
                <div class="grid gap-3 md:grid-cols-2">
                  <div class="grid gap-2">
                    <label class="text-sm text-gray-300">Interval (seconds)</label>
                    <input id="home_slideshow_interval" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h((string)($ss['interval'] ?? '4')) ?>" placeholder="4">
                  </div>
                  <div class="grid gap-2">
                    <label class="text-sm text-gray-300">Height (px)</label>
                    <input id="home_slideshow_height" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h((string)($ss['height'] ?? '360')) ?>" placeholder="360">
                  </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2">
                  <div class="text-sm text-gray-300">Slides</div>

                  <div class="flex flex-wrap items-center gap-2">
                    <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
                      Upload slide
                      <input type="file" accept="image/*" class="hidden"
                        onchange="uploadSlideImage(this.files[0]); this.value='';">
                    </label>

                    <button onclick="addSlide()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">
                      Add slide
                    </button>
                  </div>
                </div>

                <div class="text-xs text-gray-400">
                  Upload images here and they’ll be added automatically. Drag to reorder.
                </div>

                <div id="slidesWrap" class="grid gap-2">
                  <?php $slides = is_array($ss['images'] ?? null) ? $ss['images'] : []; ?>
                  <?php foreach ($slides as $img): ?>
                    <div class="slide_row flex items-center gap-2 rounded-2xl border border-white/10 bg-gray-950/40 p-2">
                      <span class="drag-handle cursor-grab select-none opacity-80 px-2">⠿</span>
                      <input class="slide_src flex-1 rounded-xl border border-white/10 bg-gray-950/50 px-3 py-2 text-sm" value="<?= h((string)$img) ?>" placeholder="/uploads/slide.jpg">
                      <button onclick="this.closest('.slide_row').remove()" class="px-3 py-2 text-sm text-red-200 hover:text-red-100">Remove</button>
                    </div>
                  <?php endforeach; ?>
                </div>

                <div class="text-xs text-gray-400">
                  Tip: upload images in Media Library, then paste paths here. Drag to reorder.
                </div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
              <div class="font-semibold">Feature blocks</div>
              <button onclick="addHomeFeature()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Add</button>
            </div>

            <div id="homeFeaturesWrap" class="mt-4 grid gap-3">
              <?php foreach ($homeFeatures as $f): ?>
                <div class="hb5_feat rounded-3xl border border-white/10 bg-gray-950/40 p-4">
                  <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-300"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Feature</div>
                    <button onclick="this.closest('.hb5_feat').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
                  </div>
                  <div class="mt-3 grid gap-2">
                    <input class="hb5_feat_title rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" placeholder="Title" value="<?= h($f['title'] ?? '') ?>">
                    <textarea class="hb5_feat_text rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" rows="2" placeholder="Text"><?= h($f['text'] ?? '') ?></textarea>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="mt-6 flex items-center justify-between">
              <div class="font-semibold">Home sections</div>
              <button onclick="addHomeSection()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Add</button>
            </div>

            <div id="homeSectionsWrap" class="mt-4 grid gap-3">
              <?php foreach ($homeSections as $s): ?>
                <div class="hb5_sec rounded-3xl border border-white/10 bg-gray-950/40 p-4">
                  <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-300"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Section</div>
                    <button onclick="this.closest('.hb5_sec').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
                  </div>
                  <div class="mt-3 grid gap-2">
                    <input class="hb5_sec_heading rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" placeholder="Heading" value="<?= h($s['heading'] ?? '') ?>">
                    <textarea class="hb5_sec_text rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" rows="3" placeholder="Text"><?= h($s['text'] ?? '') ?></textarea>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- ABOUT -->
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">About</div>
            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Headline</label>
              <input id="about_headline" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($pages['about']['headline'] ?? '') ?>">
              <label class="text-sm text-gray-300">Body</label>
              <textarea id="about_body" rows="8" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3"><?= h($pages['about']['body'] ?? '') ?></textarea>
            </div>
          </div>

          <!-- SERVICES header -->
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Services page header</div>
            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Headline</label>
              <input id="services_headline" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($pages['services']['headline'] ?? '') ?>">
            </div>
          </div>

          <!-- GALLERY header -->
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Gallery page header</div>
            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Headline</label>
              <input id="gallery_headline" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($pages['gallery']['headline'] ?? '') ?>">
            </div>
          </div>

          <!-- CONTACT -->
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Contact</div>
            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Headline</label>
              <input id="contact_headline" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($pages['contact']['headline'] ?? '') ?>">
              <label class="text-sm text-gray-300">Intro</label>
              <textarea id="contact_intro" rows="4" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3"><?= h($pages['contact']['intro'] ?? '') ?></textarea>
              <label class="text-sm text-gray-300">Success message</label>
              <input id="contact_success" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($pages['contact']['form_success_message'] ?? '') ?>">
            </div>
          </div>
        </div>

      <?php elseif ($tab === 'services'): ?>
        <?php $items = $site['pages']['services']['items'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Services</h1>
          <div class="flex gap-2">
            <button onclick="addService()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Add</button>
            <button onclick="saveServices()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
          </div>
        </div>

        <div id="servicesWrap" class="mt-6 grid gap-4">
          <?php foreach ($items as $idx=>$it): ?>
            <!-- FIX: give the OUTER wrapper a class so Remove works reliably -->
            <div class="svc_card rounded-3xl border border-white/10 bg-white/5 p-5">
              <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 font-semibold">
                  <span class="drag-handle cursor-grab select-none opacity-80">⠿</span>
                  Service <?= (int)$idx + 1 ?>
                </div>
                <!-- FIX: remove the whole card -->
                <button onclick="this.closest('.svc_card').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
              </div>

              <div class="svc mt-4 grid gap-3">
                <input class="svc_title rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Title" value="<?= h($it['title'] ?? '') ?>">
                <input class="svc_price rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Price" value="<?= h($it['price'] ?? '') ?>">
                <textarea class="svc_text rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="3" placeholder="Description"><?= h($it['text'] ?? '') ?></textarea>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'gallery'): ?>
        <?php $gitems = $site['pages']['gallery']['items'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Gallery</h1>
          <div class="flex gap-2">
            <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
              Upload image
              <input id="galleryUpload" type="file" accept="image/*" class="hidden" onchange="uploadGalleryImage(this.files[0]); this.value='';">
            </label>
            <button onclick="addGallery()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Add</button>
            <button onclick="saveGallery()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
          </div>
        </div>

        <p class="mt-2 text-sm text-gray-300">Tip: Upload an image, then paste its path into an item (or use the auto-add button after upload).</p>

        <div id="galleryWrap" class="mt-6 grid gap-4">
          <?php foreach ($gitems as $it): ?>
            <div class="gal rounded-3xl border border-white/10 bg-white/5 p-5">
              <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 font-semibold"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Item</div>
                <button onclick="this.closest('.gal').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
              </div>
              <div class="mt-4 grid gap-3">
                <input class="gal_image rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="/uploads/your-image.jpg" value="<?= h($it['image'] ?? '') ?>">
                <input class="gal_caption rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Caption" value="<?= h($it['caption'] ?? '') ?>">
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'settings'): ?>
        <?php $s = $site['site'] ?? []; $assets = $s['assets'] ?? []; $social = $s['socials'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Site Settings</h1>
          <button onclick="saveSettings()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
        </div>

        <div class="mt-6 grid gap-4">
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <label class="text-sm text-gray-300">Brand name</label>
            <input id="brand_name" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($s['brand_name'] ?? '') ?>">
            <label class="text-sm text-gray-300">Tagline</label>
            <input id="tagline" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($s['tagline'] ?? '') ?>">
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Contact</div>
            <input id="phone" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Phone" value="<?= h($s['phone'] ?? '') ?>">
            <input id="email" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Email" value="<?= h($s['email'] ?? '') ?>">
            <textarea id="address" rows="3" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Address"><?= h($s['address'] ?? '') ?></textarea>
            <input id="maps" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Google maps embed URL" value="<?= h($s['google_maps_embed_url'] ?? '') ?>">
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Theme</div>

            <label class="text-sm text-gray-300">Theme mode</label>
            <?php $mode = $s['theme']['mode'] ?? 'light'; ?>
            <select id="theme_mode" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3">
              <option value="light" <?= $mode==='light'?'selected':'' ?>>Light</option>
              <option value="warm" <?= $mode==='warm'?'selected':'' ?>>Warm (cream)</option>
              <option value="dark" <?= $mode==='dark'?'selected':'' ?>>Dark</option>
            </select>
            <div class="text-xs text-gray-400">Changes the whole site background/cards/text — not just buttons.</div>

            <label class="text-sm text-gray-300">Palette preset</label>
            <?php $preset = $s['theme']['palette'] ?? 'custom'; ?>
            <select id="palette" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3">
              <option value="custom" <?= $preset==='custom'?'selected':'' ?>>Custom</option>
              <option value="indigo" <?= $preset==='indigo'?'selected':'' ?> data-hex="#4f46e5">Indigo</option>
              <option value="emerald" <?= $preset==='emerald'?'selected':'' ?> data-hex="#10b981">Emerald</option>
              <option value="rose" <?= $preset==='rose'?'selected':'' ?> data-hex="#f43f5e">Rose</option>
              <option value="amber" <?= $preset==='amber'?'selected':'' ?> data-hex="#f59e0b">Amber</option>
              <option value="sky" <?= $preset==='sky'?'selected':'' ?> data-hex="#0ea5e9">Sky</option>
              <option value="violet" <?= $preset==='violet'?'selected':'' ?> data-hex="#8b5cf6">Violet</option>
              <option value="teal" <?= $preset==='teal'?'selected':'' ?> data-hex="#14b8a6">Teal</option>
            </select>

            <div class="grid gap-3 md:grid-cols-[1fr,160px] md:items-end">
              <div class="grid gap-2">
                <label class="text-sm text-gray-300">Accent colour (HEX)</label>
                <input id="accent_hex" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3"
                  value="<?= h($s['theme']['accent_hex'] ?? '#4f46e5') ?>">
                <div class="text-xs text-gray-400">Example: #4f46e5</div>
              </div>

              <div class="grid gap-2">
                <label class="text-sm text-gray-300">Picker</label>
                <input id="accent_picker" type="color"
                  class="h-[52px] w-full rounded-2xl border border-white/10 bg-gray-950/40 px-3"
                  value="<?= h($s['theme']['accent_hex'] ?? '#4f46e5') ?>">
              </div>
            </div>

            <div class="grid gap-3 md:grid-cols-[1fr,160px] md:items-end mt-4">
              <div class="grid gap-2">
                <label class="text-sm text-gray-300">Page background (optional HEX)</label>
                <input id="bg_hex" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3"
                  value="<?= h($s['theme']['bg_hex'] ?? '') ?>" placeholder="#FCFCFB">
                <div class="text-xs text-gray-400">Leave blank to use the selected mode.</div>
              </div>

              <div class="grid gap-2">
                <label class="text-sm text-gray-300">Picker</label>
                <input id="bg_picker" type="color"
                  class="h-[52px] w-full rounded-2xl border border-white/10 bg-gray-950/40 px-3"
                  value="<?= h(($s['theme']['bg_hex'] ?? '') ?: '#FCFCFB') ?>">
              </div>
            </div>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Assets</div>
            <div class="flex flex-wrap gap-2">
              <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
                Upload logo
                <input type="file" accept="image/*,.svg" class="hidden" onchange="uploadAsset(this.files[0],'logo'); this.value='';">
              </label>
              <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
                Upload OG image
                <input type="file" accept="image/*" class="hidden" onchange="uploadAsset(this.files[0],'og'); this.value='';">
              </label>
              <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
                Upload favicon
                <input type="file" accept="image/*" class="hidden" onchange="uploadAsset(this.files[0],'favicon'); this.value='';">
              </label>
            </div>

            <div class="grid gap-2 text-sm text-gray-300">
              <div>Logo path: <span class="font-mono"><?= h($assets['logo_path'] ?? '') ?></span></div>
              <div>OG image path: <span class="font-mono"><?= h($assets['og_image_path'] ?? '') ?></span></div>
              <div>Favicon path: <span class="font-mono"><?= h($assets['favicon_path'] ?? '') ?></span></div>
            </div>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Socials</div>
            <input id="instagram" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Instagram URL" value="<?= h($social['instagram'] ?? '') ?>">
            <input id="facebook" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Facebook URL" value="<?= h($social['facebook'] ?? '') ?>">
            <input id="tiktok" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="TikTok URL" value="<?= h($social['tiktok'] ?? '') ?>">
            <input id="linkedin" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="LinkedIn URL" value="<?= h($social['linkedin'] ?? '') ?>">
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Footer credit</div>
            <label class="flex items-center gap-2 text-sm text-gray-300">
              <input id="credit_enabled" type="checkbox" class="accent-indigo-500" <?= !empty($s['footer_credit_enabled']) ? 'checked' : '' ?>>
              Show footer credit text
            </label>
            <input id="credit_text" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($s['footer_credit_text'] ?? '') ?>">
          </div>
        </div>

      <?php elseif ($tab === 'blog'): ?>
        <?php $blog = hb5_load_blog(); $posts = $blog['posts'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Blog</h1>
          <div class="flex items-center gap-2">
            <button onclick="addPost()" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Add post</button>
            <button onclick="saveBlog()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
          </div>
        </div>
        <p class="mt-2 text-sm text-gray-300">Drag posts to reorder. Draft posts aren’t public.</p>

        <div id="postsWrap" class="mt-6 grid gap-3">
          <?php foreach ($posts as $p): ?>
            <div class="hb5_post rounded-3xl border border-white/10 bg-white/5 p-4">
              <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 font-semibold">
                  <span class="drag-handle cursor-grab select-none opacity-80">⠿</span>
                  <span>Post</span>
                </div>
                <button onclick="this.closest('.hb5_post').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
              </div>

              <div class="mt-3 grid gap-3">
                <div class="grid gap-2 md:grid-cols-2">
                  <div class="grid gap-2">
                    <label class="text-xs text-gray-300">Title</label>
                    <input class="post_title rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h((string)($p['title'] ?? '')) ?>">
                  </div>
                  <div class="grid gap-2">
                    <label class="text-xs text-gray-300">Slug (URL)</label>
                    <input class="post_slug rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h((string)($p['slug'] ?? '')) ?>" placeholder="e.g. my-first-post">
                  </div>
                </div>

                <div class="grid gap-2 md:grid-cols-3">
                  <div class="grid gap-2">
                    <label class="text-xs text-gray-300">Date (YYYY-MM-DD)</label>
                    <input class="post_date rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h((string)($p['date'] ?? '')) ?>">
                  </div>
                  <div class="grid gap-2 md:col-span-2">
                    <label class="text-xs text-gray-300">Tags (comma separated)</label>
                    <input class="post_tags rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h(is_array($p['tags'] ?? null) ? implode(', ', $p['tags']) : (string)($p['tags'] ?? '')) ?>">
                  </div>
                </div>

                <div class="grid gap-2">
                  <label class="text-xs text-gray-300">Excerpt</label>
                  <textarea class="post_excerpt rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="2"><?= h((string)($p['excerpt'] ?? '')) ?></textarea>
                </div>

                <div class="grid gap-2">
                  <label class="text-xs text-gray-300">Content (plain text)</label>
                  <textarea class="post_content rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="8"><?= h((string)($p['content'] ?? '')) ?></textarea>
                  <div class="text-xs text-gray-400">Write like you speak. Short paragraphs. Concrete examples.</div>
                </div>

                <div class="flex items-center justify-between">
                  <label class="inline-flex items-center gap-2 text-sm text-gray-200">
                    <input type="checkbox" class="post_published" <?= !empty($p['published']) ? 'checked' : '' ?>>
                    Published
                  </label>
                  <a class="text-sm text-indigo-200 hover:text-indigo-100" target="_blank" href="/post.php?slug=<?= h((string)($p['slug'] ?? '')) ?>">Preview →</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'seo'): ?>
        <?php $seo = $site['page_seo'] ?? []; $g = $site['seo'] ?? []; ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">SEO</h1>
          <button onclick="saveSeo()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Save</button>
        </div>

        <div class="mt-6 grid gap-4">
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
            <div class="font-semibold">Global defaults</div>
            <input id="seo_suffix" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Title suffix" value="<?= h($g['default_title_suffix'] ?? '') ?>">
            <textarea id="seo_default_desc" rows="3" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Default description"><?= h($g['default_description'] ?? '') ?></textarea>
          </div>

          <?php foreach (['home','about','services','gallery','contact'] as $k): ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 grid gap-3">
              <div class="font-semibold"><?= h(ucfirst($k)) ?></div>
              <input class="seo_title rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" data-k="<?= h($k) ?>" placeholder="Title" value="<?= h($seo[$k]['title'] ?? '') ?>">
              <textarea class="seo_desc rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="2" data-k="<?= h($k) ?>" placeholder="Description"><?= h($seo[$k]['description'] ?? '') ?></textarea>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'leads'): ?>
        <?php
          $leads = hb5_json_read(HB5_DATA . '/leads.json', []);
          $leads = array_reverse($leads);
        ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Leads</h1>
          <button onclick="clearLeads()" class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-2 hover:bg-red-500/15 text-red-100">Clear all</button>
        </div>

        <div class="mt-6 grid gap-3">
          <?php if (!$leads): ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 text-gray-300">No messages yet.</div>
          <?php endif; ?>

          <?php foreach ($leads as $l): ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
              <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="font-semibold"><?= h($l['name'] ?? '') ?> <span class="text-sm text-gray-400 font-normal">(<?= h($l['email'] ?? '') ?>)</span></div>
                <div class="text-xs text-gray-400"><?= h($l['ts'] ?? '') ?> • <?= h($l['ip'] ?? '') ?></div>
              </div>
              <p class="mt-3 text-gray-200 whitespace-pre-line"><?= h($l['message'] ?? '') ?></p>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'security'): ?>
        <?php $admin = hb5_admin_user(); ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Security</h1>
        </div>

        <div class="mt-6 grid gap-4">
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Change admin login</div>
            <p class="mt-2 text-sm text-gray-300">Recommended: change the default password immediately.</p>

            <div class="mt-4 grid gap-3">
              <label class="text-sm text-gray-300">Admin email</label>
              <input id="admin_email" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="<?= h($admin['email'] ?? '') ?>">
              <label class="text-sm text-gray-300">New password</label>
              <input id="admin_pass" type="password" class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Enter a strong password">
              <button onclick="saveAdminLogin()" class="rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Update admin login</button>
            </div>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Quick hardening</div>
            <ul class="mt-3 list-disc pl-5 text-sm text-gray-300 space-y-1">
              <li>Ensure <span class="font-mono">/data</span> is not web-accessible (included .htaccess).</li>
              <li>Only allow images in <span class="font-mono">/uploads</span> (included .htaccess).</li>
              <li>Keep PHP updated and use HTTPS.</li>
            </ul>
          </div>
        </div>

      <?php elseif ($tab === 'media'): ?>
        <?php
          $files = [];
          if (is_dir(HB5_UPLOADS)) {
            $dir = opendir(HB5_UPLOADS);
            while ($dir && false !== ($f = readdir($dir))) {
              if ($f === '.' || $f === '..' || $f === '.htaccess') continue;
              $p = HB5_UPLOADS . '/' . $f;
              if (is_file($p)) $files[] = $f;
            }
            if ($dir) closedir($dir);
          }
          sort($files);
        ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Media Library</h1>
          <label class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10 cursor-pointer">
            Upload image
            <input type="file" accept="image/*,.svg,.ico" class="hidden" onchange="uploadAsset(this.files[0],'gallery'); this.value='';">
          </label>
        </div>
        <p class="mt-2 text-sm text-gray-300">Files in <span class="font-mono">/uploads</span>. Copy paths for gallery/logo/OG, or delete unused files.</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
          <?php foreach ($files as $f): $url = '/uploads/' . $f; ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 overflow-hidden">
              <div class="aspect-[4/3] bg-black/20">
                <img src="<?= h($url) ?>" alt="" class="h-full w-full object-cover">
              </div>
              <div class="p-4 text-sm">
                <div class="font-mono text-xs text-gray-300 break-all"><?= h($url) ?></div>
                <div class="mt-3 flex items-center justify-between gap-2">
                  <button onclick="navigator.clipboard.writeText('<?= h($url) ?>'); toast('Copied path');"
                    class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 hover:bg-white/10 text-xs">Copy</button>
                  <button onclick="deleteUpload('<?= h($f) ?>')"
                    class="rounded-xl border border-red-500/30 bg-red-500/10 px-3 py-2 hover:bg-red-500/15 text-xs text-red-100">Delete</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php elseif ($tab === 'backup'): ?>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Backup</h1>
        </div>

        <div class="mt-6 grid gap-4">
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Export</div>
            <p class="mt-2 text-sm text-gray-300">Download a zip of your <span class="font-mono">data/*.json</span> files.</p>
            <a href="/admin/export.php" class="mt-4 inline-flex rounded-2xl bg-indigo-600 px-4 py-2 font-semibold hover:opacity-90">Download backup zip</a>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <div class="font-semibold">Reset starter content</div>
            <p class="mt-2 text-sm text-gray-300">Restores the original starter text (uploads stay).</p>
            <button onclick="resetDemo()" class="mt-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 hover:bg-white/10">Reset</button>
          </div>
        </div>
      <?php endif; ?>
    </section>
  </div>

<script>
  const csrf = <?= json_encode(hb5_csrf_token()) ?>;

  // --- Palette + picker sync ---
  const bgInput = document.getElementById('bg_hex');
  const bgPicker = document.getElementById('bg_picker');
  const hexInput = document.getElementById('accent_hex');
  const picker = document.getElementById('accent_picker');
  const palette = document.getElementById('palette');

  function normalizeHex(v) {
    v = (v || '').trim();
    if (!v.startsWith('#')) v = '#' + v;
    if (!/^#[0-9A-Fa-f]{6}$/.test(v)) return null;
    return v.toUpperCase();
  }

  if (bgInput && bgPicker) {
    bgInput.addEventListener('input', () => {
      const n = normalizeHex(bgInput.value);
      if (n) bgPicker.value = n;
    });
    bgPicker.addEventListener('input', () => {
      bgInput.value = bgPicker.value.toUpperCase();
    });
  }

  if (hexInput && picker) {
    hexInput.addEventListener('input', () => {
      const n = normalizeHex(hexInput.value);
      if (n) picker.value = n;
    });
    picker.addEventListener('input', () => {
      hexInput.value = picker.value.toUpperCase();
      if (palette) palette.value = 'custom';
    });
  }

  if (palette && hexInput && picker) {
    palette.addEventListener('change', () => {
      const opt = palette.options[palette.selectedIndex];
      const hex = opt?.dataset?.hex || '';
      if (hex) {
        hexInput.value = hex.toUpperCase();
        picker.value = hex.toUpperCase();
      }
    });
  }

  // --- Drag & drop ordering (SortableJS) ---
  function makeSortable(id) {
    const el = document.getElementById(id);
    if (!el || typeof Sortable === 'undefined') return;
    Sortable.create(el, {
      animation: 150,
      handle: '.drag-handle',
      ghostClass: 'opacity-40'
    });
  }

  makeSortable('homeFeaturesWrap');
  makeSortable('homeSectionsWrap');
  makeSortable('slidesWrap');
  makeSortable('servicesWrap');
  makeSortable('galleryWrap');
  makeSortable('postsWrap');

  function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.remove('hidden');
    setTimeout(()=>t.classList.add('hidden'), 2500);
  }

  async function post(action, payload) {
    const res = await fetch('/admin/save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ csrf, action, ...payload })
    });
    return res.json();
  }

  async function savePages() {
    const features = [...document.querySelectorAll('#homeFeaturesWrap .hb5_feat')].map(el => ({
      title: el.querySelector('.hb5_feat_title').value,
      text: el.querySelector('.hb5_feat_text').value
    })).filter(x => x.title.trim() !== '');

    const sections = [...document.querySelectorAll('#homeSectionsWrap .hb5_sec')].map(el => ({
      heading: el.querySelector('.hb5_sec_heading').value,
      text: el.querySelector('.hb5_sec_text').value
    })).filter(x => x.heading.trim() !== '');

    const out = await post('pages', {
      home: {
        hero_title: document.getElementById('home_hero_title').value,
        hero_subtitle: document.getElementById('home_hero_subtitle').value,
        cta_primary_text: document.getElementById('home_cta_primary_text').value,
        cta_primary_href: document.getElementById('home_cta_primary_href').value,
        cta_secondary_text: document.getElementById('home_cta_secondary_text').value,
        cta_secondary_href: document.getElementById('home_cta_secondary_href').value,
        feature_blocks: features,
        sections: sections,
        slideshow: {
          enabled: !!document.getElementById('home_slideshow_enabled')?.checked,
          interval: (document.getElementById('home_slideshow_interval')?.value || '4').trim(),
          height: (document.getElementById('home_slideshow_height')?.value || '360').trim(),
          images: [...document.querySelectorAll('#slidesWrap .slide_row .slide_src')].map(i=>i.value.trim()).filter(Boolean)
        }
      },
      about: {
        headline: document.getElementById('about_headline').value,
        body: document.getElementById('about_body').value
      },
      services: {
        headline: document.getElementById('services_headline').value
      },
      gallery: {
        headline: document.getElementById('gallery_headline').value
      },
      contact: {
        headline: document.getElementById('contact_headline').value,
        intro: document.getElementById('contact_intro').value,
        form_success_message: document.getElementById('contact_success').value
      }
    });

    if (out.ok) toast('Saved!');
    else alert(out.error || 'Save failed');
  }

  function addHomeFeature() {
    const wrap = document.getElementById('homeFeaturesWrap');
    const card = document.createElement('div');
    card.className = 'hb5_feat rounded-3xl border border-white/10 bg-gray-950/40 p-4';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-300"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Feature</div>
        <button onclick="this.closest('.hb5_feat').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
      </div>
      <div class="mt-3 grid gap-2">
        <input class="hb5_feat_title rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" placeholder="Title" value="">
        <textarea class="hb5_feat_text rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" rows="2" placeholder="Text"></textarea>
      </div>
    `;
    wrap.appendChild(card);
  }

  function addSlide() {
    const wrap = document.getElementById('slidesWrap');
    if (!wrap) return;
    const row = document.createElement('div');
    row.className = 'slide_row flex items-center gap-2 rounded-2xl border border-white/10 bg-gray-950/40 p-2';
    row.innerHTML = `
      <span class="drag-handle cursor-grab select-none opacity-80 px-2">⠿</span>
      <input class="slide_src flex-1 rounded-xl border border-white/10 bg-gray-950/50 px-3 py-2 text-sm" value="" placeholder="/uploads/slide.jpg">
      <button onclick="this.closest('.slide_row').remove()" class="px-3 py-2 text-sm text-red-200 hover:text-red-100">Remove</button>
    `;
    wrap.prepend(row);
  }

  function addHomeSection() {
    const wrap = document.getElementById('homeSectionsWrap');
    const card = document.createElement('div');
    card.className = 'hb5_sec rounded-3xl border border-white/10 bg-gray-950/40 p-4';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-300"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Section</div>
        <button onclick="this.closest('.hb5_sec').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
      </div>
      <div class="mt-3 grid gap-2">
        <input class="hb5_sec_heading rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" placeholder="Heading" value="">
        <textarea class="hb5_sec_text rounded-2xl border border-white/10 bg-gray-950/50 px-4 py-3" rows="3" placeholder="Text"></textarea>
      </div>
    `;
    wrap.appendChild(card);
  }

  async function deleteUpload(filename) {
    if (!confirm('Delete this file?')) return;
    const out = await post('delete_upload', { filename });
    if (out.ok) { toast('Deleted'); setTimeout(()=>location.reload(), 600); }
    else alert(out.error || 'Delete failed');
  }

  function slugify(str) {
    return (str || '')
      .toString()
      .toLowerCase()
      .trim()
      .replace(/['"]/g,'')
      .replace(/[^a-z0-9]+/g,'-')
      .replace(/^-+|-+$/g,'');
  }

  function addPost() {
    const wrap = document.getElementById('postsWrap');
    const card = document.createElement('div');
    card.className = 'hb5_post rounded-3xl border border-white/10 bg-white/5 p-4';
    const today = new Date().toISOString().slice(0,10);
    card.innerHTML = `
      <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 font-semibold">
          <span class="drag-handle cursor-grab select-none opacity-80">⠿</span>
          <span>Post</span>
        </div>
        <button onclick="this.closest('.hb5_post').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
      </div>
      <div class="mt-3 grid gap-3">
        <div class="grid gap-2 md:grid-cols-2">
          <div class="grid gap-2">
            <label class="text-xs text-gray-300">Title</label>
            <input class="post_title rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="">
          </div>
          <div class="grid gap-2">
            <label class="text-xs text-gray-300">Slug (URL)</label>
            <input class="post_slug rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="" placeholder="e.g. my-first-post">
          </div>
        </div>
        <div class="grid gap-2 md:grid-cols-3">
          <div class="grid gap-2">
            <label class="text-xs text-gray-300">Date (YYYY-MM-DD)</label>
            <input class="post_date rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="${today}">
          </div>
          <div class="grid gap-2 md:col-span-2">
            <label class="text-xs text-gray-300">Tags (comma separated)</label>
            <input class="post_tags rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" value="">
          </div>
        </div>
        <div class="grid gap-2">
          <label class="text-xs text-gray-300">Excerpt</label>
          <textarea class="post_excerpt rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="2"></textarea>
        </div>
        <div class="grid gap-2">
          <label class="text-xs text-gray-300">Content (plain text)</label>
          <textarea class="post_content rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="8"></textarea>
        </div>
        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-gray-200">
            <input type="checkbox" class="post_published">
            Published
          </label>
          <span class="text-xs text-gray-400">Save to preview</span>
        </div>
      </div>
    `;
    wrap.prepend(card);

    const title = card.querySelector('.post_title');
    const slug = card.querySelector('.post_slug');
    title.addEventListener('blur', () => {
      if (!slug.value.trim()) slug.value = slugify(title.value);
    });
  }

  async function saveBlog() {
    const posts = [...document.querySelectorAll('#postsWrap .hb5_post')].map(el => {
      const title = el.querySelector('.post_title')?.value || '';
      const slug = (el.querySelector('.post_slug')?.value || '').trim() || slugify(title);
      const tagsRaw = el.querySelector('.post_tags')?.value || '';
      const tags = tagsRaw.split(',').map(s=>s.trim()).filter(Boolean);
      return {
        id: el.dataset.id || ('p_' + Math.random().toString(16).slice(2)),
        title: title,
        slug: slug,
        date: (el.querySelector('.post_date')?.value || '').trim(),
        excerpt: el.querySelector('.post_excerpt')?.value || '',
        content: el.querySelector('.post_content')?.value || '',
        tags: tags,
        published: !!el.querySelector('.post_published')?.checked
      };
    }).filter(p => (p.title || '').trim() !== '');

    const seen = new Set();
    for (const p of posts) {
      if (!p.slug) { alert('Each post needs a slug.'); return; }
      if (seen.has(p.slug)) { alert('Duplicate slug: ' + p.slug); return; }
      seen.add(p.slug);
    }

    const out = await post('blog', { posts });
    if (out.ok) toast('Saved!');
    else alert(out.error || 'Save failed');
  }

  async function resetDemo() {
    if (!confirm('Reset starter content? This overwrites your site text (uploads stay).')) return;
    const out = await post('reset_demo', {});
    if (out.ok) { toast('Reset'); setTimeout(()=>location.reload(), 600); }
    else alert(out.error || 'Reset failed');
  }

  function addService() {
    const wrap = document.getElementById('servicesWrap');
    const card = document.createElement('div');
    // FIX: give outer wrapper a class for reliable removing
    card.className = 'svc_card rounded-3xl border border-white/10 bg-white/5 p-5';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 font-semibold"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Service</div>
        <button onclick="this.closest('.svc_card').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
      </div>
      <div class="svc mt-4 grid gap-3">
        <input class="svc_title rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Title" value="">
        <input class="svc_price rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Price" value="">
        <textarea class="svc_text rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" rows="3" placeholder="Description"></textarea>
      </div>
    `;
    wrap.appendChild(card);
  }

  async function saveServices() {
    const cards = [...document.querySelectorAll('#servicesWrap .svc')];
    const items = cards.map(c => ({
      title: c.querySelector('.svc_title').value,
      price: c.querySelector('.svc_price').value,
      text: c.querySelector('.svc_text').value,
    })).filter(x => x.title.trim() !== '');

    const out = await post('services', { items });
    if (out.ok) toast('Saved!');
    else alert(out.error || 'Save failed');
  }

  function addGallery() {
    const wrap = document.getElementById('galleryWrap');
    const card = document.createElement('div');
    card.className = 'gal rounded-3xl border border-white/10 bg-white/5 p-5';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 font-semibold"><span class="drag-handle cursor-grab select-none opacity-80">⠿</span> Item</div>
        <button onclick="this.closest('.gal').remove()" class="text-sm text-red-200 hover:text-red-100">Remove</button>
      </div>
      <div class="mt-4 grid gap-3">
        <input class="gal_image rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="/uploads/your-image.jpg" value="">
        <input class="gal_caption rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3" placeholder="Caption" value="">
      </div>
    `;
    wrap.appendChild(card);
  }

  async function saveGallery() {
    const cards = [...document.querySelectorAll('#galleryWrap .gal')];
    const items = cards.map(c => ({
      image: c.querySelector('.gal_image').value,
      caption: c.querySelector('.gal_caption').value,
    })).filter(x => x.image.trim() !== '');

    const out = await post('gallery', { items });
    if (out.ok) toast('Saved!');
    else alert(out.error || 'Save failed');
  }

  async function saveSettings() {
    const out = await post('settings', {
      brand_name: document.getElementById('brand_name').value,
      tagline: document.getElementById('tagline').value,
      phone: document.getElementById('phone').value,
      email: document.getElementById('email').value,
      address: document.getElementById('address').value,
      google_maps_embed_url: document.getElementById('maps').value,
      accent_hex: document.getElementById('accent_hex').value,
      bg_hex: (document.getElementById('bg_hex') ? document.getElementById('bg_hex').value : ''),
      palette: document.getElementById('palette') ? document.getElementById('palette').value : 'custom',
      mode: document.getElementById('theme_mode') ? document.getElementById('theme_mode').value : 'light',
      socials: {
        instagram: document.getElementById('instagram').value,
        facebook: document.getElementById('facebook').value,
        tiktok: document.getElementById('tiktok').value,
        linkedin: document.getElementById('linkedin').value
      },
      credit_enabled: document.getElementById('credit_enabled').checked,
      credit_text: document.getElementById('credit_text').value
    });

    if (out.ok) { toast('Saved!'); setTimeout(()=>location.reload(), 600); }
    else alert(out.error || 'Save failed');
  }

  async function saveSeo() {
    const titles = [...document.querySelectorAll('.seo_title')];
    const descs  = [...document.querySelectorAll('.seo_desc')];
    const page_seo = {};

    titles.forEach(el => {
      const k = el.dataset.k;
      page_seo[k] = page_seo[k] || {};
      page_seo[k].title = el.value;
    });
    descs.forEach(el => {
      const k = el.dataset.k;
      page_seo[k] = page_seo[k] || {};
      page_seo[k].description = el.value;
    });

    const out = await post('seo', {
      default_title_suffix: document.getElementById('seo_suffix').value,
      default_description: document.getElementById('seo_default_desc').value,
      page_seo
    });

    if (out.ok) toast('Saved!');
    else alert(out.error || 'Save failed');
  }

  async function clearLeads() {
    if (!confirm('Clear ALL leads?')) return;
    const out = await post('clear_leads', {});
    if (out.ok) { toast('Cleared'); setTimeout(()=>location.reload(), 600); }
    else alert(out.error || 'Failed');
  }

  async function uploadAsset(file, kind) {
    if (!file) return;
    const fd = new FormData();
    fd.append('csrf', csrf);
    fd.append('kind', kind);
    fd.append('file', file);

    const res = await fetch('/admin/upload.php', { method:'POST', body: fd });
    const out = await res.json();

    if (out.ok) { toast('Uploaded'); setTimeout(()=>location.reload(), 600); }
    else alert(out.error || 'Upload failed');
  }

  async function uploadGalleryImage(file) {
    if (!file) return;
    const fd = new FormData();
    fd.append('csrf', csrf);
    fd.append('kind', 'gallery');
    fd.append('file', file);

    const res = await fetch('/admin/upload.php', { method: 'POST', body: fd });
    const out = await res.json();

    if (out.ok) {
      toast('Uploaded: ' + out.path);
      addGallery();
      const last = [...document.querySelectorAll('#galleryWrap .gal_image')].pop();
      if (last) last.value = out.path;
    } else {
      alert(out.error || 'Upload failed');
    }
  }

  async function uploadSlideImage(file) {
    if (!file) return;

    const fd = new FormData();
    fd.append('csrf', csrf);

    // This "kind" is mainly semantic; upload.php will still save to /uploads and return {path}.
    fd.append('kind', 'slideshow');
    fd.append('file', file);

    const res = await fetch('/admin/upload.php', { method: 'POST', body: fd });
    const out = await res.json();

    if (!out.ok) {
      alert(out.error || 'Upload failed');
      return;
    }

    const wrap = document.getElementById('slidesWrap');
    if (!wrap) {
      toast('Uploaded: ' + (out.path || ''));
      return;
    }

    addSlide();
    const first = wrap.querySelector('.slide_row .slide_src');
    if (first) first.value = out.path || '';

    toast('Uploaded');
  }

  async function saveAdminLogin() {
    const email = document.getElementById('admin_email').value;
    const pass  = document.getElementById('admin_pass').value;
    if (!pass || pass.length < 10) return alert('Use a stronger password (10+ characters).');

    const out = await post('admin_login', { email, password: pass });
    if (out.ok) { toast('Updated'); document.getElementById('admin_pass').value = ''; }
    else alert(out.error || 'Failed');
  }
</script>
</body>
</html>

