<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<?php $loggedIn = session()->has('user_id'); ?>

<!-- ===== HEADER ===== -->
<header>
  <nav class="nav wrap">
    <a href="#home" class="logo">
      <img
        src="<?php echo base_url('img/logo.png'); ?>"
        alt="MD Goatco Farm Limited Logo" />
      <div class="logo-text">
        <strong>MD Goatco Farm Limited</strong>
        <small>Ethics · Service · Genetics</small>
      </div>
    </a>
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#goat-banking">Goat Banking</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <a
        href="#"
        class="btn btn-outline btn-sm"
        onclick="
              openLogin();
              return false;
            ">Log in</a>
      <a
        href="#"
        class="btn btn-primary btn-sm"
        onclick="
              openRegister();
              return false;
            ">Join Goat Banking</a>
    </div>
    <button
      class="nav-toggle"
      aria-label="Menu"
      onclick="
            this.classList.toggle('open');
            this.closest('.nav').classList.toggle('mobile-open');
          ">
      <span></span><span></span><span></span>
    </button>
  </nav>
</header>

<!-- ===== HERO ===== -->
<section class="hero" id="home">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <div class="eyebrow">Mukono, Uganda · Est. 2012</div>
      <h1>
        Every goat has a name.<br />Now it has <em>a record</em>, too.
      </h1>
      <p class="lead">
        MD Goatco Farm Limited raises healthy, well-bred goats — and gives
        you a way to grow with us. Buy into our Goat Banking program, follow
        your animals, and let our vets and farm managers handle the rest.
      </p>
      <div class="hero-actions">
        <a href="#goat-banking" class="btn btn-white">Explore Goat Banking</a>
        <a href="#services" class="btn btn-white-ghost">What we do</a>
      </div>
      <div class="hero-stats">
        <div><strong>1,240+</strong><span>Goats on farm</span></div>
        <div><strong>380</strong><span>Banking members</span></div>
        <div><strong>12 yrs</strong><span>Experience</span></div>
      </div>
    </div>
    <div class="hero-img">
      <div class="hero-badge">
        <strong>+4.2 kg</strong>
        Growth this quarter
      </div>
      <img
        src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=900"
        alt="Goats on the MD Goatco Farm" />
    </div>
  </div>
</section>

<!-- ===== TAGLINE STRIP ===== -->
<div class="tagline-strip">
  <div class="tagline-inner">
    <div class="tagline-item">
      <span class="tagline-dot"></span>Ethics in every decision
    </div>
    <div class="tagline-item">
      <span class="tagline-dot"></span>Service to our members
    </div>
    <div class="tagline-item">
      <span class="tagline-dot"></span>Genetics you can trust
    </div>
    <div class="tagline-item">
      <span class="tagline-dot"></span>Fully digital records
    </div>
    <div class="tagline-item">
      <span class="tagline-dot"></span>Resident veterinary team
    </div>
  </div>
</div>

