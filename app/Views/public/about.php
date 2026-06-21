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
    <p class="breadcrumb"><a href="<?= site_url('/') ?>">Home</a> › About</p>
    <h1>A real farm, run the modern way</h1>
    <p>Mukono, Uganda — rearing goats since before it was a spreadsheet problem.</p>
  </div>
</div>

<section>
  <div class="wrap about-grid">
    <div class="about-img">
      <img src="https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=1200"
        alt="Goat at MD Goatco Farm" loading="lazy">
    </div>
    <div class="about-copy">
      <div class="eyebrow">Our story</div>
      <h2>Ten years on the land, now backed by digital records</h2>
      <p>MD Goatco Farm Limited started as a small herd on the Mukono–Kayunga Road, raising Boer, Galla and local crossbreeds for meat, breeding and dairy. As the herd grew, so did the paperwork — vaccination dates, weight logs, member holdings, all living across notebooks and spreadsheets that only one or two people could ever really read.</p>
      <p>This platform is us moving that paper trail online: every goat individually tagged and tracked, every vet visit logged the same day it happens, and every Goat Banking member able to check in on their own animals without picking up the phone.</p>
      <ul class="about-list">
        <li>Individually tagged, tracked goats — not just headcounts</li>
        <li>Vet visits, vaccinations and weight logs on record</li>
        <li>Members can follow their own goats from anywhere</li>
        <li>Role-based dashboards for vets, managers and members</li>
      </ul>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">What we stand for</div>
      <h2>Ethics · Service · Genetics</h2>
      <p>The three words on our crest aren't decoration — they're how decisions get made on the farm.</p>
    </div>
    <div class="why-grid">
      <div class="why-item"><span class="why-num">01</span>
        <div>
          <h3>Ethics</h3>
          <p>Animals are cared for properly whether or not anyone's watching — and every member can see the records that prove it.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">02</span>
        <div>
          <h3>Service</h3>
          <p>To Goat Banking members, to buyers, and to the animals themselves — service comes before shortcuts.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">03</span>
        <div>
          <h3>Genetics</h3>
          <p>Boer, Galla and well-chosen crossbreeds, selected for health and growth, not just headline numbers.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">04</span>
        <div>
          <h3>Transparency</h3>
          <p>If you've invested in a goat, you should be able to see how it's doing — not take our word for it.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="cta-wrap">
  <div class="wrap">
    <div class="cta">
      <div class="eyebrow">Come see for yourself</div>
      <h2>Visit the farm or apply for Goat Banking</h2>
      <p>We're always happy to show prospective members and partners around.</p>
      <div class="cta-actions">
        <a href="<?= site_url('auth/register') ?>" class="btn btn-white">Apply for Goat Banking</a>
        <a href="<?= site_url('contact') ?>" class="btn btn-ghost-white">Plan a visit</a>
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