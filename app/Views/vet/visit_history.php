<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Veterinarian</div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/></svg>Today's Tasks</a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/></svg>Log a Visit</a>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History</a>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animals</a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>Health Flags</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Visit History</h3>
    <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-primary btn-sm">+ Log new visit</a>
  </div>
  <?php if (empty($visits)): ?>
    <div class="empty-state">No visits logged yet. <a href="<?= site_url('vet/visits/log') ?>">Log your first →</a></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Animal</th><th>Type</th><th>Date</th><th>Outcome</th><th>Flagged</th><th>Notes</th></tr></thead>
    <tbody>
      <?php foreach ($visits as $v): ?>
      <tr>
        <td><span class="tag"><?= esc($v['tag_number']??'—') ?></span></td>
        <td style="font-weight:600"><?= esc($v['goat_name']??'—') ?></td>
        <td><?= esc(visitTypeLabel($v['visit_type']??'routine')) ?></td>
        <td><?= date('j M Y', strtotime($v['visit_date'])) ?></td>
        <td><span class="badge <?= ($v['outcome']??'')==='healthy'?'badge-active':'badge-pending' ?>"><?= esc(ucfirst($v['outcome']??'—')) ?></span></td>
        <td><?= ($v['is_flagged']??0) ? '<span class="badge badge-flagged">🚨 Yes</span>' : '—' ?></td>
        <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= esc(substr($v['clinical_notes']??'—',0,60)) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
