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
  <a href="<?= site_url('admin/herd') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
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

<?php $isEdit = !empty($goat); ?>
<a href="<?= site_url('admin/herd') ?>" class="back-link">← Back to herd</a>

<div class="card" style="max-width:600px">
  <div class="card-head"><h3><?= $isEdit ? 'Edit — ' . esc($goat['name']) : 'Add Animal to Herd' ?></h3></div>

  <?php if (!empty($errors ?? [])): ?>
  <div class="form-errors form-errors-block" style="margin:0 20px 0">
    <strong>Please fix the following:</strong>
    <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
  </div>
  <?php endif ?>

  <?= form_open($isEdit ? 'admin/herd/' . $goat['id'] . '/edit' : 'admin/herd/create', ['class' => 'dash-form']) ?>
    <?= csrf_field() ?>

    <div class="form-section-label">Identification</div>
    <div class="field-row">
      <div class="field">
        <label for="tag_number">Tag number *</label>
        <input type="text" id="tag_number" name="tag_number"
               value="<?= esc(old('tag_number') ?? ($goat['tag_number'] ?? '')) ?>" placeholder="e.g. PGF-1042" required
               <?= $isEdit ? 'readonly' : '' ?>>
        <?php if ($isEdit): ?><p class="field-hint">Tag numbers can't be changed after creation.</p><?php endif ?>
      </div>
      <div class="field">
        <label for="name">Name *</label>
        <input type="text" id="name" name="name"
               value="<?= esc(old('name') ?? ($goat['name'] ?? '')) ?>" placeholder="e.g. Kito" required>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label for="breed">Breed</label>
        <input type="text" id="breed" name="breed"
               value="<?= esc(old('breed') ?? ($goat['breed'] ?? '')) ?>" placeholder="e.g. Boer">
      </div>
      <div class="field">
        <label for="sex">Sex *</label>
        <select id="sex" name="sex" required>
          <option value="">Select…</option>
          <option value="male"   <?= (old('sex') ?? ($goat['sex'] ?? '')) === 'male'   ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= (old('sex') ?? ($goat['sex'] ?? '')) === 'female' ? 'selected' : '' ?>>Female</option>
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob" value="<?= esc(old('dob') ?? ($goat['dob'] ?? '')) ?>">
      </div>
      <div class="field">
        <label for="pen_id">Pen</label>
        <input type="text" id="pen_id" name="pen_id"
               value="<?= esc(old('pen_id') ?? ($goat['pen_id'] ?? '')) ?>" placeholder="e.g. Pen 3">
      </div>
    </div>

    <div class="form-section-label">Goat Banking</div>
    <div class="field">
      <label for="member_id">Assigned member</label>
      <select id="member_id" name="member_id">
        <option value="">— Farm stock (unassigned) —</option>
        <?php foreach ($members ?? [] as $m): ?>
        <option value="<?= esc($m['id']) ?>" <?= (string) (old('member_id') ?? ($goat['member_id'] ?? '')) === (string) $m['id'] ? 'selected' : '' ?>>
          <?= esc($m['first_name'] . ' ' . $m['last_name']) ?>
        </option>
        <?php endforeach ?>
      </select>
    </div>

    <?php if ($isEdit): ?>
    <div class="field">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active"      <?= $goat['status'] === 'active'      ? 'selected' : '' ?>>Active</option>
        <option value="sold"        <?= $goat['status'] === 'sold'        ? 'selected' : '' ?>>Sold</option>
        <option value="deceased"    <?= $goat['status'] === 'deceased'    ? 'selected' : '' ?>>Deceased</option>
        <option value="transferred" <?= $goat['status'] === 'transferred' ? 'selected' : '' ?>>Transferred</option>
      </select>
    </div>
    <?php endif ?>

    <div class="form-section-label">Notes</div>
    <div class="field">
      <label for="notes">Notes</label>
      <textarea id="notes" name="notes" placeholder="Any extra detail…"><?= esc(old('notes') ?? ($goat['notes'] ?? '')) ?></textarea>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save changes' : 'Add to herd' ?></button>
      <a href="<?= site_url('admin/herd') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>

<?= $this->endSection() ?>
