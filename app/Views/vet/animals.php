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
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item active">
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

<div class="card">
  <div class="card-head">
    <h3>Animal Health Records</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search by tag or name…"
             data-search-table="animalsTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:220px">
    </div>
  </div>
  <?php if (empty($animals ?? [])): ?>
  <div class="empty-state">No animals found in the herd</div>
  <?php else: ?>
  <table id="animalsTable">
    <thead>
      <tr>
        <th>Tag</th>
        <th>Name</th>
        <th>Breed</th>
        <th>Age</th>
        <th>Weight</th>
        <th>Health</th>
        <th>Last Visit</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($animals as $a): ?>
      <tr>
        <td><span class="tag"><?= esc($a['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($a['name']) ?></td>
        <td><?= esc($a['breed'] ?? '—') ?></td>
        <td><?= esc(goatAge($a['dob'] ?? null)) ?></td>
        <td><?= $a['latest_weight'] ? esc($a['latest_weight']) . ' kg' : '—' ?></td>
        <td>
          <?php if (!empty($a['is_flagged'])): ?>
          <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
          <span class="badge badge-active">Healthy</span>
          <?php endif ?>
        </td>
        <td><?= $a['weight_date'] ? date('j M Y', strtotime($a['weight_date'])) : '—' ?></td>
        <td>
          <a href="<?= site_url('vet/visits/log?tag=' . urlencode($a['tag_number'])) ?>"
             class="btn btn-primary btn-sm">Log visit</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
