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
  <a href="<?= site_url('manager/herd') ?>" class="sb-item active">
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
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>Full Herd Registry</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search tag, name, breed…"
             data-search-table="herdTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:220px">
      <a href="<?= site_url('manager/herd/create') ?>" class="btn btn-primary btn-sm">+ Add animal</a>
    </div>
  </div>

  <?php if (empty($herd)): ?>
  <div class="empty-state">No animals in the herd yet</div>
  <?php else: ?>
  <table id="herdTable">
    <thead>
      <tr>
        <th>Tag</th><th>Name</th><th>Breed</th><th>Sex</th>
        <th>Age</th><th>Weight</th><th>Pen</th>
        <th>Member owner</th><th>Health</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($herd as $goat): ?>
      <tr>
        <td><span class="tag"><?= esc($goat['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($goat['name']) ?></td>
        <td><?= esc($goat['breed'] ?? '—') ?></td>
        <td><?= esc(ucfirst($goat['sex'] ?? '—')) ?></td>
        <td><?= esc(goatAge($goat['dob'] ?? null)) ?></td>
        <td><?= $goat['latest_weight'] ? esc($goat['latest_weight']) . ' kg' : '—' ?></td>
        <td><?= esc($goat['pen_id'] ?? '—') ?></td>
        <td>
          <?php if ($goat['first_name']): ?>
          <?= esc($goat['first_name'] . ' ' . $goat['last_name']) ?>
          <?php else: ?>
          <span style="color:var(--slate-light)">Unassigned</span>
          <?php endif ?>
        </td>
        <td>
          <?php if (!empty($goat['is_flagged'])): ?>
          <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
          <span class="badge badge-active">Healthy</span>
          <?php endif ?>
        </td>
        <td>
          <a href="<?= site_url('manager/herd/' . $goat['id'] . '/edit') ?>" class="btn btn-ghost btn-sm">Edit</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <div style="padding:12px 20px;font-size:0.8rem;color:var(--slate-light);border-top:1px solid var(--border)">
    <?= count($herd) ?> animal<?= count($herd) !== 1 ? 's' : '' ?> in herd
  </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
