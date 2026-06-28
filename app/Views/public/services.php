<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>


<!-- PAGE HERO -->
<div class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="<?= site_url('/') ?>">Home</a> › Services</p>
    <h1>From the kraal to your dashboard</h1>
    <p>Four pillars of the farm, all feeding one digital record system.</p>
  </div>
</div>

<section>
  <div class="wrap">
    <div class="photo-strip">
      <img src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=700" alt="Goat farm" loading="lazy">
      <img src="https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=700" alt="Goat close up" loading="lazy">
      <img src="https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=700" alt="Young goat" loading="lazy">
    </div>
    <div class="service-grid">
      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M4 18c0-5 2-9 8-9s8 4 8 9" stroke-linecap="round" />
            <circle cx="12" cy="6" r="2.4" />
            <path d="M2 18h20" stroke-linecap="round" />
          </svg>
        </div>
        <h3>Goat Rearing &amp; Sales</h3>
        <p>Boer, Galla and local crossbreeds raised for meat, breeding stock and dairy — sold farm-direct or through partners. Every animal leaves with a known health and weight history.</p>
        <span class="service-tag">Core herd</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.6A4 4 0 0119 11c0 5.5-7 10-7 10z" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 9v6M9 12h6" stroke-linecap="round" />
          </svg>
        </div>
        <h3>Veterinary Care</h3>
        <p>Routine checkups, vaccinations and treatment, logged against each goat's record by our resident vet team — with health flags raised the moment something needs attention.</p>
        <span class="service-tag">Animal health</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="7" width="18" height="12" rx="2" />
            <path d="M3 11h18M7 15h.01" stroke-linecap="round" />
            <path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke-linecap="round" />
          </svg>
        </div>
        <h3>Goat Banking</h3>
        <p>Buy into the flock, follow your goats by name, and track their growth and value over time from a dashboard built for members — not spreadsheets.</p>
        <span class="service-tag">Member program</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z" stroke-linejoin="round" />
            <path d="M12 12l8-4.5M12 12v9M12 12L4 7.5" stroke-linejoin="round" />
          </svg>
        </div>
        <h3>Farm Consultancy</h3>
        <p>Setting up your own goat enterprise? We advise on breeds, housing, feeding and record-keeping systems — drawing on a decade of our own mistakes and fixes.</p>
        <span class="service-tag">Advisory</span>
      </div>
    </div>
  </div>
</section>

<div class="cta-wrap">
  <div class="wrap">
    <div class="cta">
      <div class="eyebrow">Need one of these?</div>
      <h2>Let's talk about what your farm needs</h2>
      <p>Whether it's buying goats, booking a vet consult, or starting your own herd — get in touch and we'll point you the right way.</p>
      <div class="cta-actions">
        <a href="<?= site_url('contact') ?>" class="btn btn-white">Contact us</a>
        <a href="<?= site_url('goat-banking') ?>" class="btn btn-ghost-white">Learn about Goat Banking</a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>