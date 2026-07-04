<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $loggedIn = session()->has('user_id'); ?>

<header>
  <nav class="nav wrap">
    <a href="<?= site_url('/') ?>" class="logo">
      <img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco Farm">
      <div class="logo-text">
        <strong>MD Goatco Farm Limited</strong>
        <small>Ethics · Service · Genetics</small>
      </div>
    </a>
    <ul class="nav-links">
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#goat-banking">Goat Banking</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <?php if ($loggedIn): ?>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline btn-sm">My Dashboard</a>
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-primary btn-sm">Sign out</a>
      <?php else: ?>
        <a href="<?= site_url('auth/login') ?>" class="btn btn-outline btn-sm">Log in</a>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-sm">Join Goat Banking</a>
      <?php endif ?>
    </div>
    <button class="nav-toggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
  </nav>
  <div class="nav-mobile-menu wrap">
    <a href="#about">About</a><a href="#services">Services</a>
    <a href="#goat-banking">Goat Banking</a><a href="#contact">Contact</a>
    <div class="nav-mobile-actions">
      <?php if ($loggedIn): ?>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline">My Dashboard</a>
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-primary">Sign out</a>
      <?php else: ?>
        <a href="<?= site_url('auth/login') ?>" class="btn btn-outline">Log in</a>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Join Goat Banking</a>
      <?php endif ?>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="hero" id="home">
  <div class="wrap hero-inner">
    <div class="hero-text">
      <div class="hero-tag">🐐 Goat Banking Program — Uganda</div>
      <h1>Invest in goats.<br><em>Grow your future.</em></h1>
      <p class="hero-sub">MD Goatco Farm manages your goats on our Mukono farm — you earn returns. Ethics, genetics and professional veterinary care, always.</p>
      <div class="hero-cta">
        <?php if ($loggedIn): ?>
          <a href="<?= site_url('dashboard') ?>" class="btn btn-primary btn-lg">My Dashboard →</a>
        <?php else: ?>
          <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-lg">Start Goat Banking</a>
          <a href="#goat-banking" class="btn btn-outline btn-lg">Learn more</a>
        <?php endif ?>
      </div>
    </div>
    <div class="hero-img">
      <div class="hero-card">
        <div class="hc-stat"><strong>Ethics</strong><span>KikoXBoer genetics</span></div>
        <div class="hc-stat"><strong>Service</strong><span>Full-time vet team</span></div>
        <div class="hc-stat"><strong>Genetics</strong><span>High-yield breeds</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="section">
  <div class="wrap">
    <div class="section-label">About Us</div>
    <h2 class="section-heading">A working farm, not a promise</h2>
    <p class="section-sub">MD Goatco Farm Limited is a registered Ugandan company operating a commercial goat farm on the Mukono–Kayunga Road. We breed, rear and sell high-quality goats — and through our Goat Banking program, we give members a stake in that business.</p>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="section section-alt">
  <div class="wrap">
    <div class="section-label">Services</div>
    <h2 class="section-heading">What we do</h2>
    <div class="services-grid">
      <div class="service-card"><div class="sc-icon">🐐</div><h3>Goat Rearing</h3><p>We rear KikoXBoer and pure-bred goats from kids to maturity using best-practice feeding, housing and veterinary care.</p></div>
      <div class="service-card"><div class="sc-icon">🩺</div><h3>Veterinary Care</h3><p>Full-time veterinary team handles vaccinations, health checks, treatment and breeding — every animal, every day.</p></div>
      <div class="service-card"><div class="sc-icon">💰</div><h3>Goat Banking</h3><p>Members invest in the farm by owning goats. We manage everything — you receive returns from sales, offspring and value growth.</p></div>
      <div class="service-card"><div class="sc-icon">📊</div><h3>Farm Consultancy</h3><p>We share our expertise with other farmers — breed selection, nutrition, pen design and herd management best practices.</p></div>
    </div>
  </div>
</section>

