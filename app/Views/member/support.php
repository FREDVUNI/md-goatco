<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-profile"><div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name']??'U',0,1).substr($currentUser['last_name']??'',0,1))) ?></div><div class="sb-profile-name"><?= esc(($currentUser['first_name']??'').' '.($currentUser['last_name']??'')) ?></div></div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('member/support') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/></svg>Support</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px">
  <div class="card">
    <div class="card-head"><h3>Contact support</h3></div>
    <?= form_open('member/support', ['class'=>'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field"><label>Subject</label><input type="text" name="subject" placeholder="e.g. Question about my statement" required></div>
      <div class="field"><label>Message</label><textarea name="message" rows="4" placeholder="How can we help?" required></textarea></div>
      <div class="form-actions"><button type="submit" class="btn btn-primary">Send message</button></div>
    <?= form_close() ?>
  </div>
  <div class="card">
    <div class="card-head"><h3>Contact us directly</h3></div>
    <div style="padding:20px;display:grid;gap:14px;font-size:0.86rem">
      <div><strong>📞 Phone / WhatsApp</strong><br><a href="tel:+256700000000" style="color:var(--blue)">+256 700 000 000</a></div>
      <div><strong>✉️ Email</strong><br><a href="mailto:hello@mdgoatco.farm" style="color:var(--blue)">hello@mdgoatco.farm</a></div>
      <div><strong>🕐 Office hours</strong><br>Mon–Fri 8AM–5PM · Sat 8AM–12PM</div>
      <div><strong>📍 Farm address</strong><br>Mukono–Kayunga Road, Mukono, Uganda</div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
