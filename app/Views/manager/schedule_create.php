<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
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
