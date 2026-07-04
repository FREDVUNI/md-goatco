<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('vet/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('vet/animals') ?>" class="back-link">← Back to animal records</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= esc($goat['name']) ?> <span class="tag"><?= esc($goat['tag_number']) ?></span></h4>
    <div class="profile-row"><label>Breed</label><span><?= esc($goat['breed'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Sex</label><span><?= esc(ucfirst($goat['sex'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Age</label><span><?= esc(goatAge($goat['dob'])) ?></span></div>
    <div class="profile-row"><label>Pen</label><span><?= esc($goat['pen_id'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($goat['status']) ?></span></div>
    <?php if (!empty($latestWeight)): ?>
    <div class="profile-row"><label>Latest weight</label><span><?= esc($latestWeight['weight_kg']) ?> kg <span style="color:var(--slate-light,#718096);font-size:12px;">(<?= date('j M Y', strtotime($latestWeight['logged_at'])) ?>)</span></span></div>
    <?php endif ?>

    <a href="<?= site_url('vet/visits/log?tag=' . urlencode($goat['tag_number'])) ?>" class="btn btn-primary btn-sm" style="margin-top:16px;">Log a visit for this animal</a>
  </div>

  <div class="profile-card">
    <h4>Weight Trend</h4>
    <?php if (empty($growthChart)): ?>
    <p style="color:var(--slate-light,#718096); font-size:14px;">Not enough weight logs yet to chart a trend.</p>
    <?php else: ?>
    <table>
      <thead><tr><th>Month</th><th>Avg weight</th><th>Max weight</th></tr></thead>
      <tbody>
        <?php foreach ($growthChart as $row): ?>
        <tr>
          <td><?= esc($row['month']) ?></td>
          <td><?= esc(round((float) $row['avg_weight'], 1)) ?> kg</td>
          <td><?= esc(round((float) $row['max_weight'], 1)) ?> kg</td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Health History</h3></div>
  <?php if (empty($healthHistory)): ?>
  <div class="empty-state">No vet visits recorded for this animal yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Type</th><th>Vet</th><th>Notes</th><th>Flag</th></tr></thead>
    <tbody>
      <?php foreach ($healthHistory as $v): ?>
      <tr>
        <td><a href="<?= site_url('vet/visits/' . $v['id']) ?>" style="color:var(--blue);font-weight:600;"><?= date('j M Y', strtotime($v['visit_date'])) ?></a></td>
        <td><?= esc(visitTypeLabel($v['visit_type'])) ?></td>
        <td><?= esc(($v['first_name'] ?? '') . ' ' . ($v['last_name'] ?? '')) ?></td>
        <td><?= esc(substr($v['clinical_notes'], 0, 60)) . (strlen($v['clinical_notes']) > 60 ? '…' : '') ?></td>
        <td><?= !empty($v['is_flagged']) ? (empty($v['flag_resolved_at']) ? '<span class="badge badge-flagged">Active</span>' : '<span class="badge badge-active">Resolved</span>') : '—' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Weight Log</h3></div>
  <?php if (empty($weightHistory)): ?>
  <div class="empty-state">No weight readings logged yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Weight</th></tr></thead>
    <tbody>
      <?php foreach ($weightHistory as $w): ?>
      <tr><td><?= date('j M Y', strtotime($w['logged_at'])) ?></td><td><?= esc($w['weight_kg']) ?> kg</td></tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
