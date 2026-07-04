<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Veterinarian</div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/></svg>Today's Tasks</a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>Log a Visit</a>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History</a>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animals</a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>Health Flags</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card" style="max-width:680px">
  <div class="card-head"><h3>Log a Vet Visit</h3></div>
  <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open('vet/visits/log', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>
    <div class="field-row">
      <div class="field"><label>Animal *</label>
        <select name="goat_id" required>
          <option value="">Select animal…</option>
          <?php foreach ($goats as $g): ?><option value="<?= $g['id'] ?>">[<?= esc($g['tag_number']) ?>] <?= esc($g['name']) ?></option><?php endforeach ?>
        </select>
      </div>
      <div class="field"><label>Visit date *</label><input type="date" name="visit_date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required></div>
    </div>
    <div class="field-row">
      <div class="field"><label>Visit type *</label>
        <select name="visit_type" required>
          <option value="routine">Routine Check</option>
          <option value="vaccination">Vaccination</option>
          <option value="treatment">Treatment</option>
          <option value="emergency">Emergency</option>
          <option value="weight_check">Weight Check</option>
        </select>
      </div>
      <div class="field"><label>Weight recorded (kg)</label><input type="number" name="weight_recorded" step="0.1" min="0" placeholder="e.g. 24.5"></div>
    </div>
    <div class="field"><label>Clinical notes</label><textarea name="clinical_notes" rows="3" placeholder="Observations, symptoms, condition…"></textarea></div>
    <div class="field"><label>Treatment given</label><textarea name="treatment" rows="2" placeholder="Drugs administered, dosage, procedures…"></textarea></div>
    <div class="field-row">
      <div class="field"><label>Outcome</label>
        <select name="outcome">
          <option value="healthy">Healthy</option>
          <option value="monitoring">Monitoring</option>
          <option value="treated">Treated</option>
          <option value="critical">Critical</option>
        </select>
      </div>
      <div class="field"><label>Follow-up date</label><input type="date" name="followup_date" min="<?= date('Y-m-d') ?>"></div>
    </div>
    <div class="field" style="padding:14px;background:var(--bg);border-radius:10px">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
        <input type="checkbox" name="is_flagged" value="1" id="flagCheck" style="width:auto">
        🚨 Flag this animal — raise a health concern
      </label>
      <div id="flagDetails" style="display:none;margin-top:12px">
        <textarea name="flag_reason" rows="2" placeholder="Describe the health concern…"></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save visit log</button>
      <a href="<?= site_url('dashboard') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('flagCheck')?.addEventListener('change',function(){
  document.getElementById('flagDetails').style.display=this.checked?'block':'none';
});
</script>
<?= $this->endSection() ?>
