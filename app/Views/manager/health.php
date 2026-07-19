<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (empty($flags) && empty($search)): ?>
<div class="empty-page">
  <div class="empty-icon">✅</div>
  <h2>No active health flags</h2>
  <p>All flagged animals have been resolved or there are no current concerns.</p>
  <a href="<?= site_url('manager/dashboard') ?>" class="btn btn-outline">Back to dashboard</a>
</div>
<?php else: ?>
<div class="card">
  <div class="card-head">
    <h3>🚨 Active Health Flags</h3>
    <span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($pager->getTotal() ?? count($flags)) ?> open</span>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search by tag, animal, or reason…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
    <a href="<?= site_url('manager/health/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-ghost btn-sm">📥 Export</a>
  </div>
  <?php if (empty($flags)): ?>
    <div class="empty-state">No health flags match your search.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Tag</th><th>Animal</th><th>Member owner</th>
        <th>Issue</th><th>Flagged by</th><th>Date flagged</th>
        <th>Follow-up</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($flags as $flag): ?>
      <tr>
        <td><span class="tag"><?= esc($flag['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($flag['goat_name']) ?></td>
        <td>
          <?php if ($flag['member_first']): ?>
          <?= esc($flag['member_first'] . ' ' . $flag['member_last']) ?>
          <?php else: ?>
          <span style="color:var(--slate-light)">Unassigned</span>
          <?php endif ?>
        </td>
        <td style="max-width:200px"><?= esc(substr($flag['flag_reason'], 0, 60)) . (strlen($flag['flag_reason']) > 60 ? '…' : '') ?></td>
        <td><?= esc($flag['vet_first'] . ' ' . $flag['vet_last']) ?></td>
        <td><?= date('j M Y', strtotime($flag['created_at'])) ?></td>
        <td>
          <?= $flag['followup_date']
              ? '<strong>' . date('j M Y', strtotime($flag['followup_date'])) . '</strong>'
              : '—' ?>
        </td>
        <td>
          <?= form_open('manager/health/' . $flag['id'] . '/resolve', ['style' => 'display:inline']) ?>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-green btn-sm"
                    data-confirm="Mark this health flag as resolved?">
              ✓ Resolve
            </button>
          <?= form_close() ?>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?php endif ?>

<?= $this->endSection() ?>
