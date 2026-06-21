<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Overview</div>
  <a href="<?= site_url('manager/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $isEdit = !empty($goat); ?>
<a href="<?= site_url('manager/herd') ?>" class="back-link">← Back to herd</a>

<div class="card" style="max-width:600px">
  <div class="card-head"><h3><?= $isEdit ? 'Edit — ' . esc($goat['name']) : 'Add Goat to Herd' ?></h3></div>

  <?php if (!empty($errors ?? [])): ?>
  <div class="form-errors form-errors-block" style="margin:0 20px 0">
    <strong>Please fix the following:</strong>
    <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
  </div>
  <?php endif ?>

  <?= form_open($isEdit ? 'manager/herd/' . $goat['id'] . '/edit' : 'manager/herd/create', ['class' => 'dash-form']) ?>
    <?= csrf_field() ?>

    <div class="form-section-label">Identification</div>
    <div class="field-row">
      <div class="field">
        <label for="tag_number">Tag number *</label>
        <input type="text" id="tag_number" name="tag_number"
               value="<?= esc(old('tag_number') ?? ($goat['tag_number'] ?? '')) ?>" placeholder="e.g. PGF-1042" required
               <?= $isEdit ? 'readonly' : '' ?>>
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
      <a href="<?= site_url('manager/herd') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>

<?= $this->endSection() ?>
