<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
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
    <a href="<?= site_url('admin/members/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>

  <?php if (empty($members)): ?>
  <div class="empty-state">No members yet — applications will appear here once approved.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Email</th><th>Phone</th><th>Goats</th>
        <th>Joined</th><th>Last login</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar"><?= esc(initials($m['first_name'] ?? '', $m['last_name'] ?? '')) ?></div>
            <div>
              <a href="<?= site_url('admin/members/' . $m['id']) ?>" style="font-weight:600;color:var(--blue)">
                <?= esc(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? '')) ?>
              </a>
            </div>
          </div>
        </td>
        <td><?= esc($m['email']) ?></td>
        <td><?= esc($m['phone'] ?? '—') ?></td>
        <td><?= esc($m['goat_count'] ?? 0) ?></td>
        <td><?= date('j M Y', strtotime($m['created_at'])) ?></td>
        <td><?= $m['last_login_at'] ? date('j M Y', strtotime($m['last_login_at'])) : 'Never' ?></td>
        <td><?= statusBadge($m['status']) ?></td>
        <td>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="<?= site_url('admin/members/' . $m['id']) ?>" class="btn btn-ghost btn-sm">View</a>
            <?php if ($m['status'] === 'active'): ?>
            <?= form_open('admin/members/' . $m['id'] . '/deactivate', ['style' => 'display:inline']) ?>
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:var(--red)"
                      data-confirm="Deactivate <?= esc($m['first_name']) ?>'s account?">
                Deactivate
              </button>
            <?= form_close() ?>
            <?php else: ?>
            <?= form_open('admin/members/' . $m['id'] . '/reactivate', ['style' => 'display:inline']) ?>
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-green btn-sm"
                      data-confirm="Reactivate <?= esc($m['first_name']) ?>'s account?">
                Reactivate
              </button>
            <?= form_close() ?>
            <?php endif ?>
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
