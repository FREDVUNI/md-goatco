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
      <li><a href="<?= site_url('/') ?>">Home</a></li>
      <li><a href="<?= site_url('/') ?>#about">About</a></li>
      <li><a href="<?= site_url('/') ?>#services">Services</a></li>
      <li><a href="<?= site_url('/') ?>#goat-banking">Goat Banking</a></li>
      <li><a href="<?= site_url('/') ?>#contact">Contact</a></li>
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
    <a href="<?= site_url('/') ?>">Home</a>
    <a href="<?= site_url('/') ?>#about">About</a>
    <a href="<?= site_url('/') ?>#services">Services</a>
    <a href="<?= site_url('/') ?>#contact">Contact</a>
    <div class="nav-mobile-actions">
      <a href="<?= site_url('auth/login') ?>" class="btn btn-outline">Log in</a>
      <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Join Goat Banking</a>
    </div>
  </div>
</header>

<div class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="<?= site_url('/') ?>">Home</a> › Terms &amp; Conditions</p>
    <h1>Terms &amp; Conditions</h1>
    <p>The agreement between you and MD Goatco Farm Limited governing your use of our website and Goat Banking program.</p>
    <div class="page-meta">
      <span>📅 Effective: 1 June 2026</span>
      <span>🔄 Last updated: 1 June 2026</span>
      <span>📍 Governed by: Laws of Uganda</span>
    </div>
  </div>
</div>

