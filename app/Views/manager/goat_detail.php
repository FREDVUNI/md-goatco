<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Overview</div>
  <a href="<?= site_url('manager/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('manager/herd') ?>" class="back-link">← Back to herd</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= esc($goat['name']) ?> <span class="tag"><?= esc($goat['tag_number']) ?></span></h4>
    <div class="profile-row"><label>Breed</label><span><?= esc($goat['breed'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Sex</label><span><?= esc(ucfirst($goat['sex'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Age</label><span><?= esc(goatAge($goat['dob'])) ?></span></div>
    <div class="profile-row"><label>Pen</label><span><?= esc($goat['pen_id'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($goat['status']) ?></span></div>
    <div class="profile-row"><label>Added</label><span><?= date('j M Y', strtotime($goat['created_at'])) ?></span></div>

    <?php if (!empty($goat['notes'])): ?>
    <h4 style="margin-top:20px;">Notes</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($goat['notes'])) ?></p>
    <?php endif ?>

    <a href="<?= site_url('manager/herd/' . $goat['id'] . '/edit') ?>" class="btn btn-outline btn-sm" style="margin-top:16px;">Edit details</a>
  </div>

  <div class="profile-card">
    <h4>Vet Visit Summary</h4>
    <?php
      $flagged = array_filter($visits ?? [], fn($v) => !empty($v['is_flagged']) && empty($v['flag_resolved_at']));
    ?>
    <div class="profile-row"><label>Total visits</label><span><?= count($visits ?? []) ?></span></div>
    <div class="profile-row"><label>Active flags</label><span><?= count($flagged) ?></span></div>
    <a href="<?= site_url('vet/visits/log?tag=' . urlencode($goat['tag_number'])) ?>" class="btn btn-outline btn-sm" style="margin-top:16px;">Log a vet visit</a>
  </div>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Visit History</h3></div>

  <?php if (empty($visits)): ?>
  <div class="empty-state">No vet visits recorded for this animal yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Type</th><th>Vet</th><th>Notes</th><th>Flag</th></tr></thead>
    <tbody>
      <?php foreach ($visits as $v): ?>
      <tr>
        <td><?= date('j M Y', strtotime($v['visit_date'])) ?></td>
        <td><?= esc(visitTypeLabel($v['visit_type'])) ?></td>
        <td><?= esc(($v['first_name'] ?? '') . ' ' . ($v['last_name'] ?? '')) ?></td>
        <td><?= esc(substr($v['clinical_notes'], 0, 80)) . (strlen($v['clinical_notes']) > 80 ? '…' : '') ?></td>
        <td><?= !empty($v['is_flagged']) ? (empty($v['flag_resolved_at']) ? '<span class="badge badge-flagged">Active</span>' : '<span class="badge badge-active">Resolved</span>') : '—' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
