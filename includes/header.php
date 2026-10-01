<?php
$pageTitle = $pageTitle ?? 'Visa Hat | Visa & Immigration Consultants';
$metaDescription = $metaDescription ?? 'Visa consultation guidance for skilled workers, visitors, temporary workers and students.';
$canonical = $canonical ?? url(ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/'));
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta name="robots" content="<?= !empty($noindex) ? 'noindex,nofollow' : 'index,follow' ?>">
    <meta property="og:type" content="website"><meta property="og:site_name" content="Visa Hat">
    <meta property="og:title" content="<?= e($pageTitle) ?>"><meta property="og:description" content="<?= e($metaDescription) ?>"><meta property="og:url" content="<?= e($canonical) ?>">
    <meta name="theme-color" content="#f4f9fd">
    <link rel="icon" href="/assets/images/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/icon-192x192.png?v=2">
    <link rel="icon" type="image/png" sizes="512x512" href="/assets/images/icon-512x512.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png?v=2">
    <link rel="stylesheet" href="/assets/css/style.css?v=1">
</head>
<body data-form-mode="<?= e(FORM_MODE) ?>">
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header">
  <div class="nav-shell">
    <a class="brand" href="/" aria-label="Visa Hat home"><img class="brand-logo" src="/assets/images/logo-full.png?v=1" width="63" height="40" alt=""><span class="brand-divider" aria-hidden="true"></span><span class="brand-tagline">Visa &amp; Immigration Consultants</span></a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav"><span></span><span></span><span></span><b class="sr-only">Open menu</b></button>
    <nav id="site-nav" class="site-nav" aria-label="Main navigation">
      <a href="/" <?= $currentPath === '/' ? 'aria-current="page"' : '' ?>>Home</a>
      <a href="/services" <?= $currentPath === '/services' ? 'aria-current="page"' : '' ?>>Services</a>
      <a href="/about" <?= $currentPath === '/about' ? 'aria-current="page"' : '' ?>>About</a>
      <a href="/visa" <?= str_starts_with($currentPath, '/visa') ? 'aria-current="page"' : '' ?>>Visa</a>
      <a href="/contact" <?= $currentPath === '/contact' ? 'aria-current="page"' : '' ?>>Contact</a>
      <a class="nav-cta" href="/consultation" <?= $currentPath === '/consultation' ? 'aria-current="page"' : '' ?>>Consultation</a>
      <a class="search-link" href="/search" aria-label="Search Visa Hat"><svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="10.75" cy="10.75" r="6.75"></circle><path d="m16 16 4.25 4.25"></path></svg></a>
    </nav>
  </div>
</header>