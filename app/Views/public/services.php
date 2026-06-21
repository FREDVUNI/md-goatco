<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<header>
  <nav class="nav wrap">
    <a href="<?= site_url('/') ?>" class="logo">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco Farm Limited">
      <div class="logo-text">
        <strong>MD Goatco Farm Limited</strong>
        <small>Ethics · Service · Genetics</small>
      </div>
    </a>
    <ul class="nav-links">
      <li><a href="<?= site_url('about') ?>">About</a></li>
      <li><a href="<?= site_url('services') ?>">Services</a></li>
      <li><a href="<?= site_url('goat-banking') ?>">Goat Banking</a></li>
      <li><a href="<?= site_url('contact') ?>">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <a href="<?= site_url('auth/login') ?>" class="btn btn-outline btn-sm">Log in</a>
      <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-sm">Join Goat Banking</a>
    </div>
    <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>
  <div class="nav-mobile-menu wrap">
    <a href="<?= site_url('about') ?>">About</a>
    <a href="<?= site_url('services') ?>">Services</a>
    <a href="<?= site_url('goat-banking') ?>">Goat Banking</a>
    <a href="<?= site_url('contact') ?>">Contact</a>
    <div class="nav-mobile-actions">
      <a href="<?= site_url('auth/login') ?>" class="btn btn-outline">Log in</a>
      <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Join Goat Banking</a>
    </div>
  </div>
</header>

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

<!-- ===== FOOTER ===== -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-logo">
          <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco">
          <strong>MD Goatco Farm Limited</strong>
        </div>
        <p class="foot-tagline">A working goat farm in Mukono, Uganda — rearing, veterinary care and a member-owned Goat Banking program on one digital record.</p>
      </div>
      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="<?= site_url('about') ?>">About us</a></li>
          <li><a href="<?= site_url('services') ?>">Services</a></li>
          <li><a href="<?= site_url('goat-banking') ?>">Goat Banking</a></li>
          <li><a href="<?= site_url('contact') ?>">Contact</a></li>
        </ul>
      </div>
      <div>
        <h5>Portals</h5>
        <ul>
          <li><a href="<?= site_url('auth/login') ?>">Member login</a></li>
          <li><a href="<?= site_url('auth/register') ?>">Apply for Goat Banking</a></li>
          <li><a href="<?= site_url('auth/admin') ?>">Admin portal</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul>
          <li><a href="<?= site_url('privacy-policy') ?>">Privacy Policy</a></li>
          <li><a href="<?= site_url('terms') ?>">Terms &amp; Conditions</a></li>
        </ul>
        <h5 style="margin-top:20px">Address</h5>
        <ul>
          <li>Mukono–Kayunga Road</li>
          <li>Mukono District, Uganda</li>
          <li><a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?= date('Y') ?> MD Goatco Farm Limited · Registered in Uganda</span>
      <span>Ethics · Service · Genetics</span>
    </div>
  </div>
</footer>


<?= $this->endSection() ?>