<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Overview</div>
  <a href="<?= site_url('manager/dashboard') ?>" class="sb-item <?= uri_string() === 'manager/dashboard' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>

  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item <?= str_starts_with(uri_string(), 'manager/herd') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item <?= str_starts_with(uri_string(), 'manager/health') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
    Health Flags
    <?php if (($flagCount ?? 0) > 0): ?>
    <span class="sb-badge"><?= esc($flagCount ?? 0) ?></span>
    <?php endif ?>
  </a>

  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item <?= str_starts_with(uri_string(), 'manager/members') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    Members
  </a>

  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item <?= str_starts_with(uri_string(), 'manager/schedule') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item <?= str_starts_with(uri_string(), 'manager/reports') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-4">
  <div class="stat-card stat-amber">
    <div class="stat-label">Total Herd</div>
    <div class="stat-val"><?= esc($herdStats['total'] ?? 0) ?></div>
    <div class="stat-sub">Active animals</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg></div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Health Flags</div>
    <div class="stat-val"><?= esc($herdStats['flagged'] ?? 0) ?></div>
    <div class="stat-sub">Require attention</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>
  </div>
  <div class="stat-card stat-blue">
    <div class="stat-label">Active Members</div>
    <div class="stat-val"><?= esc($memberCount ?? 0) ?></div>
    <div class="stat-sub">Goat Banking</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Scheduled Today</div>
    <div class="stat-val"><?= esc($todayTaskCount ?? 0) ?></div>
    <div class="stat-sub">Vet tasks</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
  </div>
</div>

<div class="grid-2">

  <!-- Active health flags -->
  <div class="card">
    <div class="card-head">
      <h3>🚨 Active Health Flags</h3>
      <a href="<?= site_url('manager/health') ?>" class="btn btn-outline btn-sm">View all</a>
    </div>
    <?php if (empty($activeFlags)): ?>
    <div class="empty-state">No active health flags ✅</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Tag</th><th>Animal</th><th>Issue summary</th><th>Vet</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($activeFlags, 0, 5) as $flag): ?>
        <tr>
          <td><span class="tag"><?= esc($flag['tag_number']) ?></span></td>
          <td><?= esc($flag['goat_name']) ?></td>
          <td><?= esc(substr($flag['flag_reason'], 0, 45)) . (strlen($flag['flag_reason']) > 45 ? '…' : '') ?></td>
          <td><?= esc($flag['vet_first'] . ' ' . $flag['vet_last']) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <!-- Today's vet schedule -->
  <div class="card">
    <div class="card-head">
      <h3>📅 Today's Vet Schedule</h3>
      <a href="<?= site_url('manager/schedule') ?>" class="btn btn-outline btn-sm">Full schedule</a>
    </div>
    <?php if (empty($todayTasks)): ?>
    <div class="empty-state">No tasks scheduled for today</div>
    <?php else: ?>
    <div class="timeline" style="max-height:320px;overflow-y:auto;">
      <?php foreach ($todayTasks as $task): ?>
      <div class="tl-item">
        <div class="tl-dot <?= $task['status'] === 'completed' ? 'tl-green' : 'tl-amber' ?>"></div>
        <div class="tl-content">
          <p><?= esc($task['task']) ?></p>
          <span><?= esc($task['animals_desc'] ?? '') ?></span>
          <div class="tl-meta">
            <small><?= date('g:i A', strtotime($task['scheduled_at'])) ?></small>
            <?php if ($task['assigned_vet_id']): ?>
            <span class="tl-vet">Assigned vet</span>
            <?php endif ?>
            <?php if ($task['status'] === 'completed'): ?>
            <span style="color:var(--green);font-size:0.74rem;font-weight:600;">✓ Done</span>
            <?php endif ?>
          </div>
        </div>
      </div>
      <?php endforeach ?>
    </div>
    <?php endif ?>
  </div>

</div>

<!-- Recent members -->
<div class="card">
  <div class="card-head">
    <h3>👥 Recent Goat Banking Members</h3>
    <a href="<?= site_url('manager/members') ?>" class="btn btn-outline btn-sm">All members</a>
  </div>
  <?php if (empty($recentMembers)): ?>
  <div class="empty-state">No members yet</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Name</th><th>Phone</th><th>Goats</th><th>Joined</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($recentMembers as $m): ?>
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar"><?= esc(initials($m['first_name'] ?? '', $m['last_name'] ?? '')) ?></div>
            <?= esc(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? '')) ?>
          </div>
        </td>
        <td><?= esc($m['phone'] ?? '—') ?></td>
        <td><?= esc($m['goat_count'] ?? 0) ?></td>
        <td><?= date('j M Y', strtotime($m['created_at'])) ?></td>
        <td><?= statusBadge($m['status']) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
