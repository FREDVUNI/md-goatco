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
  <a href="<?= site_url('admin/staff') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2"/></svg>Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('admin/staff') ?>" class="back-link">← Back to staff accounts</a>

<div class="card" style="max-width:600px">
  <div class="card-head"><h3>Create Staff Account</h3></div>

  <?php if (!empty($errors ?? [])): ?>
  <div class="form-errors form-errors-block" style="margin:0 20px 0">
    <strong>Please fix the following:</strong>
    <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
  </div>
  <?php endif ?>

  <?= form_open('admin/staff/create', ['class' => 'dash-form']) ?>
    <?= csrf_field() ?>

    <div class="form-section-label">Basic information</div>
    <div class="field-row">
      <div class="field">
        <label for="first_name">First name *</label>
        <input type="text" id="first_name" name="first_name"
               value="<?= esc(old('first_name')) ?>" placeholder="e.g. Sarah" required>
      </div>
      <div class="field">
        <label for="last_name">Last name *</label>
        <input type="text" id="last_name" name="last_name"
               value="<?= esc(old('last_name')) ?>" placeholder="e.g. Apio" required>
      </div>
    </div>
    <div class="field">
      <label for="email">Email address *</label>
      <input type="email" id="email" name="email"
             value="<?= esc(old('email')) ?>" placeholder="name@mdgoatco.farm" required>
      <p class="field-hint">This will be their login email. Recommend using @mdgoatco.farm address.</p>
    </div>
    <div class="field">
      <label for="phone">Phone number</label>
      <input type="tel" id="phone" name="phone"
             value="<?= esc(old('phone')) ?>" placeholder="+256 700 000 000">
    </div>

    <div class="form-section-label">Role & access</div>
    <div class="field">
      <label for="role">Role *</label>
      <select id="role" name="role" required>
        <option value="">Select a role…</option>
        <option value="vet"     <?= old('role') === 'vet'     ? 'selected' : '' ?>>Veterinarian — Can log vet visits, flag animals, view herd</option>
        <option value="manager" <?= old('role') === 'manager' ? 'selected' : '' ?>>Farm Manager — Full herd management, vet scheduling, reports</option>
      </select>
    </div>

    <div class="form-section-label">Initial password</div>
    <div class="field">
      <label for="password">Temporary password *</label>
      <input type="password" id="password" name="password"
             placeholder="Minimum 8 characters" required>
      <p class="field-hint">The staff member will be required to change this on their first login.</p>
    </div>

    <div style="background:var(--blue-tint);border:1px solid var(--border);border-radius:10px;padding:14px 16px;font-size:0.84rem;color:var(--slate);margin-top:4px;margin-bottom:20px;">
      The account will be <strong>immediately active</strong>. The staff member can log in using the
      <strong><?= old('role') === 'manager' ? '/auth/manager' : '/auth/vet' ?></strong> portal.
      Please share their credentials securely.
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Create account</button>
      <a href="<?= site_url('admin/staff') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Update the note about portal URL when role changes
document.getElementById('role').addEventListener('change', function() {
  const note = this.closest('.dash-form').querySelector('[style*="blue-tint"] strong:nth-child(2)');
  if (note) note.textContent = this.value === 'manager' ? '/auth/manager' : '/auth/vet';
});
</script>
<?= $this->endSection() ?>