<!-- GOAT BANKING -->
<section id="goat-banking" class="section goat-banking-section">
  <div class="wrap">
    <div class="section-label">Goat Banking</div>
    <h2 class="section-heading">How it works</h2>
    <div class="steps-grid">
      <div class="step"><div class="step-num">1</div><h4>Apply online</h4><p>Fill in the application form with your details, upload your ID and choose how many goats you'd like to invest in.</p></div>
      <div class="step"><div class="step-num">2</div><h4>Get approved</h4><p>Our team reviews your application within 2–3 working days and contacts you with the next steps and payment details.</p></div>
      <div class="step"><div class="step-num">3</div><h4>Goats assigned</h4><p>Once payment is confirmed, goats are tagged in your name and you receive portal access to track them in real time.</p></div>
      <div class="step"><div class="step-num">4</div><h4>Earn returns</h4><p>We manage everything — feeding, vet care, breeding. You receive your returns as agreed in your onboarding schedule.</p></div>
    </div>
    <?php if (! $loggedIn): ?>
    <div style="text-align:center;margin-top:40px">
      <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-lg">Apply for Goat Banking →</a>
      <p style="margin-top:12px;font-size:0.84rem;color:var(--slate)">Already applied? <a href="<?= site_url('auth/status') ?>">Check your application status</a></p>
    </div>
    <?php endif ?>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section section-alt">
  <div class="wrap">
    <div class="section-label">Contact</div>
    <h2 class="section-heading">Get in touch</h2>
    <div class="contact-grid">
      <div class="contact-info">
        <div class="contact-card"><div class="contact-icon">📍</div><div><div class="contact-label">Farm address</div><div class="contact-value">Mukono–Kayunga Road, Mukono District, Uganda</div></div></div>
        <div class="contact-card"><div class="contact-icon">📞</div><div><div class="contact-label">Phone / WhatsApp</div><a href="tel:+256700000000" class="contact-value">+256 700 000 000</a></div></div>
        <div class="contact-card"><div class="contact-icon">✉️</div><div><div class="contact-label">Email</div><a href="mailto:hello@mdgoatco.farm" class="contact-value">hello@mdgoatco.farm</a></div></div>
        <div class="contact-card"><div class="contact-icon">🕐</div><div><div class="contact-label">Office hours</div><div class="contact-value">Mon–Fri 8AM–5PM · Sat 8AM–12PM</div></div></div>
      </div>
      <div class="contact-form-card">
        <?php if (session()->has('success')): ?>
        <div class="flash flash-success"><?= esc(session('success')) ?></div>
        <?php endif ?>
        <?= form_open('contact', ['class'=>'contact-form']) ?>
          <?= csrf_field() ?>
          <div class="field-row">
            <div class="field"><label>Your name *</label><input type="text" name="name" value="<?= esc(old('name')) ?>" required placeholder="e.g. Robert Kizito"></div>
            <div class="field"><label>Email *</label><input type="email" name="email" value="<?= esc(old('email')) ?>" required placeholder="you@example.com"></div>
          </div>
          <div class="field">
            <label>Subject *</label>
            <select name="subject" required>
              <option value="">Select…</option>
              <option <?= old('subject')==='Goat Banking enquiry'?'selected':''?>>Goat Banking enquiry</option>
              <option <?= old('subject')==='Goat purchase / sales'?'selected':''?>>Goat purchase / sales</option>
              <option <?= old('subject')==='Farm consultancy'?'selected':''?>>Farm consultancy</option>
              <option <?= old('subject')==='General enquiry'?'selected':''?>>General enquiry</option>
              <option <?= old('subject')==='Other'?'selected':''?>>Other</option>
            </select>
          </div>
          <div class="field"><label>Message *</label><textarea name="message" rows="4" required placeholder="How can we help?"><?= esc(old('message')) ?></textarea></div>
          <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e)?></p><?php endforeach?></div><?php endif ?>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Send message</button>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div><div class="foot-logo"><img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco"><strong>MD Goatco Farm Limited</strong></div><p class="foot-tagline">A working goat farm in Mukono, Uganda.</p></div>
      <div><h5>Explore</h5><ul><li><a href="#about">About us</a></li><li><a href="#services">Services</a></li><li><a href="#goat-banking">Goat Banking</a></li></ul></div>
      <div><h5>Account</h5><ul>
        <?php if ($loggedIn): ?>
          <li><a href="<?= site_url('dashboard') ?>">My Dashboard</a></li>
          <li><a href="<?= site_url('auth/logout') ?>">Sign out</a></li>
        <?php else: ?>
          <li><a href="<?= site_url('auth/login') ?>">Log in</a></li>
          <li><a href="<?= site_url('auth/register') ?>">Apply for Goat Banking</a></li>
          <li><a href="<?= site_url('auth/status') ?>">Check application</a></li>
        <?php endif ?>
      </ul></div>
      <div><h5>Legal</h5><ul><li><a href="<?= site_url('privacy-policy') ?>">Privacy Policy</a></li><li><a href="<?= site_url('terms') ?>">Terms &amp; Conditions</a></li></ul>
      <h5 style="margin-top:16px">Contact</h5><ul><li><a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a></li><li>+256 700 000 000</li></ul></div>
    </div>
    <div class="foot-bottom"><span>© <?= date('Y') ?> MD Goatco Farm Limited · Registered in Uganda</span><span>Ethics · Service · Genetics</span></div>
  </div>
</footer>
<?= $this->endSection() ?>
