<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ===== HERO ===== -->
<section class="hero" id="home">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <div class="eyebrow">Mukono, Uganda · Est. 2012</div>
      <h1>Every goat has a name.<br>Now it has <em>a record</em>, too.</h1>
      <p class="lead">MD Goatco Farm Limited raises healthy, well-bred goats — and gives you a way to grow with us. Buy into our Goat Banking program, follow your animals from kid to market, and let our vets and managers handle the rest.</p>
      <div class="hero-actions">
        <a href="#goat-banking" class="btn btn-white">Explore Goat Banking</a>
        <a href="#services" class="btn btn-ghost-white">What we do</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat"><strong>1,247</strong><span>Goats on farm</span></div>
        <div class="hero-stat"><strong>380</strong><span>Banking members</span></div>
        <div class="hero-stat"><strong>12 yrs</strong><span>Experience</span></div>
      </div>
    </div>
    <div class="hero-img">
      <div class="hero-badge">
        <strong>+4.2 kg</strong>
        Avg growth this quarter
      </div>
      <img src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=900"
        alt="Goats on the MD Goatco Farm" loading="eager">
    </div>
  </div>
</section>

<!-- ===== TAGLINE STRIP ===== -->
<div class="tagline-strip">
  <div class="tagline-inner wrap">
    <div class="tagline-item"><span class="tagline-dot"></span>Ethics in every decision</div>
    <div class="tagline-item"><span class="tagline-dot"></span>Service to our members</div>
    <div class="tagline-item"><span class="tagline-dot"></span>Genetics you can trust</div>
    <div class="tagline-item"><span class="tagline-dot"></span>Fully digital records</div>
    <div class="tagline-item"><span class="tagline-dot"></span>Resident veterinary team</div>
  </div>
</div>

<!-- ===== ABOUT ===== -->
<section id="about">
  <div class="wrap about-grid">
    <div class="about-img">
      <img src="https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=1200"
        alt="Goat at MD Goatco Farm" loading="lazy">
    </div>
    <div class="about-copy">
      <div class="eyebrow">Who we are</div>
      <h2>A real farm, run the modern way</h2>
      <p>For over a decade, MD Goatco Farm Limited has reared Boer, Galla and local crossbreeds for meat, breeding and dairy — built on careful records that used to live in notebooks and Excel sheets.</p>
      <p>We're moving those records online, so every goat, every member, and every vet visit is tracked in one place — accessible to the right people, at any time.</p>
      <ul class="about-list">
        <li>Individually tagged, tracked goats — not just headcounts</li>
        <li>Vet visits, vaccinations and weight logs on record</li>
        <li>Members can follow their own goats from anywhere</li>
        <li>Role-based dashboards for vets, managers and members</li>
      </ul>
    </div>
  </div>
</section>

<!-- ===== SERVICES ===== -->
<section id="services" class="section-alt">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">What we do</div>
      <h2>From the kraal to your dashboard</h2>
      <p>Four pillars of the farm, all feeding one digital record system.</p>
    </div>
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
        <p>Boer, Galla and local crossbreeds raised for meat, breeding stock and dairy — sold farm-direct or through partners.</p>
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
        <p>Routine checkups, vaccinations and treatment, logged against each goat's record by our resident vet team.</p>
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
        <p>Buy into a flock, follow your goats by name, and track their growth and value over time from your dashboard.</p>
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
        <p>Setting up your own goat enterprise? We advise on breeds, housing, feeding and record-keeping systems.</p>
        <span class="service-tag">Advisory</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== GOAT BANKING ===== -->
<section id="goat-banking" class="banking-wrap">
  <div class="wrap">
    <div class="banking-head">
      <div>
        <div class="eyebrow">The Goat Banking program</div>
        <h2>Your goats. Your dashboard. Our farm.</h2>
        <p>When you join Goat Banking, your animals are tagged, tracked and visible to you at all times — alongside the specialists who care for them every day.</p>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Apply for Goat Banking</a>
      </div>
      <div class="banking-img">
        <img src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=900" alt="Goat Banking" loading="lazy">
      </div>
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
            <li>View holdings statements and any payouts</li>
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
            <li>Flag animals needing follow-up to farm manager</li>
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

