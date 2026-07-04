<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a href="<?= site_url('admin/members') ?>" class="back-link">← Back to members</a>
<div class="card" style="max-width:640px">
  <div class="card-head"><h3><?= esc($member['first_name'].' '.$member['last_name']) ?></h3><?= statusBadge($member['status']) ?></div>
  <div style="padding:20px;display:grid;gap:10px">
    <?php foreach (['Email'=>$member['email'],'Phone'=>$member['phone']??'—','Role'=>roleLabel($member['role']),'Joined'=>date('j F Y',strtotime($member['created_at'])),'Last login'=>$member['last_login_at']?date('j F Y',strtotime($member['last_login_at'])):'Never'] as $k=>$v): ?>
    <div style="display:flex;gap:16px;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.84rem"><span style="color:var(--slate-light);width:100px;flex-shrink:0"><?= esc($k) ?></span><strong><?= esc($v) ?></strong></div>
    <?php endforeach ?>
  </div>
</div>
<?php if (!empty($goats)): ?>
<div class="card" style="max-width:640px">
  <div class="card-head"><h3>Goats in portfolio (<?= count($goats) ?>)</h3></div>
  <table>
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($goats as $g): ?>
      <tr><td><span class="tag"><?= esc($g['tag_number']) ?></span></td><td><?= esc($g['name']) ?></td><td><?= esc($g['breed']??'—') ?></td><td><?= statusBadge($g['status']) ?></td></tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<?php endif ?>
<?= $this->endSection() ?>