<div class="wrap doc-layout">
  <aside class="doc-toc">
    <h4>Contents</h4>
    <ul>
      <li><a href="#t1">1. Definitions</a></li>
      <li><a href="#t2">2. Acceptance</a></li>
      <li><a href="#t3">3. Eligibility</a></li>
      <li><a href="#t4">4. Goat Banking program</a></li>
      <li><a href="#t5">5. Application &amp; onboarding</a></li>
      <li><a href="#t6">6. Member obligations</a></li>
      <li><a href="#t7">7. Farm operations</a></li>
      <li><a href="#t8">8. Returns &amp; financial terms</a></li>
      <li><a href="#t9">9. Platform use</a></li>
      <li><a href="#t10">10. Intellectual property</a></li>
      <li><a href="#t11">11. Limitation of liability</a></li>
      <li><a href="#t12">12. Termination</a></li>
      <li><a href="#t13">13. Dispute resolution</a></li>
      <li><a href="#t14">14. Amendments</a></li>
      <li><a href="#t15">15. Contact</a></li>
    </ul>
  </aside>

  <div class="doc-body">

    <div class="highlight-box">
      <p><strong>Please read these Terms carefully.</strong> By registering for Goat Banking or using our website, you agree to be bound by these Terms. If you do not agree, do not use our services.</p>
    </div>

    <h2 id="t1">1. Definitions</h2>
    <ul>
      <li><strong>"Company"</strong> means MD Goatco Farm Limited, registered in Uganda.</li>
      <li><strong>"Goat Banking"</strong> means the Company's member investment program whereby Members purchase rights to goats reared on the farm.</li>
      <li><strong>"Member"</strong> means an individual who has been approved to participate in the Goat Banking program.</li>
      <li><strong>"Portfolio"</strong> means the goats allocated to a Member's account at any given time.</li>
      <li><strong>"Platform"</strong> means the website and member dashboard operated by the Company at mdgoatco.farm.</li>
    </ul>

    <h2 id="t2">2. Acceptance of terms</h2>
    <p>By accessing the Platform or submitting a Goat Banking application, you confirm that you have read, understood and agree to these Terms. These Terms form a legally binding agreement between you and MD Goatco Farm Limited under the laws of Uganda.</p>

    <h2 id="t3">3. Eligibility</h2>
    <ul>
      <li>Be at least <strong>18 years of age</strong></li>
      <li>Be a resident of Uganda or hold a valid Ugandan identity document</li>
      <li>Provide accurate, complete and current information at registration</li>
      <li>Not be subject to any legal restriction that would prevent you from entering a financial arrangement</li>
    </ul>
    <p>The Company reserves the right to decline any application without providing a reason.</p>

    <h2 id="t4">4. The Goat Banking program</h2>
    <p>Under Goat Banking, Members pay an agreed amount to acquire rights to one or more goats that are reared, maintained and managed exclusively by the Company on its farm. The Company retains physical custody of, and operational responsibility for, all goats at all times.</p>
    <p>Goat Banking membership does not confer ownership of land, farm infrastructure or any asset other than the right to the goats in your Portfolio and the returns arising from them as specified in your agreement.</p>

    <h2 id="t5">5. Application and onboarding</h2>
    <p>Complete the online application form in full, including all required personal and identification details. The Company will review your application and contact you within <strong>2–3 working days</strong>. Upon approval, you will receive an onboarding schedule detailing the number of goats, payment terms and expected returns.</p>
    <div class="warn-box">
      <p>⚠️ <strong>Providing false information</strong> at any stage — including false ID documents — will result in immediate termination of your application or membership and may be reported to relevant authorities.</p>
    </div>

    <h2 id="t6">6. Member obligations</h2>
    <ul>
      <li>Keep your account credentials confidential</li>
      <li>Ensure your contact details and next-of-kin information remain current and accurate</li>
      <li>Make payments on the agreed schedule and by the agreed method</li>
      <li>Not transfer or sell your Goat Banking membership without written consent from the Company</li>
      <li>Not use the Platform for any unlawful purpose</li>
    </ul>

    <h2 id="t7">7. Farm operations and animal care</h2>
    <p>The Company is solely responsible for all decisions relating to the physical care, feeding, veterinary treatment, breeding, and management of goats on the farm. Members acknowledge that farming involves inherent risks including disease, mortality, natural disaster and market fluctuation.</p>

    <h2 id="t8">8. Returns and financial terms</h2>
    <p>The specific financial terms — including payment amounts and return calculations — are set out in your individual onboarding schedule.</p>
    <div class="warn-box">
      <p>⚠️ <strong>Goat Banking involves financial risk.</strong> Returns are not guaranteed. The value of livestock can go down as well as up. Do not participate with funds you cannot afford to lose.</p>
    </div>

    <h2 id="t9">9. Dashboard and platform use</h2>
    <p>Access to the Member dashboard is provided for legitimate account management purposes only. You must not attempt to access other accounts, reverse-engineer the Platform, or share your login credentials with any other person.</p>

    <h2 id="t10">10. Intellectual property</h2>
    <p>All content on the Platform — including the MD Goatco Farm Limited name, logo, text, images and software — is owned by or licensed to the Company. Nothing in these Terms transfers any intellectual property rights to you.</p>

    <h2 id="t11">11. Limitation of liability</h2>
    <p>To the maximum extent permitted by Ugandan law, the Company's total liability shall not exceed the total amount paid by you in the 12 months preceding the event giving rise to the claim. The Company is not liable for indirect or consequential loss, or for losses arising from events outside our reasonable control.</p>

    <h2 id="t12">12. Termination</h2>
    <p><strong>By you:</strong> You may exit the Goat Banking program by giving the Company 30 days' written notice. Exit terms will be calculated in accordance with your onboarding schedule.</p>
    <p><strong>By the Company:</strong> We may terminate your membership immediately if you breach any material term, provide false information, or fail to make payment within 30 days of the due date after notice.</p>

    <h2 id="t13">13. Dispute resolution</h2>
    <p>In the event of any dispute, the parties agree to first attempt to resolve the matter informally. If unresolved after 28 days, either party may refer the dispute to mediation under the rules of the Uganda Mediation Centre. These Terms are governed by the laws of the Republic of Uganda.</p>

    <h2 id="t14">14. Amendments</h2>
    <p>The Company may update these Terms at any time. We will give registered Members at least <strong>14 days' notice</strong> of material changes by email. Continued use after the effective date constitutes acceptance.</p>

    <h2 id="t15">15. Contact us</h2>
    <div class="highlight-box">
      <p>
        <strong>MD Goatco Farm Limited</strong><br>
        Mukono–Kayunga Road, Mukono, Uganda<br>
        📧 <a href="mailto:legal@mdgoatco.farm">legal@mdgoatco.farm</a><br>
        📞 +256 700 000 000
      </p>
    </div>
  </div>
</div>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-logo">
          <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco">
          <strong>MD Goatco Farm Limited</strong>
        </div>
        <p class="foot-tagline">A working goat farm in Mukono, Uganda.</p>
      </div>
      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="<?= site_url('/') ?>#about">About us</a></li>
          <li><a href="<?= site_url('/') ?>#services">Services</a></li>
          <li><a href="<?= site_url('/') ?>#goat-banking">Goat Banking</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul>
          <li><a href="<?= site_url('privacy-policy') ?>">Privacy Policy</a></li>
          <li><a href="<?= site_url('terms') ?>">Terms &amp; Conditions</a></li>
        </ul>
      </div>
      <div>
        <h5>Contact</h5>
        <ul>
          <li>Mukono–Kayunga Road, Uganda</li>
          <li><a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a></li>
          <li>+256 700 000 000</li>
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