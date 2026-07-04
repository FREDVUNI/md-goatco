<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Veterinarian</div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/></svg>Log a Visit</a>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records</a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>Health Flags</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Animal Records</h3>
    <input type="text" placeholder="Search tag or name…" data-search-table="animalsTable" style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:0.84rem;width:200px">
  </div>
  <?php if (empty($herd)): ?>
    <div class="empty-state">No animals in the herd yet</div>
  <?php else: ?>
  <table id="animalsTable">
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Sex</th><th>Age</th><th>Pen</th><th>Health</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($herd as $g): ?>
      <tr>
        <td><span class="tag"><?= esc($g['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($g['name']) ?></td>
        <td><?= esc($g['breed']??'—') ?></td>
        <td><?= esc(ucfirst($g['sex']??'—')) ?></td>
        <td><?= esc(goatAge($g['dob']??null)) ?></td>
        <td><?= esc($g['pen_id']??'—') ?></td>
        <td><?= ($g['is_flagged']??0) ? '<span class="badge badge-flagged">Flagged</span>' : '<span class="badge badge-active">Healthy</span>' ?></td>
        <td>
          <a href="<?= site_url('vet/visits/log?goat_id='.$g['id']) ?>" class="btn btn-primary btn-sm">Log visit</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
