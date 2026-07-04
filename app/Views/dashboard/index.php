<?= $this->extend('layouts/dashboard') ?>

<?php
$role      = $currentUser['role'] ?? 'member';
$isAdmin   = $role === 'super_admin';
$isManager = $role === 'manager';
$isVet     = $role === 'vet';
$isMember  = $role === 'member';
$greeting  = date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening');
?>

<?= $this->section('portalName') ?>
<?php if ($isAdmin): ?>Administration
<?php elseif ($isManager): ?>Farm Management
<?php elseif ($isVet): ?>Veterinary Portal
<?php else: ?>Goat Banking
<?php endif ?>
<?= $this->endSection() ?>

<!-- ══════════════ SIDEBAR ══════════════ -->
<?= $this->section('sidebar') ?>

<?php if ($isAdmin): ?>
  <?= $this->include('admin/_sidebar') ?>
<?php elseif ($isManager): ?>
  <?= $this->include('manager/_sidebar') ?>
<?php elseif ($isVet): ?>
  <?= $this->include('vet/_sidebar') ?>
<?php else: ?>
  <?= $this->include('member/_sidebar') ?>
<?php endif ?>

<?= $this->endSection() ?>

<!-- ══════════════ CONTENT ══════════════ -->
<?= $this->section('content') ?>

<?php if ($isAdmin): ?>
<!-- ─────────── ADMIN CONTENT ─────────── -->
<div class="welcome-banner">
  <div>
    <h2>Good <?= $greeting ?>, <?= esc($currentUser['first_name']) ?>! 👋</h2>
    <p>Here's your farm overview for <?= date('l, j F Y') ?></p>
    <div class="wb-stats">
      <div class="wb-stat"><strong><?= esc($totalMembers ?? 0) ?></strong><span>Members</span></div>
      <div class="wb-stat"><strong><?= esc($pendingCount ?? 0) ?></strong><span>Pending</span></div>
      <div class="wb-stat"><strong><?= esc($goatStats['total'] ?? 0) ?></strong><span>Goats</span></div>
      <div class="wb-stat"><strong><?= esc($goatStats['flagged'] ?? 0) ?></strong><span>Flags</span></div>
    </div>
  </div>
</div>

<div class="stat-grid stat-grid-4">
  <div class="stat-card stat-blue">
    <div class="stat-label">Total Members</div>
    <div class="stat-val"><?= esc($totalMembers ?? 0) ?></div>
    <div class="stat-sub">Active Goat Banking accounts</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Pending Review</div>
    <div class="stat-val"><?= esc($pendingCount ?? 0) ?></div>
    <div class="stat-sub">Applications awaiting</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Total Goats</div>
    <div class="stat-val"><?= esc($goatStats['total'] ?? 0) ?></div>
    <div class="stat-sub"><?= esc($goatStats['in_banking'] ?? 0) ?> in Banking</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg></div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Health Flags</div>
    <div class="stat-val"><?= esc($goatStats['flagged'] ?? 0) ?></div>
    <div class="stat-sub">Active, unresolved</div>
    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>
  </div>
</div>

<div class="grid-2">
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
              <div class="avatar"><?= strtoupper(substr($app['first_name'] ?? 'A', 0, 1) . substr($app['last_name'] ?? '', 0, 1)) ?></div>
              <?= esc(($app['first_name'] ?? '') . ' ' . ($app['last_name'] ?? '')) ?>
            </div>
          </td>
          <td><?= date('j M Y', strtotime($app['created_at'])) ?></td>
          <td><a href="<?= site_url('admin/applications/' . $app['id']) ?>" class="btn btn-primary btn-sm">Review</a></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <div class="card">
    <div class="card-head">
      <h3>👥 Staff Overview</h3>
      <a href="<?= site_url('admin/staff') ?>" class="btn btn-outline btn-sm">Manage</a>
    </div>
    <div class="staff-overview">
      <div class="staff-role-row"><div class="role-chip role-chip-vet">Veterinarians</div><strong><?= esc($staffCounts['vet'] ?? 0) ?></strong></div>
      <div class="staff-role-row"><div class="role-chip role-chip-manager">Farm Managers</div><strong><?= esc($staffCounts['manager'] ?? 0) ?></strong></div>
      <div class="staff-role-row"><div class="role-chip role-chip-admin">Administrators</div><strong><?= esc($staffCounts['super_admin'] ?? 0) ?></strong></div>
      <a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary" style="margin-top:16px;width:100%;justify-content:center">+ Create staff account</a>
    </div>
  </div>
