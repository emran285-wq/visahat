<?php
declare(strict_types=1); require __DIR__ . '/config.php'; $assistance = require __DIR__ . '/data/assistance.php';
$pageTitle = 'Our Services | Visa Hat';
$metaDescription = 'What Visa Hat actually provides: personal consultation, document guidance, process explanation, and follow-up support.';
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
<section class="page-hero service-intro-hero"><div class="container split-hero"><div><p class="eyebrow">Our services</p><h1>The support Visa Hat <strong>actually provides.</strong></h1><p>Visa Hat is a consultation service, not a law firm or a document-submission agency. Here is exactly what that means in practice, service by service.</p><nav class="jump-nav" aria-label="Jump to a service"><?php foreach ($assistance as $slug => $item): ?><a href="#<?= e($slug) ?>"><?= e($item['title']) ?></a><?php endforeach; ?></nav></div><div class="intro-art" aria-hidden="true"><svg viewBox="0 0 260 260"><rect x="46" y="34" width="150" height="192" rx="10" fill="#fff" stroke="#0867e8" stroke-width="7"/><path d="m70 92 3 26h20l6-52h20l6 52h20l3-26" fill="none" stroke="#149d98" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/><path d="m66 150 12 12 22-22M66 182h20" stroke="#ef7182" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/><circle cx="196" cy="196" r="26" fill="#efa833"/><path d="M186 196h20M196 186v20" stroke="#fff" stroke-width="5" stroke-linecap="round"/></svg></div></div></section>

<section class="section content-note"><div class="container compact-copy"><p><b>Where things stand:</b> these four services are what we're currently offering. We haven't nailed down pricing or office hours yet, and we'll add more services here once they're confirmed.</p></div></section>

<?php foreach ($assistance as $slug => $item): ?>
<section class="section service-block" id="<?= e($slug) ?>"><div class="container service-block-grid">
  <div class="service-block-head"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><use href="/assets/images/service-icons.svg#<?= e($item['icon']) ?>"></use></svg></div><h2><?= e($item['title']) ?></h2><p><?= e($item['summary']) ?></p><p class="who-for"><b>Who it's for:</b> <?= e($item['who']) ?></p></div>
  <div class="service-block-body">
    <div class="scope-columns">
      <div class="scope-included"><h3>Included</h3><ul><?php foreach ($item['included'] as $line): ?><li><?= e($line) ?></li><?php endforeach; ?></ul></div>
      <div class="scope-excluded"><h3>Not included</h3><ul><?php foreach ($item['excluded'] as $line): ?><li><?= e($line) ?></li><?php endforeach; ?></ul></div>
    </div>
    <div class="service-meta-row">
      <div><h3>What we need from you</h3><ul class="capability-list"><?php foreach ($item['info_needed'] as $line): ?><li><?= e($line) ?></li><?php endforeach; ?></ul></div>
      <div><h3>What happens</h3><ol class="mini-steps"><?php foreach ($item['process'] as $line): ?><li><?= e($line) ?></li><?php endforeach; ?></ol></div>
    </div>
    <p class="receive-line"><b>What you'll receive:</b> <?= e($item['receive']) ?></p>
    <a class="btn btn-outline" href="/consultation">Request this consultation <span aria-hidden="true">→</span></a>
  </div>
</div></section>
<?php endforeach; ?>

<section class="section faq-section"><div class="container faq-grid"><div><p class="eyebrow">Common questions</p><h2>Realistic expectations, answered plainly.</h2><p>These answers explain what this service can and cannot do.</p></div><div class="faq-list"><details><summary>Does Visa Hat submit applications for me?</summary><p>No. Consultation and guidance are provided; submitting or managing an application is outside the current scope.</p></details><details><summary>Can Visa Hat guarantee a visa outcome?</summary><p>No. Requirements and decisions vary by destination and individual circumstances.</p></details><details><summary>Does the consultation form send my request?</summary><p>Not yet. It is currently a browser-only preview and does not transmit or store requests.</p></details></div></div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