<!-- ===== ABOUT ===== -->
<section id="about">
  <div class="wrap about-grid">
    <div class="about-img">
      <img
        src="https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=1200"
        alt="Goat at MD Goatco Farm"
        loading="lazy" />
    </div>
    <div class="about-copy">
      <div class="eyebrow">Who we are</div>
      <h2>A real farm, run the modern way</h2>
      <p>
        For over a decade, MD Goatco Farm Limited has reared Boer, Galla and
        local crossbreeds for meat, breeding and dairy — built on careful
        records that used to live in notebooks and Excel sheets.
      </p>
      <p>
        We're moving those records online, so every goat, every member, and
        every vet visit is tracked in one place — accessible to the right
        people, at any time.
      </p>
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
      <p>
        Four pillars of the farm, all feeding one digital record system.
      </p>
    </div>
    <div class="photo-strip">
      <img
        src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=700"
        alt="Goat farm herd"
        loading="lazy" />
      <img
        src="https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=700"
        alt="Goat close up"
        loading="lazy" />
      <img
        src="https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=700"
        alt="Young goat"
        loading="lazy" />
    </div>
    <div class="service-grid">
      <div class="service-card">
        <div class="service-icon">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8">
            <path d="M4 18c0-5 2-9 8-9s8 4 8 9" stroke-linecap="round" />
            <circle cx="12" cy="6" r="2.4" />
            <path d="M2 18h20" stroke-linecap="round" />
          </svg>
        </div>
        <h3>Goat Rearing &amp; Sales</h3>
        <p>
          Boer, Galla and local crossbreeds raised for meat, breeding stock
          and dairy — sold farm-direct or through partners.
        </p>
        <span class="service-tag">Core herd</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8">
            <path
              d="M12 21s-7-4.5-7-10a4 4 0 017-2.6A4 4 0 0119 11c0 5.5-7 10-7 10z"
              stroke-linecap="round"
              stroke-linejoin="round" />
            <path d="M12 9v6M9 12h6" stroke-linecap="round" />
          </svg>
        </div>
        <h3>Veterinary Care</h3>
        <p>
          Routine checkups, vaccinations and treatment, logged against each
          goat's record by our resident vet team.
        </p>
        <span class="service-tag">Animal health</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8">
            <rect x="3" y="7" width="18" height="12" rx="2" />
            <path d="M3 11h18M7 15h.01" stroke-linecap="round" />
            <path
              d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"
              stroke-linecap="round" />
          </svg>
        </div>
        <h3>Goat Banking</h3>
        <p>
          Buy into a flock, follow your goats by name, and track their
          growth and value over time from your own dashboard.
        </p>
        <span class="service-tag">Member program</span>
      </div>
      <div class="service-card">
        <div class="service-icon">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8">
            <path
              d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"
              stroke-linejoin="round" />
            <path
              d="M12 12l8-4.5M12 12v9M12 12L4 7.5"
              stroke-linejoin="round" />
          </svg>
        </div>
        <h3>Farm Consultancy</h3>
        <p>
          Setting up your own goat enterprise? We advise on breeds, housing,
          feeding and record-keeping systems.
        </p>
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
        <p>
          When you join Goat Banking, your animals are tagged, tracked and
          visible to you at all times — alongside the specialists who care
          for them every day.
        </p>
        <br />
        <a
          href="#"
          class="btn btn-primary"
          onclick="
                openRegister();
                return false;
              ">Apply for Goat Banking</a>
      </div>
      <div class="banking-img">
        <img
          src="https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=900"
          alt="Goat Banking"
          loading="lazy" />
      </div>
    </div>
    <div class="role-grid">
      <div class="role-card">
        <div
          class="role-photo"
          style="
                background-image: url(&quot;https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=700&quot;);
              "></div>
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
        <div
          class="role-photo"
          style="
                background-image: url(&quot;https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=700&quot;);
              "></div>
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
        <div
          class="role-photo"
          style="
                background-image: url(&quot;https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=700&quot;);
              "></div>
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
      <div class="why-item">
        <span class="num">01</span>
        <div>
          <h3>Every goat is tagged and named</h3>
          <p>
            No animal is "just a number" — each has an ID, a health history,
            and for Banking members, an owner on record.
          </p>
        </div>
      </div>
      <div class="why-item">
        <span class="num">02</span>
        <div>
          <h3>Resident veterinary team</h3>
          <p>
            Routine care isn't an afterthought. Our vets keep a written,
            dated record for every single animal.
          </p>
        </div>
      </div>
      <div class="why-item">
        <span class="num">03</span>
        <div>
          <h3>Transparent member dashboards</h3>
          <p>
            Invested through Goat Banking? You don't need to call us to ask
            how your goats are doing — log in and see.
          </p>
        </div>
      </div>
      <div class="why-item">
        <span class="num">04</span>
        <div>
          <h3>Experienced farm management</h3>
          <p>
            Over a decade rearing goats for meat, breeding and dairy, now
            backed by digital records instead of paper books.
          </p>
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
        <p>
          "I joined Goat Banking with three goats. I can log in and see
          exactly how they're growing — no more waiting for a phone call."
        </p>
        <div class="test-who">
          <div
            class="test-avatar"
            style="
                  background-image: url(&quot;https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=200&quot;);
                "></div>
          <div>
            <strong>Esther N.</strong><span>Goat Banking member, Mukono</span>
          </div>
        </div>
      </div>
      <div class="test-card">
        <div class="test-stars">★★★★★</div>
        <p>
          "Vaccination records used to live in a notebook that could get
          lost. Now every goat's history is one search away."
        </p>
        <div class="test-who">
          <div
            class="test-avatar"
            style="
                  background-image: url(&quot;https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=200&quot;);
                "></div>
          <div>
            <strong>Dr. Wasswa</strong><span>Farm veterinarian</span>
          </div>
        </div>
      </div>
      <div class="test-card">
        <div class="test-stars">★★★★★</div>
        <p>
          "Reports that took a full day in Excel now take minutes. We spend
          that time actually managing the herd."
        </p>
        <div class="test-who">
          <div
            class="test-avatar"
            style="
                  background-image: url(&quot;https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=200&quot;);
                "></div>
          <div><strong>Brian K.</strong><span>Farm manager</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
<section>
  <div class="cta">
    <div
      class="eyebrow"
      style="justify-content: center; color: rgba(255, 255, 255, 0.7)">
      Ready when you are
    </div>
    <h2>Open your Goat Banking account</h2>
    <p>
      Tell us about yourself and your next of kin. Our team will get your
      account and your first goats set up.
    </p>
    <div class="hero-actions">
      <a
        href="#"
        class="btn btn-white"
        onclick="
              openRegister();
              return false;
            ">Register for Goat Banking</a>
      <a
        href="#"
        class="btn btn-white-ghost"
        onclick="
              openLogin();
              return false;
            ">I already have an account</a>
    </div>
  </div>
