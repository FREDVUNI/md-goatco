<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>🐐 My Goats</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($goatCount??0) ?> animal<?= ($goatCount??0)!==1?'s':'' ?></span></div>
  <?php if ($goatCount > 0): ?>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tag or name…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
  </div>
  <?php endif ?>
  <?php if (empty($goats)): ?>
    <div class="empty-state"><?= $goatCount > 0 ? 'No goats match your search.' : 'No goats assigned yet. Your animals will appear here once assigned by the farm team.' ?></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Age</th><th>Weight</th><th>Last check</th><th>Health</th></tr></thead>
    <tbody>
      <?php foreach ($goats as $g): ?>
      <tr>
        <td><span class="tag"><?= esc($g['tag_number']) ?></span></td>
        <td><a href="<?= site_url('member/goats/'.$g['id']) ?>" class="link-strong"><?= esc($g['name']) ?></a></td>
        <td><?= esc($g['breed']??'—') ?></td>
        <td><?= esc(goatAge($g['dob']??null)) ?></td>
        <td><?= isset($g['latest_weight']) ? esc($g['latest_weight']).' kg' : '—' ?></td>
        <td><?= isset($g['weight_date']) ? date('j M Y', strtotime($g['weight_date'])) : '—' ?></td>
        <td><?= ($g['is_flagged']??0) ? '<span class="badge badge-flagged">Flagged</span>' : '<span class="badge badge-active">Healthy</span>' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
