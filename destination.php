<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
$destinations = require __DIR__ . '/data/destinations.php';

$slug = (string) ($_GET['destination'] ?? '');
if (!isset($destinations[$slug])) { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$d = $destinations[$slug];

// Honest, generic region framing — no destination-specific visa facts are asserted here.
// contentStatus is 'enquiry-only' for every destination right now, so every page stays noindex
// and out of the sitemap until the business supplies a verified sourceLink + reviewedAt date.
$regionIntro = [
    'middle-east' => 'Enquiries about the Gulf and wider Middle East usually relate to work opportunities, family visits, or transit planning.',
    'europe-caucasus' => 'Enquiries across Europe and the Caucasus span short visits, study plans, and longer work moves.',
    'central-asia' => 'Enquiries across Central Asia are often tied to work, study, or family travel plans.',
    'south-asia' => 'Enquiries across South Asia commonly involve work, study, or family-visit planning.',
    'east-southeast-asia' => 'Enquiries across East and Southeast Asia often involve work postings, study plans, or shorter visits.',
    'africa' => 'Enquiries across Africa commonly relate to work opportunities, family visits, or study plans.',
    'north-america' => 'Enquiries about North America commonly relate to work, study, or family-visit planning.',
][$d['region']] ?? 'Enquiries about this destination vary by nationality, purpose, and timeline.';

$typeWord = $d['destinationType'] === 'visa-area' ? 'visa area' : 'destination';
$noindex = !$d['verifiedContent']; // stays true for all 39 destinations until verified content exists

$related = array_filter($destinations, fn($item, $key) => $key !== $slug && $item['region'] === $d['region'], ARRAY_FILTER_USE_BOTH);
uasort($related, fn($a, $b) => strcasecmp($a['displayName'], $b['displayName']));
$related = array_slice($related, 0, 4, true);

$pageTitle = e($d['seo']['title']);
$metaDescription = $d['seo']['description'];
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
<section class="page-hero destination-detail-hero"><div class="container">
  <p class="breadcrumb"><a href="/">Home</a><span>/</span><a href="/visa">Visa</a><span>/</span><a href="/visa/destinations">Destinations</a><span>/</span><?= e($d['displayName']) ?></p>
  <div class="destination-detail-flag"><img src="<?= e($d['flagAsset']) ?>" alt="" width="144" height="96"></div>
  <p class="eyebrow"><?= e($d['regionLabel']) ?><?= $d['destinationType'] === 'visa-area' ? ' · Visa area' : '' ?></p>
  <h1><?= e($d['displayName']) ?> <strong>visa enquiries.</strong></h1>
  <p><?= e($regionIntro) ?> If <?= e($d['displayName']) ?> is part of your plans, we can talk through your circumstances and confirm what we're currently able to help with.</p>
</div></section>

<section class="section"><div class="container detail-grid">
  <article class="detail-copy">
    <h2>What we can currently help with</h2>
    <ul class="capability-list">
      <li>A one-to-one conversation about your travel purpose and general options</li>
      <li>Help understanding what to prepare before you decide on next steps</li>
      <li>Clear, honest next steps based on what we can currently support for your circumstances</li>
    </ul>
    <h3>Why we don't publish specific requirements here</h3>
    <p>Visa requirements depend on your nationality, current residence, travel purpose, and current rules — they are not the same for every visitor to <?= e($d['displayName']) ?>. Rather than publish generic information that might not apply to you, we confirm what's relevant to your situation directly in a consultation.</p>

    <?php if ($related): ?>
      <h3>Related destinations in <?= e($d['regionLabel']) ?></h3>
      <div class="destination-related">
        <?php foreach ($related as $rSlug => $r): ?><a href="<?= e(destination_url($rSlug)) ?>"><?= e($r['displayName']) ?></a><?php endforeach; ?>
      </div>
    <?php endif; ?>
    <p class="text-action"><a href="/visa/destinations">← Back to all destinations</a></p>
  </article>

  <aside class="detail-cta">
    <p class="eyebrow">Next step</p>
    <h2>Enquire about <?= e($d['displayName']) ?></h2>
    <p>Share a little context and we'll confirm what we can currently offer for your circumstances.</p>
    <a class="btn btn-primary" href="/consultation?destination=<?= e(rawurlencode($slug)) ?>">Enquire About <?= e($d['displayName']) ?></a>
  </aside>
</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
