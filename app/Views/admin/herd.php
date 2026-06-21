<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-3" style="margin-bottom:24px">
  <div class="stat-card stat-blue">
    <div class="stat-label">Total animals</div>
    <div class="stat-val"><?= esc($stats['total'] ?? 0) ?></div>
    <div class="stat-sub">Active in herd</div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Health flags</div>
    <div class="stat-val"><?= esc($stats['flagged'] ?? 0) ?></div>
    <div class="stat-sub">Unresolved</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">In Banking</div>
    <div class="stat-val"><?= esc($stats['in_banking'] ?? 0) ?></div>
    <div class="stat-sub">Assigned to members</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <h3>Full Herd Registry</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search tag, name, breed…"
             data-search-table="adminHerdTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:220px">
      <a href="<?= site_url('admin/herd/create') ?>" class="btn btn-primary btn-sm">+ Add animal</a>
    </div>
  </div>

  <?php if (empty($herd)): ?>
  <div class="empty-state">
    No animals in the herd yet.
    <a href="<?= site_url('admin/herd/create') ?>">Add the first animal →</a>
  </div>
  <?php else: ?>
  <table id="adminHerdTable">
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
          <?php if (!empty($goat['first_name'])): ?>
            <a href="<?= site_url('admin/members/' . $goat['member_id']) ?>"
               style="color:var(--blue);font-weight:500">
              <?= esc($goat['first_name'] . ' ' . $goat['last_name']) ?>
            </a>
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
          <a href="<?= site_url('admin/herd/' . $goat['id'] . '/edit') ?>"
             class="btn btn-ghost btn-sm">Edit</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <div style="padding:12px 20px;font-size:0.8rem;color:var(--slate-light);border-top:1px solid var(--border)">
    <?= count($herd) ?> animal<?= count($herd) !== 1 ? 's' : '' ?> total
  </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
