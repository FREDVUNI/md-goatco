<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Staff Accounts</h3><a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary btn-sm">+ Create account</a></div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search by name or email…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
    <a href="<?= site_url('admin/staff/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>
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
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
