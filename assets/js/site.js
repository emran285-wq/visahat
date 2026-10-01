document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.site-nav');
  if (toggle && nav) {
    const close = () => { nav.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); };
    toggle.addEventListener('click', () => { const expanded = toggle.getAttribute('aria-expanded') === 'true'; toggle.setAttribute('aria-expanded', String(!expanded)); nav.classList.toggle('open', !expanded); });
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', close));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && nav.classList.contains('open')) { close(); toggle.focus(); } });
  }

  const formMode = document.body.dataset.formMode || 'demo';

  const consultationForm = document.querySelector('#consultation-form');
  if (consultationForm) {
    const selected = new URLSearchParams(window.location.search).get('service');
    const serviceInput = consultationForm.elements.service;
    const services = {'skilled-worker':'Skilled Worker Visa','visitor':'Visitor Visa','temporary-work':'Temporary Work Visa','student':'Student Visa'};
    if (selected && services[selected]) serviceInput.value = services[selected];
    consultationForm.addEventListener('submit', (event) => {
      event.preventDefault(); const status = consultationForm.querySelector('.form-status'); status.className = 'form-status';
      if (formMode !== 'demo') { status.textContent = 'Consultation requests are not available at the moment.'; return; }
      if (!consultationForm.checkValidity()) { status.textContent = 'Please complete the required fields before previewing your request.'; consultationForm.reportValidity(); return; }
      status.className = 'form-status success'; status.textContent = 'Preview ready. Demo form — requests are not sent.';
    });
  }

  const enquiryForm = document.querySelector('#enquiry-form');
  if (enquiryForm) {
    enquiryForm.addEventListener('submit', (event) => {
      event.preventDefault(); const status = enquiryForm.querySelector('.form-status'); status.className = 'form-status';
      if (formMode !== 'demo') { status.textContent = 'Enquiries are not available at the moment.'; return; }
      if (!enquiryForm.checkValidity()) { status.textContent = 'Please complete the required fields before previewing your enquiry.'; enquiryForm.reportValidity(); return; }
      status.className = 'form-status success'; status.textContent = 'Preview ready. Demo form — enquiries are not sent.';
    });
  }
});