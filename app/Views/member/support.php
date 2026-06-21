<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/></svg>Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/wallet') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Top Up Wallet
  </a>
  <div class="sb-section">Account</div>
  <a href="<?= site_url('member/account') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account
  </a>
  <a href="<?= site_url('member/support') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:22px;align-items:start">

  <!-- Contact form -->
  <div class="card">
    <div class="card-head"><h3>Send us a message</h3></div>
    <?= form_open('member/support', ['class' => 'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field">
        <label for="subject">Subject *</label>
        <select id="subject" name="subject" required>
          <option value="">Select…</option>
          <option value="Question about my goats">Question about my goats</option>
          <option value="Payment or account query">Payment or account query</option>
          <option value="Update personal details">Update personal details</option>
          <option value="Technical issue">Technical issue</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="field">
        <label for="message">Message *</label>
        <textarea id="message" name="message" rows="6" required
                  placeholder="Describe your question or concern in as much detail as possible…"></textarea>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Send message</button>
      </div>
      <p style="font-size:0.78rem;color:var(--slate-light);margin-top:12px">
        We typically reply within 1–2 working days.
      </p>
    <?= form_close() ?>
  </div>

  <!-- Contact info sidebar -->
  <div>
    <div class="card" style="padding:22px">
      <h4 style="font-size:0.96rem;font-weight:700;color:var(--primary-deep);margin-bottom:16px">Other ways to reach us</h4>
      <div class="contact-item">
        <div class="contact-icon">📧</div>
        <div>
          <div class="contact-label">Email</div>
          <a href="mailto:hello@mdgoatco.farm" class="contact-value">hello@mdgoatco.farm</a>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">📞</div>
        <div>
          <div class="contact-label">Phone / WhatsApp</div>
          <div class="contact-value">+256 700 000 000</div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">📍</div>
        <div>
          <div class="contact-label">Farm address</div>
          <div class="contact-value">Mukono–Kayunga Road<br>Mukono District, Uganda</div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">🕐</div>
        <div>
          <div class="contact-label">Office hours</div>
          <div class="contact-value">Mon–Fri: 8 AM – 5 PM<br>Sat: 8 AM – 12 noon</div>
        </div>
      </div>
    </div>

    <div class="card" style="padding:22px;margin-top:18px">
      <h4 style="font-size:0.96rem;font-weight:700;color:var(--primary-deep);margin-bottom:12px">Quick links</h4>
      <a href="<?= site_url('terms') ?>" target="_blank" class="quick-link">Terms &amp; Conditions →</a>
      <a href="<?= site_url('privacy-policy') ?>" target="_blank" class="quick-link">Privacy Policy →</a>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
.contact-item { display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border); }
.contact-item:last-child { border-bottom:none;margin-bottom:0;padding-bottom:0; }
.contact-icon { font-size:1.2rem;flex-shrink:0;width:28px;text-align:center; }
.contact-label { font-family:var(--font-mono);font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--slate-light);margin-bottom:3px; }
.contact-value { font-size:0.88rem;color:var(--ink);font-weight:500; }
a.contact-value { color:var(--primary); }
.quick-link { display:block;font-size:0.86rem;font-weight:600;color:var(--primary);padding:8px 0;border-bottom:1px solid var(--border); }
.quick-link:last-child { border-bottom:none; }
.quick-link:hover { text-decoration:underline; }
@media(max-width:860px){ div[style*="grid-template-columns:1fr 340px"]{ grid-template-columns:1fr !important; } }
</style>
<?= $this->endSection() ?>
