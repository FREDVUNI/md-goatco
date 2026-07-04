<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
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