</section>

<!-- ===== CONTACT SECTION ===== -->
<section id="contact" class="section-blue">
  <div class="wrap">
    <div class="contact-grid">
      <div class="contact-info">
        <div class="eyebrow">Get in touch</div>
        <h2>We'd love to hear from you</h2>
        <p>
          Whether you're interested in Goat Banking, buying livestock, or
          just want to visit the farm — reach out and our team will get back
          to you promptly.
        </p>

        <div class="contact-detail">
          <div class="contact-icon">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8">
              <path
                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
              <circle cx="12" cy="9" r="2.5" />
            </svg>
          </div>
          <div>
            <strong>Farm address</strong>
            <span>Mukono–Kayunga Road, Mukono District, Uganda</span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-icon">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8">
              <path
                d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.52 12a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 1.3h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.04a16 16 0 006.06 6.06l1.31-1.31a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7a2 2 0 011.72 2z" />
            </svg>
          </div>
          <div>
            <strong>Phone &amp; WhatsApp</strong>
            <span>+256 700 000 000</span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-icon">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8">
              <path
                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
              <polyline points="22,6 12,13 2,6" />
            </svg>
          </div>
          <div>
            <strong>Email</strong>
            <span>hello@mdgoatco.farm</span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-icon">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <div>
            <strong>Farm hours</strong>
            <span>Mon – Sat: 7:00 AM – 6:00 PM<br />Sunday: By appointment
              only</span>
          </div>
        </div>

        <div class="contact-social">
          <a href="#" class="social-btn" title="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
            </svg>
          </a>
          <a href="#" class="social-btn" title="WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
          </a>
          <a href="#" class="social-btn" title="Twitter / X">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
            </svg>
          </a>
          <a href="#" class="social-btn" title="Instagram">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
            </svg>
          </a>
        </div>
      </div>

      <div class="contact-form-card">
        <h3>Send us a message</h3>
        <p class="sub">We'll respond within 1–2 business days.</p>
        <div id="contactForm">
          <div class="field-row">
            <div class="field">
              <label>First name *</label><input type="text" placeholder="Esther" />
            </div>
            <div class="field">
              <label>Last name *</label><input type="text" placeholder="Nakato" />
            </div>
          </div>
          <div class="field">
            <label>Email address *</label><input type="email" placeholder="you@example.com" />
          </div>
          <div class="field">
            <label>Phone number</label><input type="tel" placeholder="+256 700 000 000" />
          </div>
          <div class="field">
            <label>Subject *</label>
            <select>
              <option value="">Select a topic…</option>
              <option>Goat Banking enquiry</option>
              <option>Buying livestock</option>
              <option>Farm visit</option>
              <option>Veterinary services</option>
              <option>Farm consultancy</option>
              <option>Other</option>
            </select>
          </div>
          <div class="field">
            <label>Message *</label>
            <textarea
              placeholder="Tell us what you'd like to know or discuss…"></textarea>
          </div>
          <button
            class="btn btn-primary"
            style="width: 100%"
            onclick="submitContact()">
            Send message
          </button>
        </div>
        <div class="contact-success" id="contactSuccess">
          <div class="tick">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </div>
          <h4>Message sent!</h4>
          <p>
            Thank you for reaching out. A member of our team will get back
            to you within 1–2 business days.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-logo">
          <img src="<?php echo base_url('img/logo.png'); ?>" alt="MD Goatco logo" />
          <strong>MD Goatco Farm Limited</strong>
        </div>
        <p class="tagline">
          A working goat farm in Mukono, Uganda — rearing, veterinary care
          and a member-owned Goat Banking program on one digital record.
        </p>
      </div>
      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="#about">About us</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#goat-banking">Goat Banking</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <div>
        <h5>Account</h5>
        <ul>
          <li>
            <a
              href="#"
              onclick="
                    openLogin();
                    return false;
                  ">Log in</a>
          </li>
          <li>
            <a
              href="#"
              onclick="
                    openRegister();
                    return false;
                  ">Register</a>
          </li>
          <li><a href="#">Member support</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul>
          <li><a href="privacy.html">Privacy Policy</a></li>
          <li><a href="terms.html">Terms &amp; Conditions</a></li>
          <li><a href="#contact">Contact us</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?php echo date('Y'); ?> MD Goatco Farm Limited · Registered in Uganda</span>
      <span><a
          href="privacy.html"
          style="color: rgba(255, 255, 255, 0.4); margin-right: 16px">Privacy</a><a href="terms.html" style="color: rgba(255, 255, 255, 0.4)">Terms</a></span>
    </div>
  </div>
</footer>
<?= $this->endSection() ?>