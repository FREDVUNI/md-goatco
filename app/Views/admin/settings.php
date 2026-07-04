<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
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
