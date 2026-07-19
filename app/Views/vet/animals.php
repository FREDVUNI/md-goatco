<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('vet/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Animal Records</h3>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tag or name…" value="<?= esc($search ?? '') ?>">
    </form>
  </div>
  <?php if (empty($herd)): ?>
    <div class="empty-state">No animals in the herd yet</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Sex</th><th>Age</th><th>Pen</th><th>Health</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($herd as $g): ?>
      <tr>
        <td><span class="tag"><?= esc($g['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($g['name']) ?></td>
        <td><?= esc($g['breed']??'—') ?></td>
        <td><?= esc(ucfirst($g['sex']??'—')) ?></td>
        <td><?= esc(goatAge($g['dob']??null)) ?></td>
        <td><?= esc($g['pen_id']??'—') ?></td>
        <td><?= ($g['is_flagged']??0) ? '<span class="badge badge-flagged">Flagged</span>' : '<span class="badge badge-active">Healthy</span>' ?></td>
        <td>
          <a href="<?= site_url('vet/visits/log?goat_id='.$g['id']) ?>" class="btn btn-primary btn-sm">Log visit</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
