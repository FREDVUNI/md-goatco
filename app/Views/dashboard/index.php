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
  <div class="sb-role">Super Administrator</div>
  <nav class="sb-nav">
    <div class="sb-section">Main</div>
    <a href="<?= site_url('dashboard') ?>" class="sb-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="<?= site_url('admin/applications') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Applications
      <?php if (($pendingCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($pendingCount) ?></span><?php endif ?>
    </a>
    <a href="<?= site_url('admin/members') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Members
    </a>
    <div class="sb-section">Farm</div>
    <a href="<?= site_url('admin/herd') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
      Herd Overview
    </a>
    <div class="sb-section">Staff</div>
    <a href="<?= site_url('admin/staff') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2M4.93 4.93l1.41 1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2"/></svg>
      Staff Accounts
    </a>
    <div class="sb-section">System</div>
    <a href="<?= site_url('admin/settings') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      Settings
    </a>
  </nav>

<?php elseif ($isManager): ?>
  <div class="sb-role">Farm Manager</div>
  <nav class="sb-nav">
    <div class="sb-section">Main</div>
    <a href="<?= site_url('dashboard') ?>" class="sb-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <div class="sb-section">Herd</div>
    <a href="<?= site_url('manager/herd') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
      Herd Registry
    </a>
    <a href="<?= site_url('manager/health') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      Health Flags
      <?php if (($flagCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($flagCount) ?></span><?php endif ?>
    </a>
    <div class="sb-section">Members</div>
    <a href="<?= site_url('manager/members') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Members
    </a>
    <div class="sb-section">Veterinary</div>
    <a href="<?= site_url('manager/schedule') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Vet Schedule
      <?php if (($todayTaskCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($todayTaskCount) ?></span><?php endif ?>
    </a>
    <div class="sb-section">Reports</div>
    <a href="<?= site_url('manager/reports') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </a>
  </nav>

<?php elseif ($isVet): ?>
  <div class="sb-role">Veterinarian</div>
  <nav class="sb-nav">
    <div class="sb-section">Main</div>
    <a href="<?= site_url('dashboard') ?>" class="sb-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="<?= site_url('vet/tasks') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      Today's Tasks
    </a>
    <div class="sb-section">Visits</div>
    <a href="<?= site_url('vet/visits/log') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
      Log a Visit
    </a>
    <a href="<?= site_url('vet/visits/history') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Visit History
    </a>
    <div class="sb-section">Animals</div>
    <a href="<?= site_url('vet/animals') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
      Animal Records
    </a>
    <a href="<?= site_url('vet/flags') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
      Health Flags
      <?php if (($flagCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($flagCount) ?></span><?php endif ?>
    </a>
  </nav>

<?php else: ?><!-- MEMBER -->
  <div class="sb-profile">
    <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1) . substr($currentUser['last_name'] ?? '', 0, 1))) ?></div>
    <div class="sb-profile-name"><?= esc(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? '')) ?></div>
    <div class="sb-profile-meta"><?= esc($goatCount ?? 0) ?> goat<?= ($goatCount ?? 0) !== 1 ? 's' : '' ?> in portfolio</div>
  </div>
  <nav class="sb-nav">
    <div class="sb-section">My Portfolio</div>
    <a href="<?= site_url('dashboard') ?>" class="sb-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="<?= site_url('member/goats') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
      My Goats
    </a>
    <div class="sb-section">Finances</div>
    <a href="<?= site_url('member/statements') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/></svg>
      Statements
    </a>
    <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Top Up Wallet
    </a>
    <div class="sb-section">Account</div>
    <a href="<?= site_url('member/account') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      My Account
    </a>
    <a href="<?= site_url('member/support') ?>" class="sb-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      Support
    </a>
  </nav>
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
