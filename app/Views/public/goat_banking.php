<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="<?= site_url('/') ?>">Home</a> › Goat Banking</p>
    <h1>Your goats. Your dashboard. Our farm.</h1>
    <p>Buy into a flock of well-bred, well-cared-for goats and watch them grow from your phone.</p>
    <div class="page-meta">
      <span>🐐 Individually tagged animals</span>
      <span>🩺 Vet-logged health records</span>
      <span><i class="fas fa-tachometer-alt"></i> Real-time member dashboard</span>
    </div>
  </div>
</div>

<section class="banking-wrap">
  <div class="wrap">
    <div class="banking-head">
      <div>
        <div class="eyebrow">How it works</div>
        <h2>Four steps from application to your first statement</h2>
        <p>No middlemen, no guesswork — every step of Goat Banking happens on the same record your goats live in.</p>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Apply for Goat Banking</a>
      </div>
      <div class="banking-img">
        <img src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=900" alt="Goat Banking" loading="lazy">
      </div>
    </div>

    <div class="why-grid">
      <div class="why-item"><span class="why-num">01</span>
        <div>
          <h3>Apply online</h3>
          <p>Tell us about yourself, your next of kin, and how many goats you'd like to hold. Takes about ten minutes.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">02</span>
        <div>
          <h3>We verify &amp; approve</h3>
          <p>Our team checks your ID and details, then approves your account — usually within 2–3 working days.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">03</span>
        <div>
          <h3>Goats are allocated</h3>
          <p>Tagged animals are assigned to your account. You can see their breed, age, weight and health from day one.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">04</span>
        <div>
          <h3>Track &amp; grow</h3>
          <p>Log in any time for growth charts, vet visits, and your statement of holdings — no phone calls required.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-alt">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Three dashboards, one farm</div>
      <h2>Built for everyone who touches the herd</h2>
    </div>
    <div class="role-grid">
      <div class="role-card">
        <div class="role-photo" style="background-image:url('https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=700')"></div>
        <div class="role-body">
          <span class="role-tag">Member dashboard</span>
          <h4>For Goat Banking members</h4>
          <ul>
            <li>See every goat in your account by name and tag</li>
            <li>Track weight, health status and growth over time</li>
            <li>View statements and top up your wallet securely via Pesapal</li>
          </ul>
        </div>
      </div>
      <div class="role-card">
        <div class="role-photo" style="background-image:url('https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=700')"></div>
        <div class="role-body">
          <span class="role-tag">Vet dashboard</span>
          <h4>For our veterinary team</h4>
          <ul>
            <li>Daily checkups, vaccinations and treatments due</li>
            <li>Log health records to each goat's file instantly</li>
            <li>Flag animals needing follow-up to the farm manager</li>
          </ul>
        </div>
      </div>
      <div class="role-card">
        <div class="role-photo" style="background-image:url('https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=700')"></div>
        <div class="role-body">
          <span class="role-tag">Manager dashboard</span>
          <h4>For farm managers</h4>
          <ul>
            <li>Whole-herd view: numbers, locations, health flags</li>
            <li>Member accounts, applications and onboarding</li>
            <li>Reports in minutes, not a full day in Excel</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="cta-wrap">
  <div class="wrap">
    <div class="cta">
      <div class="eyebrow">Ready when you are</div>
      <h2>Open your Goat Banking account</h2>
      <p>Tell us about yourself and your next of kin. Our team will review your application and contact you within 2–3 working days.</p>
      <div class="cta-actions">
        <a href="<?= site_url('auth/register') ?>" class="btn btn-white">Register for Goat Banking</a>
        <a href="<?= site_url('auth/login') ?>" class="btn btn-ghost-white">I already have an account</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>