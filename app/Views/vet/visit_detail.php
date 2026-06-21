<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role sb-role-green">Veterinarian</div>
<nav class="sb-nav">
  <div class="sb-section">My Work</div>
  <a href="<?= site_url('vet/dashboard') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>My Dashboard
  </a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>My Flags
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('vet/visits/history') ?>" class="back-link">← Back to visit history</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= $goat ? esc($goat['name'] . ' (' . $goat['tag_number'] . ')') : 'Unknown animal' ?></h4>
    <div class="profile-row"><label>Visit type</label><span><?= esc(visitTypeLabel($visit['visit_type'])) ?></span></div>
    <div class="profile-row"><label>Visit date</label><span><?= date('j M Y, g:i A', strtotime($visit['visit_date'])) ?></span></div>
    <?php if (!empty($visit['temperature'])): ?>
    <div class="profile-row"><label>Temperature</label><span><?= esc($visit['temperature']) ?> °C</span></div>
    <?php endif ?>
    <?php if (!empty($visit['weight_kg'])): ?>
    <div class="profile-row"><label>Weight</label><span><?= esc($visit['weight_kg']) ?> kg</span></div>
    <?php endif ?>
    <div class="profile-row"><label>Flagged</label>
      <span><?= !empty($visit['is_flagged']) ? (empty($visit['flag_resolved_at']) ? '<span class="badge badge-flagged">Active</span>' : '<span class="badge badge-active">Resolved</span>') : 'No' ?></span>
    </div>
    <?php if ($goat): ?>
    <a href="<?= site_url('vet/animals/' . $goat['id']) ?>" class="btn btn-outline btn-sm" style="margin-top:16px;">View full animal record</a>
    <?php endif ?>
  </div>

  <div class="profile-card">
    <h4>Clinical Notes</h4>
    <p style="font-size:14px; color:var(--slate); line-height:1.6;"><?= nl2br(esc($visit['clinical_notes'])) ?></p>

    <?php if (!empty($visit['medication'])): ?>
    <h4 style="margin-top:20px;">Medication</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($visit['medication'])) ?></p>
    <?php endif ?>

    <?php if (!empty($visit['is_flagged'])): ?>
    <h4 style="margin-top:20px;">Flag Reason</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($visit['flag_reason'] ?: '—')) ?></p>
    <?php endif ?>

    <?php if (!empty($visit['followup_date'])): ?>
    <div class="profile-row" style="margin-top:16px;"><label>Follow-up due</label><span><?= date('j M Y', strtotime($visit['followup_date'])) ?></span></div>
    <?php endif ?>
  </div>
</div>

<?= $this->endSection() ?>
