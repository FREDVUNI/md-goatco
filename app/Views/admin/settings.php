<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41"/></svg>Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;max-width:900px">

  <!-- Farm details -->
  <div class="card" style="grid-column:1/-1">
    <div class="card-head"><h3>Farm Details</h3></div>
    <?= form_open('admin/settings', ['class' => 'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="form-section-label">Farm information</div>
      <div class="field-row">
        <div class="field">
          <label for="farm_name">Farm name</label>
          <input type="text" id="farm_name" name="farm_name" value="MD Goatco Farm Limited">
        </div>
        <div class="field">
          <label for="tagline">Tagline</label>
          <input type="text" id="tagline" name="tagline" value="Ethics · Service · Genetics">
        </div>
      </div>
      <div class="field-row">
        <div class="field">
          <label for="admin_email">Admin email</label>
          <input type="email" id="admin_email" name="admin_email" value="admin@mdgoatco.farm">
        </div>
        <div class="field">
          <label for="phone">Phone / WhatsApp</label>
          <input type="tel" id="phone" name="phone" value="+256 700 000 000">
        </div>
      </div>
      <div class="field">
        <label for="address">Farm address</label>
        <input type="text" id="address" name="address" value="Mukono–Kayunga Road, Mukono District, Uganda">
      </div>
      <div class="form-section-label">Application review settings</div>
      <div class="field-row">
        <div class="field">
          <label for="review_days">Review window (working days)</label>
          <input type="number" id="review_days" name="review_days" value="3" min="1" max="14">
        </div>
        <div class="field">
          <label for="min_goats">Minimum goats per application</label>
          <input type="number" id="min_goats" name="min_goats" value="1" min="1">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save settings</button>
      </div>
    <?= form_close() ?>
  </div>

  <!-- Environment info (read-only) -->
  <div class="card">
    <div class="card-head"><h3>Environment Info</h3></div>
    <div style="padding:20px;display:grid;gap:12px">
      <?php
        $info = [
          'PHP version'    => PHP_VERSION,
          'CI4 environment'=> ENVIRONMENT,
          'App URL'        => base_url(),
          'Timezone'       => date_default_timezone_get(),
          'Server time'    => date('j M Y, H:i:s'),
        ];
        foreach ($info as $label => $val):
      ?>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.86rem">
        <span style="color:var(--slate-light);font-family:var(--font-mono);font-size:0.78rem"><?= esc($label) ?></span>
        <span style="font-weight:600;color:var(--ink)"><?= esc($val) ?></span>
      </div>
      <?php endforeach ?>
    </div>
  </div>

  <!-- Danger zone -->
  <div class="card">
    <div class="card-head"><h3 style="color:var(--red)">⚠ Danger Zone</h3></div>
    <div style="padding:20px;display:grid;gap:14px">
      <div>
        <p style="font-size:0.88rem;color:var(--slate);margin-bottom:10px">
          <strong>Clear application cache</strong><br>
          Removes cached views and config. Safe to run anytime.
        </p>
        <a href="#" class="btn btn-ghost btn-sm" onclick="alert('Cache cleared (demo)');return false">Clear cache</a>
      </div>
      <div style="border-top:1px solid var(--border);padding-top:14px">
        <p style="font-size:0.88rem;color:var(--slate);margin-bottom:10px">
          <strong>Export all data</strong><br>
          Download a full CSV export of members, goats and transactions.
        </p>
        <a href="<?= site_url('manager/reports/export/all') ?>" class="btn btn-ghost btn-sm">📥 Export all data</a>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