</div>

<div class="card chart-card">
  <div class="card-head"><h3>📈 Applications — last 6 months</h3></div>
  <?= view('partials/bar_chart', ['labels' => $appLabels ?? [], 'values' => $appValues ?? []]) ?>
</div>

<?php elseif ($isManager): ?>
<!-- ─────────── MANAGER CONTENT ─────────── -->
<div class="welcome-banner">
  <div>
    <h2>Good <?= $greeting ?>, <?= esc($currentUser['first_name']) ?>! 👋</h2>
    <p>Farm overview — <?= date('l, j F Y') ?></p>
    <div class="wb-stats">
      <div class="wb-stat"><strong><?= esc($herdStats['total'] ?? 0) ?></strong><span>Goats</span></div>
      <div class="wb-stat"><strong><?= esc($flagCount ?? 0) ?></strong><span>Flags</span></div>
      <div class="wb-stat"><strong><?= esc($memberCount ?? 0) ?></strong><span>Members</span></div>
      <div class="wb-stat"><strong><?= esc($todayTaskCount ?? 0) ?></strong><span>Tasks today</span></div>
    </div>
  </div>
</div>

<div class="stat-grid stat-grid-4">
  <div class="stat-card stat-blue"><div class="stat-label">Total Goats</div><div class="stat-val"><?= esc($herdStats['total'] ?? 0) ?></div><div class="stat-sub"><?= esc($herdStats['in_banking'] ?? 0) ?> in Banking</div></div>
  <div class="stat-card stat-green"><div class="stat-label">Active Members</div><div class="stat-val"><?= esc($memberCount ?? 0) ?></div><div class="stat-sub">Goat Banking accounts</div></div>
  <div class="stat-card <?= ($flagCount ?? 0) > 0 ? 'stat-red' : 'stat-green' ?>"><div class="stat-label">Health Flags</div><div class="stat-val"><?= esc($flagCount ?? 0) ?></div><div class="stat-sub">Awaiting resolution</div></div>
  <div class="stat-card stat-amber"><div class="stat-label">Tasks Today</div><div class="stat-val"><?= esc($todayTaskCount ?? 0) ?></div><div class="stat-sub">Scheduled vet visits</div></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3>🚩 Active Health Flags</h3><a href="<?= site_url('manager/health') ?>" class="btn btn-outline btn-sm">View all</a></div>
    <?php if (empty($activeFlags)): ?>
      <div class="empty-state">No active health flags — all clear! ✅</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Tag</th><th>Issue</th><th>Flagged</th></tr></thead>
      <tbody>
        <?php foreach ($activeFlags as $flag): ?>
        <tr>
          <td><span class="tag"><?= esc($flag['tag_number'] ?? '—') ?></span> <?= esc($flag['goat_name'] ?? '') ?></td>
          <td><?= esc(substr($flag['flag_reason'] ?? '—', 0, 50)) ?><?= strlen($flag['flag_reason'] ?? '') > 50 ? '…' : '' ?></td>
          <td><?= date('j M', strtotime($flag['created_at'] ?? 'now')) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <div class="card">
    <div class="card-head"><h3>📅 Today's Vet Schedule</h3><a href="<?= site_url('manager/schedule') ?>" class="btn btn-outline btn-sm">Full schedule</a></div>
    <?php if (empty($todayTasks)): ?>
      <div class="empty-state">No vet visits scheduled for today</div>
    <?php else: ?>
    <div class="timeline">
      <?php foreach ($todayTasks as $task): ?>
      <div class="tl-item">
        <div class="tl-dot tl-<?= ($task['status'] ?? '') === 'completed' ? 'green' : 'blue' ?>"></div>
        <div class="tl-content">
          <p><?= esc($task['task'] ?? 'Visit') ?></p>
          <small><?= date('g:i A', strtotime($task['scheduled_at'])) ?></small>
        </div>
      </div>
      <?php endforeach ?>
    </div>
    <?php endif ?>
  </div>
</div>

