<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('vet/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>🚨 My Active Health Flags</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($pager->getTotal() ?? count($flags??[])) ?> open</span></div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tag, animal, or reason…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
  </div>
  <?php if (empty($flags)): ?>
    <div class="empty-state">No active flags — great work! ✅</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Animal</th><th>Concern</th><th>Flagged on</th><th>Follow-up</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($flags as $flag): ?>
      <tr>
        <td><span class="tag"><?= esc($flag['tag_number']??'—') ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($flag['goat_name']??'—') ?></td>
        <td style="max-width:200px"><?= esc(substr($flag['flag_reason']??'—',0,60)) ?><?= strlen($flag['flag_reason']??'')>60?'…':'' ?></td>
        <td><?= date('j M Y', strtotime($flag['created_at']??'now')) ?></td>
        <td><?= $flag['followup_date'] ? date('j M Y', strtotime($flag['followup_date'])) : '—' ?></td>
        <td>
          <?= form_open('vet/flags/'.$flag['id'].'/resolve',['style'=>'display:inline']) ?><?= csrf_field() ?>
          <button type="submit" class="btn btn-green btn-sm" data-confirm="Mark this flag as resolved?">✓ Resolve</button>
          <?= form_close() ?>
          <a href="<?= site_url('vet/visits/log?goat_id='.($flag['goat_id']??'')) ?>" class="btn btn-primary btn-sm">Log update</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
