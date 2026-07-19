<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>Goat Banking Members</h3>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search members…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
  </div>

  <?php if (empty($members)): ?>
  <div class="empty-state">No active members yet</div>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>Name</th><th>Phone</th><th>Goats</th><th>Joined</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
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
        <td>
          <a href="<?= site_url('manager/members/' . $m['id']) ?>" class="btn btn-ghost btn-sm">View portfolio</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