<div class="card chart-card">
  <div class="card-head"><h3>🚩 Health flags — last 6 months</h3></div>
  <?= view('partials/bar_chart', ['labels' => $flagLabels ?? [], 'values' => $flagValues ?? []]) ?>
</div>

<?php elseif ($isVet): ?>
<!-- ─────────── VET CONTENT ─────────── -->
<div class="welcome-banner">
  <div>
    <h2>Good <?= $greeting ?>, <?= esc($currentUser['first_name']) ?>! 👋</h2>
    <p>Your veterinary dashboard — <?= date('l, j F Y') ?></p>
    <div class="wb-stats">
      <div class="wb-stat"><strong><?= esc($flagCount ?? 0) ?></strong><span>Active flags</span></div>
      <div class="wb-stat"><strong><?= count($recentVisits ?? []) ?></strong><span>Recent visits</span></div>
    </div>
  </div>
</div>

<div class="stat-grid stat-grid-3">
  <div class="stat-card <?= ($flagCount ?? 0) > 0 ? 'stat-red' : 'stat-green' ?>">
    <div class="stat-label">My Active Flags</div>
    <div class="stat-val"><?= esc($flagCount ?? 0) ?></div>
    <div class="stat-sub">Animals needing attention</div>
  </div>
  <div class="stat-card stat-blue">
    <div class="stat-label">Recent Visits Logged</div>
    <div class="stat-val"><?= count($recentVisits ?? []) ?></div>
    <div class="stat-sub">Showing below</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Quick Log</div>
    <div class="stat-val" style="font-size:1rem;margin-top:8px">
      <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-primary btn-sm">+ Log a visit</a>
    </div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3>🚩 My Active Flags</h3><a href="<?= site_url('vet/flags') ?>" class="btn btn-outline btn-sm">All flags</a></div>
    <?php if (empty($myFlags)): ?>
      <div class="empty-state">No active flags — great work! ✅</div>
    <?php else: ?>
    <table>
      <thead><tr><th>Tag</th><th>Issue</th><th>Flagged</th><th></th></tr></thead>
      <tbody>
        <?php foreach (array_slice($myFlags, 0, 5) as $flag): ?>
        <tr>
          <td><span class="tag"><?= esc($flag['tag_number'] ?? '—') ?></span></td>
          <td><?= esc(substr($flag['flag_reason'] ?? '—', 0, 45)) ?><?= strlen($flag['flag_reason'] ?? '') > 45 ? '…' : '' ?></td>
          <td><?= date('j M', strtotime($flag['created_at'] ?? 'now')) ?></td>
          <td><a href="<?= site_url('vet/visits/log?tag=' . urlencode($flag['tag_number'] ?? '')) ?>" class="btn btn-primary btn-sm">Log update</a></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <div class="card">
    <div class="card-head"><h3>📋 Recent Visits</h3><a href="<?= site_url('vet/visits/history') ?>" class="btn btn-outline btn-sm">Full history</a></div>
    <?php if (empty($recentVisits)): ?>
      <div class="empty-state">No visits logged yet. <a href="<?= site_url('vet/visits/log') ?>">Log your first →</a></div>
    <?php else: ?>
    <div class="timeline">
      <?php foreach (array_slice($recentVisits, 0, 6) as $visit): ?>
      <div class="tl-item">
        <div class="tl-dot tl-<?= ($visit['is_flagged'] ?? 0) ? 'amber' : 'green' ?>"></div>
        <div class="tl-content">
          <p><strong><?= esc($visit['goat_name'] ?? 'Tag: ' . ($visit['tag_number'] ?? '')) ?></strong></p>
          <span><?= esc(substr($visit['clinical_notes'] ?? '', 0, 60)) ?><?= strlen($visit['clinical_notes'] ?? '') > 60 ? '…' : '' ?></span>
          <small><?= date('j M Y', strtotime($visit['visit_date'] ?? 'now')) ?></small>
        </div>
      </div>
      <?php endforeach ?>
    </div>
    <?php endif ?>
  </div>
</div>

<div class="card chart-card">
  <div class="card-head"><h3>📋 Visits logged — last 6 weeks</h3></div>
  <?= view('partials/bar_chart', ['labels' => $visitLabels ?? [], 'values' => $visitValues ?? []]) ?>
