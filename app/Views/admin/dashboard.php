<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item <?= str_starts_with(uri_string(), 'admin/applications') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Applications
    <?php if (($pendingCount ?? 0) > 0): ?>
    <span class="sb-badge"><?= esc($pendingCount) ?></span>
    <?php endif ?>
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item <?= str_starts_with(uri_string(), 'admin/members') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
    Members
  </a>

  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item <?= str_starts_with(uri_string(), 'admin/herd') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Herd Overview
  </a>

  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item <?= str_starts_with(uri_string(), 'admin/staff') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2M4.93 4.93l1.41 1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2"/></svg>
    Staff Accounts
  </a>

  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item <?= str_starts_with(uri_string(), 'admin/settings') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- STAT CARDS -->
<div class="stat-grid stat-grid-4">
  <div class="stat-card stat-blue">
    <div class="stat-label">Total Members</div>
    <div class="stat-val"><?= esc($totalMembers) ?></div>
    <div class="stat-sub">Active Goat Banking accounts</div>
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Pending Review</div>
    <div class="stat-val"><?= esc($pendingCount) ?></div>
    <div class="stat-sub">Applications awaiting</div>
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Total Goats</div>
    <div class="stat-val"><?= esc($goatStats['total'] ?? 0) ?></div>
    <div class="stat-sub"><?= esc($goatStats['in_banking'] ?? 0) ?> in Banking</div>
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    </div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Health Flags</div>
    <div class="stat-val"><?= esc($goatStats['flagged'] ?? 0) ?></div>
    <div class="stat-sub">Active, unresolved</div>
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    </div>
  </div>
</div>

<!-- BOTTOM GRID -->
<div class="grid-2">

  <!-- Pending applications -->
  <div class="card">
    <div class="card-head">
      <h3>⏳ Pending Applications</h3>
      <a href="<?= site_url('admin/applications') ?>" class="btn btn-outline btn-sm">View all</a>
    </div>
    <?php if (empty($recentPending)): ?>
    <div class="empty-state">No pending applications 🎉</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Applicant</th><th>Submitted</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($recentPending, 0, 5) as $app): ?>
        <tr>
          <td>
            <div class="avatar-cell">
              <div class="avatar"><?= strtoupper(substr($app['first_name'], 0, 1) . substr($app['last_name'], 0, 1)) ?></div>
              <?= esc($app['first_name'] . ' ' . $app['last_name']) ?>
            </div>
          </td>
          <td><?= date('j M Y', strtotime($app['created_at'])) ?></td>
          <td>
            <a href="<?= site_url('admin/applications/' . $app['id']) ?>" class="btn btn-primary btn-sm">Review</a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <!-- Staff overview -->
  <div class="card">
    <div class="card-head">
      <h3>👥 Staff Overview</h3>
      <a href="<?= site_url('admin/staff') ?>" class="btn btn-outline btn-sm">Manage staff</a>
    </div>
    <div class="staff-overview">
      <div class="staff-role-row">
        <div class="role-chip role-chip-vet">Veterinarians</div>
        <strong><?= esc($staffCounts['vet'] ?? 0) ?></strong>
      </div>
      <div class="staff-role-row">
        <div class="role-chip role-chip-manager">Farm Managers</div>
        <strong><?= esc($staffCounts['manager'] ?? 0) ?></strong>
      </div>
      <div class="staff-role-row">
        <div class="role-chip role-chip-admin">Administrators</div>
        <strong><?= esc($staffCounts['super_admin'] ?? 0) ?></strong>
      </div>
      <a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary" style="margin-top:16px;width:100%;justify-content:center;">+ Create staff account</a>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
