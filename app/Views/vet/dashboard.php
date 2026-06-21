<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role sb-role-green">Veterinarian</div>
<nav class="sb-nav">
  <div class="sb-section">My Work</div>
  <a href="<?= site_url('vet/dashboard') ?>" class="sb-item <?= uri_string() === 'vet/dashboard' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    My Dashboard
  </a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item <?= str_starts_with(uri_string(), 'vet/tasks') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item <?= uri_string() === 'vet/visits/log' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>
    Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item <?= str_starts_with(uri_string(), 'vet/animals') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item <?= str_starts_with(uri_string(), 'vet/flags') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    My Flags
    <?php if (($flagCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($flagCount) ?></span><?php endif ?>
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item <?= uri_string() === 'vet/visits/history' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-4">
  <div class="stat-card stat-amber">
    <div class="stat-label">Tasks Today</div>
    <div class="stat-val"><?= count($recentVisits ?? []) ?></div>
    <div class="stat-sub">Logged this week</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Visits logged</div>
    <div class="stat-val"><?= count($recentVisits ?? []) ?></div>
    <div class="stat-sub">This week</div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">My Flags</div>
    <div class="stat-val"><?= esc($flagCount ?? 0) ?></div>
    <div class="stat-sub">Active, unresolved</div>
  </div>
  <div class="stat-card stat-blue">
    <div class="stat-label">Quick action</div>
    <div class="stat-val" style="font-size:1rem;margin-top:8px">
      <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-primary btn-sm">+ Log a visit</a>
    </div>
  </div>
</div>

<div class="grid-2">
  <!-- Active flags -->
  <div class="card">
    <div class="card-head">
      <h3>🚨 My Active Flags</h3>
      <a href="<?= site_url('vet/flags') ?>" class="btn btn-outline btn-sm">View all</a>
    </div>
    <?php if (empty($myFlags)): ?>
    <div class="empty-state">No active flags — great work! ✅</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Tag</th><th>Animal</th><th>Issue</th><th>Days open</th><th></th></tr></thead>
      <tbody>
        <?php foreach (array_slice($myFlags, 0, 5) as $flag): ?>
        <tr>
          <td><span class="tag"><?= esc($flag['tag_number']) ?></span></td>
          <td><?= esc($flag['goat_name']) ?></td>
          <td><?= esc(substr($flag['flag_reason'], 0, 40)) . (strlen($flag['flag_reason']) > 40 ? '…' : '') ?></td>
          <td><?= floor((time() - strtotime($flag['created_at'])) / 86400) ?></td>
          <td>
            <a href="<?= site_url('vet/visits/log?tag=' . $flag['tag_number']) ?>" class="btn btn-primary btn-sm">Log update</a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <!-- Recent visits -->
  <div class="card">
    <div class="card-head">
      <h3>📋 Recent Visits</h3>
      <a href="<?= site_url('vet/visits/history') ?>" class="btn btn-outline btn-sm">Full history</a>
    </div>
    <?php if (empty($recentVisits)): ?>
    <div class="empty-state">No visits logged yet</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Tag</th><th>Animal</th><th>Type</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($recentVisits, 0, 6) as $v): ?>
        <tr>
          <td><span class="tag"><?= esc($v['tag_number']) ?></span></td>
          <td><?= esc($v['goat_name']) ?></td>
          <td><?= esc(str_replace('_', ' ', ucfirst($v['visit_type']))) ?></td>
          <td><?= date('j M', strtotime($v['visit_date'])) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>
</div>

<?= $this->endSection() ?>