</div>

<?php else: ?>
<!-- ─────────── MEMBER CONTENT ─────────── -->
<div class="welcome-banner">
  <div>
    <h2>Good <?= $greeting ?>, <?= esc($currentUser['first_name']) ?>! 👋</h2>
    <p>
      <?php if (($healthyCount ?? 0) === ($goatCount ?? 0) && ($goatCount ?? 0) > 0): ?>
        All <?= esc($goatCount) ?> of your goats are healthy.
      <?php elseif (($goatCount ?? 0) > 0): ?>
        <?= esc($goatCount) ?> goats in your portfolio · <?= esc(($goatCount ?? 0) - ($healthyCount ?? 0)) ?> flagged.
      <?php else: ?>
        Welcome! Your goats will appear here once assigned.
      <?php endif ?>
    </p>
    <div class="wb-stats">
      <div class="wb-stat"><strong><?= esc($goatCount ?? 0) ?></strong><span>Your goats</span></div>
      <div class="wb-stat"><strong><?= esc($healthyCount ?? 0) ?></strong><span>Healthy</span></div>
      <div class="wb-stat"><strong>UGX <?= number_format($balance ?? 0) ?></strong><span>Balance</span></div>
      <div class="wb-stat"><strong>UGX <?= number_format($totalCredited ?? 0) ?></strong><span>Total credited</span></div>
    </div>
  </div>
</div>

<div class="stat-grid stat-grid-3">
  <div class="stat-card stat-blue">
    <div class="stat-label">Goats in portfolio</div>
    <div class="stat-val"><?= esc($goatCount ?? 0) ?></div>
    <div class="stat-sub">All assigned and tagged</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Healthy animals</div>
    <div class="stat-val"><?= esc($healthyCount ?? 0) ?>/<?= esc($goatCount ?? 0) ?></div>
    <div class="stat-sub">Last vet check on record</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Account balance</div>
    <div class="stat-val">UGX <?= number_format($balance ?? 0) ?></div>
    <div class="stat-sub"><a href="<?= site_url('member/wallet/topup') ?>" style="color:var(--amber);font-weight:700">Top up →</a></div>
  </div>
</div>

<?php if (!empty($goats)): ?>
<div class="card">
  <div class="card-head">
    <h3>🐐 Your Goats</h3>
    <a href="<?= site_url('member/goats') ?>" class="btn btn-outline btn-sm">View all →</a>
  </div>
  <table>
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Weight</th><th>Last check</th><th>Health</th></tr></thead>
    <tbody>
      <?php foreach (array_slice($goats, 0, 5) as $goat): ?>
      <tr>
        <td><span class="tag"><?= esc($goat['tag_number']) ?></span></td>
        <td><a href="<?= site_url('member/goats/' . $goat['id']) ?>" class="link-strong"><?= esc($goat['name']) ?></a></td>
        <td><?= esc($goat['breed'] ?? '—') ?></td>
        <td><?= $goat['latest_weight'] ? esc($goat['latest_weight']) . ' kg' : '—' ?></td>
        <td><?= isset($goat['weight_date']) ? date('j M Y', strtotime($goat['weight_date'])) : '—' ?></td>
        <td>
          <?php if (!empty($goat['is_flagged'])): ?>
            <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
            <span class="badge badge-active">Healthy</span>
          <?php endif ?>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<?php endif ?>

<div class="card chart-card">
  <div class="card-head"><h3>⚖️ Average goat weight — last 6 months</h3></div>
  <?= view('partials/bar_chart', ['labels' => $weightLabels ?? [], 'values' => $weightValues ?? []]) ?>
</div>

<?php if (!empty($notifications)): ?>
<div class="card">
  <div class="card-head"><h3>🔔 Recent Activity</h3></div>
  <div class="timeline">
    <?php foreach (array_slice($notifications, 0, 5) as $notif): ?>
    <div class="tl-item">
      <div class="tl-dot tl-<?= esc($notif['type']) ?>"></div>
      <div class="tl-content">
        <p><?= esc($notif['title']) ?></p>
        <span><?= esc($notif['body']) ?></span>
        <small><?= time_ago($notif['created_at']) ?></small>
      </div>
    </div>
    <?php endforeach ?>
  </div>
</div>
<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
