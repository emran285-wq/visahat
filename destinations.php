<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
$destinations = require __DIR__ . '/data/destinations.php'; // also defines DESTINATION_REGIONS + helper functions

$query = trim((string) ($_GET['q'] ?? ''));
$regionParam = (string) ($_GET['region'] ?? 'all');
$activeRegion = isset(DESTINATION_REGIONS[$regionParam]) ? $regionParam : 'all';

$results = array_filter($destinations, function (array $d) use ($query, $activeRegion) {
    if ($activeRegion !== 'all' && $d['region'] !== $activeRegion) return false;
    return destination_matches($d, $query);
});
uasort($results, fn($a, $b) => strcasecmp($a['displayName'], $b['displayName']));

function region_link(string $regionSlug, string $label, string $activeRegion, string $query): string {
    $params = [];
    if ($regionSlug !== 'all') $params['region'] = $regionSlug;
    if ($query !== '') $params['q'] = $query;
    $href = '/visa/destinations' . ($params ? '?' . http_build_query($params) : '');
    $current = $regionSlug === $activeRegion ? ' aria-current="page"' : '';
    return '<li><a class="region-filter-link" href="' . e($href) . '"' . $current . '>' . e($label) . '</a></li>';
}

$pageTitle = 'Explore Visa Destinations | Visa Hat';
$metaDescription = 'Browse the countries and visa areas Visa Hat can discuss with you, filter by region, and start a consultation for your destination.';
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
<section class="page-hero"><div class="container">
  <p class="breadcrumb"><a href="/">Home</a><span>/</span><a href="/visa">Visa</a><span>/</span>Explore Visa Destinations</p>
  <p class="eyebrow">Visa destinations</p>
  <h1>Explore <strong>Visa Destinations.</strong></h1>
  <p>Browse the countries and visa areas we can talk through with you. Search by name or filter by region — every card links to a short page where you can enquire about that destination specifically.</p>

  <div class="destinations-toolbar">
    <form class="destinations-search" method="get" action="/visa/destinations">
      <label class="sr-only" for="destination-search">Search destinations</label>
      <input id="destination-search" name="q" value="<?= e($query) ?>" placeholder="Search destinations" autocomplete="off">
      <?php if ($activeRegion !== 'all'): ?><input type="hidden" name="region" value="<?= e($activeRegion) ?>"><?php endif; ?>
      <button class="btn btn-primary" type="submit">Search destinations</button>
    </form>
    <ul class="region-filter-list">
      <?= region_link('all', 'All Destinations', $activeRegion, $query) ?>
      <?php foreach (DESTINATION_REGIONS as $slug => $label): ?><?= region_link($slug, $label, $activeRegion, $query) ?><?php endforeach; ?>
    </ul>
  </div>
</div></section>

<section class="section" style="padding-top:44px">
<div class="container" aria-live="polite">
  <div class="destinations-result-bar">
    <p><strong><?= count($results) ?></strong> destination<?= count($results) === 1 ? '' : 's' ?> <?= $query !== '' ? 'for “' . e($query) . '”' : '' ?><?= $activeRegion !== 'all' ? ' in ' . e(DESTINATION_REGIONS[$activeRegion]) : '' ?></p>
  </div>

  <?php if (!$results): ?>
    <div class="destinations-empty">
      <h2>No destinations match your search</h2>
      <p>Try a different spelling, or clear your search and region filter to see every destination.</p>
      <a class="btn btn-outline" href="/visa/destinations">Clear filters</a>
    </div>
  <?php else: ?>
    <div class="destination-grid">
      <?php foreach ($results as $slug => $d): ?>
        <article class="destination-card">
          <div class="destination-flag"><img src="<?= e($d['flagAsset']) ?>" alt="" width="120" height="80" loading="lazy"></div>
          <h2 class="destination-name"><?= e($d['displayName']) ?></h2>
          <p class="destination-region"><?= e($d['regionLabel']) ?><?= $d['destinationType'] === 'visa-area' ? ' · Visa area' : '' ?></p>
          <a class="destination-link" href="<?= e(destination_url($slug)) ?>">View Visa Information</a>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="destinations-invite">
    <div><h2>Not sure which destination fits your plan?</h2><p>Talk it through with a consultant. A short conversation can help you narrow down your destination and next steps.</p></div>
    <a class="btn btn-primary" href="/consultation">Request a consultation</a>
  </div>
</div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
