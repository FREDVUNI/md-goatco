<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Farm Management<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-3" style="margin-bottom:24px">
  <div class="stat-card stat-blue">
    <div class="stat-label">Total animals</div>
    <div class="stat-val"><?= esc($stats['total'] ?? 0) ?></div>
    <div class="stat-sub">Active in herd</div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Health flags</div>
    <div class="stat-val"><?= esc($stats['flagged'] ?? 0) ?></div>
    <div class="stat-sub">Unresolved</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">In Banking</div>
    <div class="stat-val"><?= esc($stats['in_banking'] ?? 0) ?></div>
    <div class="stat-sub">Assigned to members</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <h3>Herd Registry</h3>
    <a href="<?= site_url('manager/herd/create') ?>" class="btn btn-primary btn-sm">+ Add animal</a>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tag, name, breed…" value="<?= esc($search ?? '') ?>">
    </form>
    <a href="<?= site_url('manager/herd/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>

  <?php if (empty($herd)): ?>
  <div class="empty-state">No animals in the herd yet.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Tag</th><th>Name</th><th>Breed</th><th>Sex</th>
        <th>Age</th><th>Weight</th><th>Pen</th>
        <th>Member owner</th><th>Health</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($herd as $goat): ?>
      <tr>
        <td><span class="tag"><?= esc($goat['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($goat['name']) ?></td>
        <td><?= esc($goat['breed'] ?? '—') ?></td>
        <td><?= esc(ucfirst($goat['sex'] ?? '—')) ?></td>
        <td><?= esc(goatAge($goat['dob'] ?? null)) ?></td>
        <td><?= $goat['latest_weight'] ? esc($goat['latest_weight']) . ' kg' : '—' ?></td>
        <td><?= esc($goat['pen_id'] ?? '—') ?></td>
        <td>
          <?php if (!empty($goat['first_name'])): ?>
            <?= esc($goat['first_name'] . ' ' . $goat['last_name']) ?>
          <?php else: ?>
            <span style="color:var(--slate-light)">Unassigned</span>
          <?php endif ?>
        </td>
        <td>
          <?php if (!empty($goat['is_flagged'])): ?>
          <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
          <span class="badge badge-active">Healthy</span>
          <?php endif ?>
        </td>
        <td><a href="<?= site_url('manager/herd/' . $goat['id'] . '/edit') ?>" class="btn btn-ghost btn-sm">Edit</a></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
