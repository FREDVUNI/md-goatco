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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/></svg>Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item">
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
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>My Visit History</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search visits…"
             data-search-table="historyTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:200px">
      <a href="<?= site_url('vet/visits/history?export=csv') ?>" class="btn btn-ghost btn-sm">📥 CSV</a>
    </div>
  </div>

  <?php if (empty($visits)): ?>
  <div class="empty-state">No visits logged yet. <a href="<?= site_url('vet/visits/log') ?>">Log your first visit →</a></div>
  <?php else: ?>
  <table id="historyTable">
    <thead>
      <tr>
        <th>Tag</th><th>Animal</th><th>Visit type</th>
        <th>Weight</th><th>Flagged</th><th>Notes (excerpt)</th><th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($visits as $v): ?>
      <tr>
        <td><span class="tag"><?= esc($v['tag_number']) ?></span></td>
        <td style="font-weight:600"><?= esc($v['goat_name']) ?></td>
        <td><?= esc(visitTypeLabel($v['visit_type'])) ?></td>
        <td><?= $v['weight_kg'] ? esc($v['weight_kg']) . ' kg' : '—' ?></td>
        <td>
          <?php if ($v['is_flagged']): ?>
          <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
          <span style="color:var(--green);font-size:0.82rem;font-weight:600">✓ Clear</span>
          <?php endif ?>
        </td>
        <td style="max-width:220px;overflow:hidden">
          <?= esc(substr($v['clinical_notes'], 0, 60)) . (strlen($v['clinical_notes']) > 60 ? '…' : '') ?>
        </td>
        <td style="white-space:nowrap"><?= date('j M Y', strtotime($v['visit_date'])) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <div style="padding:14px 20px;font-size:0.8rem;color:var(--slate-light);border-top:1px solid var(--border)">
    <?= count($visits) ?> visit<?= count($visits) !== 1 ? 's' : '' ?> in history
  </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
