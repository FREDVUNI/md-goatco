<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications</a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members</a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd</a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>Staff Accounts</a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/></svg>Settings</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Staff Accounts</h3><a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary btn-sm">+ Create account</a></div>
  <?php if (empty($staff)): ?>
  <div class="empty-state">No staff accounts yet. <a href="<?= site_url('admin/staff/create') ?>">Create the first →</a></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last login</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($staff as $s): ?>
      <tr>
        <td><div class="avatar-cell"><div class="avatar"><?= strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1)) ?></div><?= esc($s['first_name'].' '.$s['last_name']) ?></div></td>
        <td><?= esc($s['email']) ?></td>
        <td><span class="badge badge-pending"><?= esc(roleLabel($s['role'])) ?></span></td>
        <td><?= statusBadge($s['status']) ?></td>
        <td><?= $s['last_login_at'] ? date('j M Y', strtotime($s['last_login_at'])) : 'Never' ?></td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="<?= site_url('admin/staff/'.$s['id'].'/edit') ?>" class="btn btn-ghost btn-sm">Edit</a>
            <?= form_open('admin/staff/'.$s['id'].'/reset-password',['style'=>'display:inline']) ?><?= csrf_field() ?><button class="btn btn-ghost btn-sm" data-confirm="Reset password for <?= esc($s['first_name']) ?>?">Reset PW</button><?= form_close() ?>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
