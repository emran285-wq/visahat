<?php
declare(strict_types=1); require __DIR__ . '/config.php';
$pageTitle = 'Contact | Visa Hat';
$metaDescription = 'How to get in touch with Visa Hat, and what to include in a general enquiry.';
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
<section class="page-hero"><div class="container"><p class="eyebrow">Contact</p><h1>Get in touch with a <strong>general enquiry.</strong></h1><p>Use this page for questions about Visa Hat itself. If you already know you want a visa consultation, the consultation page is the faster route.</p></div></section>

<section class="section contact-page-grid"><div class="container contact-page-inner">
  <aside class="contact-info">
    <h2>Contact status</h2>
    <p class="content-note-inline">Direct phone and email channels are pending business verification and are not published yet. The form on this page is the current way to reach us.</p>
    <h3>What to include</h3>
    <ul class="capability-list"><li>Your name and a way to reach you back</li><li>Whether your question is general, about the website, or something else</li><li>Enough detail for us to understand what you need</li></ul>
    <h3>Hours &amp; location</h3>
    <p class="content-note-inline">Business hours and office address will be published once confirmed.</p>
    <p class="alt-route">Already know what you need? <a href="/consultation">Request a visa consultation</a> instead.</p>
  </aside>
  <div class="form-panel">
    <form id="enquiry-form" novalidate>
      <div class="form-row"><label>Full name<input name="name" autocomplete="name" minlength="2" maxlength="100" required></label><label>Email<input name="email" type="email" autocomplete="email" maxlength="254" required></label></div>
      <label>What is this about?<select name="topic" required><option value="">Select a topic</option><option>General question</option><option>Website feedback</option><option>Something else</option></select></label>
      <label>Message<textarea name="message" maxlength="2000" rows="5" required></textarea></label>
      <label class="checkbox-row"><input name="privacy" type="checkbox" required><span>I agree to the <a href="/privacy">privacy notice</a>.</span></label>
      <p class="form-status" role="status" aria-live="polite"></p>
      <button class="btn btn-primary" type="submit">Preview enquiry</button>
    </form>
  </div>
</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
