<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications<?php if(($pendingCount??0)>0): ?><span class="sb-badge"><?= esc($pendingCount) ?></span><?php endif ?></a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members</a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd</a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>Staff Accounts</a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Settings</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Applications</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= count($pending??[]) ?> pending</span></div>
  <?php if (empty($pending)): ?>
    <div class="empty-state">No pending applications 🎉</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Applicant</th><th>Email</th><th>Goats</th><th>Submitted</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach (($pending??[]) as $app): ?>
      <tr>
        <td><div class="avatar-cell"><div class="avatar"><?= strtoupper(substr($app['first_name'],0,1).substr($app['last_name'],0,1)) ?></div><?= esc($app['first_name'].' '.$app['last_name']) ?></div></td>
        <td><?= esc($app['email']??'—') ?></td>
        <td><?= esc($app['goats_requested']??1) ?></td>
        <td><?= date('j M Y', strtotime($app['created_at'])) ?></td>
        <td>
          <div style="display:flex;gap:8px">
            <a href="<?= site_url('admin/applications/'.$app['id']) ?>" class="btn btn-primary btn-sm">Review</a>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?php if (!empty($approved)): ?>
<div class="card">
  <div class="card-head"><h3>Recently approved</h3></div>
  <table>
    <thead><tr><th>Name</th><th>Approved</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($approved as $app): ?>
      <tr>
        <td><?= esc($app['first_name'].' '.$app['last_name']) ?></td>
        <td><?= date('j M Y', strtotime($app['reviewed_at']??$app['created_at'])) ?></td>
        <td><span class="badge badge-active">Approved</span></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<?php endif ?>
<?= $this->endSection() ?>
