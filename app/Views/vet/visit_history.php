<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('vet/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Visit History</h3>
    <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-primary btn-sm">+ Log new visit</a>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tag, animal, or type…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
    <a href="<?= site_url('vet/visits/history/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>
  <?php if (empty($visits)): ?>
    <div class="empty-state">No visits logged yet. <a href="<?= site_url('vet/visits/log') ?>">Log your first →</a></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Animal</th><th>Type</th><th>Date</th><th>Outcome</th><th>Flagged</th><th>Notes</th></tr></thead>
    <tbody>
      <?php foreach ($visits as $v): ?>
      <tr>
        <td><span class="tag"><?= esc($v['tag_number']??'—') ?></span></td>
        <td style="font-weight:600"><?= esc($v['goat_name']??'—') ?></td>
        <td><?= esc(visitTypeLabel($v['visit_type']??'routine')) ?></td>
        <td><?= date('j M Y', strtotime($v['visit_date'])) ?></td>
        <td><span class="badge <?= ($v['outcome']??'')==='healthy'?'badge-active':'badge-pending' ?>"><?= esc(ucfirst($v['outcome']??'—')) ?></span></td>
        <td><?= ($v['is_flagged']??0) ? '<span class="badge badge-flagged">🚨 Yes</span>' : '—' ?></td>
        <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= esc(substr($v['clinical_notes']??'—',0,60)) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
