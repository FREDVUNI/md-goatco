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
  <a href="<?= site_url('vet/animals') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>My Flags
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('vet/animals') ?>" class="back-link">← Back to animal records</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= esc($goat['name']) ?> <span class="tag"><?= esc($goat['tag_number']) ?></span></h4>
    <div class="profile-row"><label>Breed</label><span><?= esc($goat['breed'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Sex</label><span><?= esc(ucfirst($goat['sex'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Age</label><span><?= esc(goatAge($goat['dob'])) ?></span></div>
    <div class="profile-row"><label>Pen</label><span><?= esc($goat['pen_id'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($goat['status']) ?></span></div>
    <?php if (!empty($latestWeight)): ?>
    <div class="profile-row"><label>Latest weight</label><span><?= esc($latestWeight['weight_kg']) ?> kg <span style="color:var(--slate-light,#718096);font-size:12px;">(<?= date('j M Y', strtotime($latestWeight['logged_at'])) ?>)</span></span></div>
    <?php endif ?>

    <a href="<?= site_url('vet/visits/log?tag=' . urlencode($goat['tag_number'])) ?>" class="btn btn-primary btn-sm" style="margin-top:16px;">Log a visit for this animal</a>
  </div>

  <div class="profile-card">
    <h4>Weight Trend</h4>
    <?php if (empty($growthChart)): ?>
    <p style="color:var(--slate-light,#718096); font-size:14px;">Not enough weight logs yet to chart a trend.</p>
    <?php else: ?>
    <table>
      <thead><tr><th>Month</th><th>Avg weight</th><th>Max weight</th></tr></thead>
      <tbody>
        <?php foreach ($growthChart as $row): ?>
        <tr>
          <td><?= esc($row['month']) ?></td>
          <td><?= esc(round((float) $row['avg_weight'], 1)) ?> kg</td>
          <td><?= esc(round((float) $row['max_weight'], 1)) ?> kg</td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Health History</h3></div>
  <?php if (empty($healthHistory)): ?>
  <div class="empty-state">No vet visits recorded for this animal yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Type</th><th>Vet</th><th>Notes</th><th>Flag</th></tr></thead>
    <tbody>
      <?php foreach ($healthHistory as $v): ?>
      <tr>
        <td><a href="<?= site_url('vet/visits/' . $v['id']) ?>" style="color:var(--blue);font-weight:600;"><?= date('j M Y', strtotime($v['visit_date'])) ?></a></td>
        <td><?= esc(visitTypeLabel($v['visit_type'])) ?></td>
        <td><?= esc(($v['first_name'] ?? '') . ' ' . ($v['last_name'] ?? '')) ?></td>
        <td><?= esc(substr($v['clinical_notes'], 0, 60)) . (strlen($v['clinical_notes']) > 60 ? '…' : '') ?></td>
        <td><?= !empty($v['is_flagged']) ? (empty($v['flag_resolved_at']) ? '<span class="badge badge-flagged">Active</span>' : '<span class="badge badge-active">Resolved</span>') : '—' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Weight Log</h3></div>
  <?php if (empty($weightHistory)): ?>
  <div class="empty-state">No weight readings logged yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Weight</th></tr></thead>
    <tbody>
      <?php foreach ($weightHistory as $w): ?>
      <tr><td><?= date('j M Y', strtotime($w['logged_at'])) ?></td><td><?= esc($w['weight_kg']) ?> kg</td></tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
