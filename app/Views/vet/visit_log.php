<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role sb-role-green">Veterinarian</div>
<nav class="sb-nav">
  <div class="sb-section">My Work</div>
  <a href="<?= site_url('vet/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>My Dashboard
  </a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>My Flags
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!empty($errors ?? [])): ?>
<div class="form-errors form-errors-block">
  <strong>Please fix the following:</strong>
  <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
</div>
<?php endif ?>

<div class="card" style="max-width:800px">
  <div class="card-head"><h3>Log a Veterinary Visit</h3></div>

  <?= form_open('vet/visits/log', ['class' => 'dash-form', 'id' => 'visitForm']) ?>
    <?= csrf_field() ?>

    <div class="form-section-label">Animal identification</div>
    <div class="field-row">
      <div class="field">
        <label for="goat_tag">Animal tag number *</label>
        <input type="text" id="goat_tag" name="goat_tag"
               value="<?= esc(old('goat_tag', $prefillGoat['tag_number'] ?? '')) ?>"
               placeholder="e.g. PGF-1042"
               oninput="lookupTag(this.value)"
               autocomplete="off">
        <div class="tag-lookup-result" id="tagResult"></div>
      </div>
      <div class="field">
        <label for="goat_id">Animal name (auto-filled)</label>
        <input type="text" id="goat_name_display"
               value="<?= esc($prefillGoat['name'] ?? '') ?>"
               placeholder="Auto-fills from tag" readonly>
        <input type="hidden" id="goat_id" name="goat_id"
               value="<?= esc(old('goat_id', $prefillGoat['id'] ?? '')) ?>">
      </div>
    </div>

    <div class="form-section-label">Visit details</div>
    <div class="field-row">
      <div class="field">
        <label for="visit_type">Visit type *</label>
        <select id="visit_type" name="visit_type" required>
          <option value="">Select…</option>
          <?php foreach ($visitTypes as $vt): ?>
          <option value="<?= $vt ?>" <?= old('visit_type') === $vt ? 'selected' : '' ?>>
            <?= esc(str_replace('_', ' ', ucfirst($vt))) ?>
          </option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="field">
        <label for="visit_date">Date &amp; time *</label>
        <input type="datetime-local" id="visit_date" name="visit_date"
               value="<?= esc(old('visit_date', date('Y-m-d\TH:i'))) ?>" required>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label for="weight_kg">Weight (kg)</label>
        <input type="number" id="weight_kg" name="weight_kg" step="0.1" min="0"
               value="<?= esc(old('weight_kg')) ?>" placeholder="e.g. 24.6">
        <p class="field-hint">Logged as a weight record automatically</p>
      </div>
      <div class="field">
        <label for="temperature">Temperature (°C)</label>
        <input type="number" id="temperature" name="temperature" step="0.1" min="30" max="45"
               value="<?= esc(old('temperature')) ?>" placeholder="e.g. 38.5">
        <p class="field-hint">Normal range: 38.5–40.0 °C</p>
      </div>
    </div>

    <div class="field">
      <label for="medication">Medication / vaccine administered</label>
      <input type="text" id="medication" name="medication"
             value="<?= esc(old('medication')) ?>"
             placeholder="e.g. Ivermectin 1ml, OJD vaccine">
    </div>

    <div class="field">
      <label for="clinical_notes">Clinical notes *</label>
      <textarea id="clinical_notes" name="clinical_notes" rows="4" required
                placeholder="Describe what you observed and what was done…"><?= esc(old('clinical_notes')) ?></textarea>
    </div>

    <!-- HEALTH FLAG SECTION -->
    <div class="flag-toggle">
      <label class="checkbox-label">
        <input type="checkbox" id="flag_toggle" name="is_flagged" value="1"
               <?= old('is_flagged') ? 'checked' : '' ?>
               onchange="toggleFlagSection(this.checked)">
        <span>🚨 Flag this animal for follow-up</span>
      </label>
    </div>

    <div class="flag-section" id="flagSection" style="display:<?= old('is_flagged') ? 'block' : 'none' ?>">
      <div class="flag-section-inner">
        <div class="field">
          <label for="flag_reason">Flag reason *</label>
          <input type="text" id="flag_reason" name="flag_reason"
                 value="<?= esc(old('flag_reason')) ?>"
                 placeholder="e.g. Weight loss — monitor daily">
        </div>
        <div class="field">
          <label for="followup_date">Follow-up date</label>
          <input type="date" id="followup_date" name="followup_date"
                 value="<?= esc(old('followup_date')) ?>"
                 min="<?= date('Y-m-d') ?>">
        </div>
        <p class="flag-note">Flagging this animal will notify the farm manager and the goat's owner (if assigned).</p>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">✓ Save visit record</button>
      <a href="<?= site_url('vet/dashboard') ?>" class="btn btn-ghost">Cancel</a>
    </div>

  <?= form_close() ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFlagSection(checked) {
  document.getElementById('flagSection').style.display = checked ? 'block' : 'none';
  document.getElementById('flag_reason').required = checked;
}

// Live tag lookup — calls API endpoint
let tagTimer;
function lookupTag(val) {
  clearTimeout(tagTimer);
  const result = document.getElementById('tagResult');
  const nameField = document.getElementById('goat_name_display');
  const idField = document.getElementById('goat_id');

  if (val.length < 4) {
    result.innerHTML = '';
    return;
  }

  tagTimer = setTimeout(async () => {
    try {
      const res = await fetch('<?= site_url('api/v1/goats') ?>?tag=' + encodeURIComponent(val), {
        headers: { 'Authorization': 'Bearer <?= session()->get('api_token') ?? '' ?>' }
      });
      if (!res.ok) { result.innerHTML = '<span class="tag-not-found">Tag not found</span>'; return; }
      const data = await res.json();
      if (data.data && data.data.length > 0) {
        const goat = data.data[0];
        nameField.value = goat.name;
        idField.value = goat.id;
        result.innerHTML = '<span class="tag-found">✓ Found: ' + goat.name + ' (' + goat.breed + ')</span>';
      } else {
        result.innerHTML = '<span class="tag-not-found">Tag not found in herd</span>';
        nameField.value = '';
        idField.value = '';
      }
    } catch(e) {
      result.innerHTML = '';
    }
  }, 400);
}
</script>
<?= $this->endSection() ?>
