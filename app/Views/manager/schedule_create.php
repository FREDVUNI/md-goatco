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
  <a href="<?= site_url('manager/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('manager/schedule') ?>" class="back-link">← Back to schedule</a>

<div class="card" style="max-width:640px">
  <div class="card-head"><h3>Add Scheduled Task</h3></div>

  <?php if (!empty($errors ?? [])): ?>
  <div class="form-errors form-errors-block" style="margin:0 20px 0">
    <strong>Please fix the following:</strong>
    <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
  </div>
  <?php endif ?>

  <?= form_open('manager/schedule/create', ['class' => 'dash-form']) ?>
    <?= csrf_field() ?>

    <div class="form-section-label">Task details</div>

    <div class="field">
      <label for="task">Task name *</label>
      <input type="text" id="task" name="task"
             value="<?= esc(old('task')) ?>"
             placeholder="e.g. Vaccinations — Batch B, Weight checks — Pen 3"
             required>
    </div>

    <div class="field">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="3"
                placeholder="Additional details about this task…"><?= esc(old('description')) ?></textarea>
    </div>

    <div class="field">
      <label for="animals_desc">Animals / Pen</label>
      <input type="text" id="animals_desc" name="animals_desc"
             value="<?= esc(old('animals_desc')) ?>"
             placeholder="e.g. 24 animals — Pen 4, or All pens">
      <p class="field-hint">Optional — helps the vet know which animals to prepare for</p>
    </div>

    <div class="form-section-label">Scheduling</div>

    <div class="field-row">
      <div class="field">
        <label for="scheduled_at">Date &amp; time *</label>
        <input type="datetime-local" id="scheduled_at" name="scheduled_at"
               value="<?= esc(old('scheduled_at', date('Y-m-d\TH:i'))) ?>"
               min="<?= date('Y-m-d\TH:i') ?>"
               required>
      </div>
      <div class="field">
        <label for="assigned_vet_id">Assign to vet</label>
        <select id="assigned_vet_id" name="assigned_vet_id">
          <option value="">— Unassigned —</option>
          <?php foreach ($vets as $vet): ?>
          <option value="<?= $vet['id'] ?>"
                  <?= old('assigned_vet_id') == $vet['id'] ? 'selected' : '' ?>>
            <?= esc($vet['first_name'] . ' ' . $vet['last_name']) ?>
          </option>
          <?php endforeach ?>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Schedule task</button>
      <a href="<?= site_url('manager/schedule') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>

<?= $this->endSection() ?>