<!-- ===== WHY CHOOSE US ===== -->
<section>
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Why choose MD Goatco</div>
      <h2>Built on care, kept honest by records</h2>
    </div>
    <div class="why-grid">
      <div class="why-item"><span class="why-num">01</span>
        <div>
          <h3>Every goat is tagged and named</h3>
          <p>No animal is just a number — each has an ID, a health history, and for Banking members, an owner on record.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">02</span>
        <div>
          <h3>Resident veterinary team</h3>
          <p>Routine care isn't an afterthought. Our vets keep a written, dated record for every single animal.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">03</span>
        <div>
          <h3>Transparent member dashboards</h3>
          <p>Invested through Goat Banking? Log in and see exactly how your animals are doing — no need to call us.</p>
        </div>
      </div>
      <div class="why-item"><span class="why-num">04</span>
        <div>
          <h3>Experienced farm management</h3>
          <p>Over a decade rearing goats, now backed by digital records instead of paper books and spreadsheets.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section-alt">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">From our members</div>
      <h2>"I can see my goats from my phone"</h2>
    </div>
    <div class="test-grid">
      <div class="test-card">
        <div class="test-stars">★★★★★</div>
        <p>"I joined Goat Banking with three goats. I can log in and see exactly how they're growing — no more waiting for a phone call."</p>
        <div class="test-who">
          <div class="test-avatar" style="background-image:url('https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=200')"></div>
          <div><strong>Esther N.</strong><span>Goat Banking member, Mukono</span></div>
        </div>
      </div>
      <div class="test-card">
        <div class="test-stars">★★★★★</div>
        <p>"Vaccination records used to live in a notebook that could get lost. Now every goat's history is one search away."</p>
        <div class="test-who">
          <div class="test-avatar" style="background-image:url('https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=200')"></div>
          <div><strong>Dr. Wasswa</strong><span>Farm veterinarian</span></div>
        </div>
      </div>
      <div class="test-card">
        <div class="test-stars">★★★★★</div>
        <p>"Reports that took a full day in Excel now take minutes. We spend that time actually managing the herd."</p>
        <div class="test-who">
          <div class="test-avatar" style="background-image:url('https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=200')"></div>
          <div><strong>Brian K.</strong><span>Farm manager</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
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

<!-- ===== CONTACT ===== -->
<section id="contact">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Get in touch</div>
      <h2>We'd love to hear from you</h2>
      <p>Questions about Goat Banking, the farm, or anything else — reach us via the form or directly.</p>
    </div>
    <div class="contact-grid">
      <div class="contact-info">
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="contact-label">Visit us</div>
            <div class="contact-value">Mukono–Kayunga Road<br>Mukono District, Uganda</div>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
          <div>
            <div class="contact-label">Phone / WhatsApp</div>
            <a href="tel:+256700000000" class="contact-value">+256 700 000 000</a>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <div class="contact-label">Email</div>
            <a href="mailto:hello@mdgoatco.farm" class="contact-value">hello@mdgoatco.farm</a>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-clock"></i></div>
          <div>
            <div class="contact-label">Office hours</div>
            <div class="contact-value">Monday – Friday: 8 AM – 5 PM<br>Saturday: 8 AM – 12 noon</div>
          </div>
        </div>
      </div>

      <div class="contact-form-card">
        <h3>Send us a message</h3>
        <?= form_open('contact', ['class' => 'contact-form']) ?>
        <?= csrf_field() ?>
        <div class="field-row">
          <div class="field">
            <label for="name">Your name *</label>
            <input type="text" id="name" name="name" value="<?= esc(old('name')) ?>" placeholder="e.g. Robert Kizito" required>
          </div>
          <div class="field">
            <label for="email">Email address *</label>
            <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="you@example.com" required>
          </div>
        </div>
        <div class="field">
          <label for="subject">Subject *</label>
          <select id="subject" name="subject" required>
            <option value="">Select…</option>
            <option <?= old('subject') === 'Goat Banking enquiry' ? 'selected' : '' ?>>Goat Banking enquiry</option>
            <option <?= old('subject') === 'Goat purchase / sales' ? 'selected' : '' ?>>Goat purchase / sales</option>
            <option <?= old('subject') === 'Farm consultancy' ? 'selected' : '' ?>>Farm consultancy</option>
            <option <?= old('subject') === 'General enquiry' ? 'selected' : '' ?>>General enquiry</option>
            <option <?= old('subject') === 'Other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
        <div class="field">
          <label for="message">Message *</label>
          <textarea id="message" name="message" required
            placeholder="Tell us what you'd like to know…"><?= esc(old('message')) ?></textarea>
        </div>
        <?php if (!empty($errors ?? [])): ?>
          <div class="form-errors">
            <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
          </div>
        <?php endif ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Send message</button>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>