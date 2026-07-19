<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Applications</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($pager->getTotal() ?? count($pending??[])) ?> pending</span></div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search by name or phone…" value="<?= esc($search ?? '') ?>">
    </form>
    <a href="<?= site_url('admin/applications/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>
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
  <?= $pager->links('default', 'dashboard') ?>
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
