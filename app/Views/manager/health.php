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
  <a href="<?= site_url('manager/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
    <?php if (count($flags ?? []) > 0): ?><span class="sb-badge"><?= count($flags) ?></span><?php endif ?>
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (empty($flags)): ?>
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
    <div style="display:flex;gap:10px;align-items:center">
      <span style="font-size:0.84rem;color:var(--slate-light)"><?= count($flags) ?> open</span>
      <a href="<?= site_url('manager/reports/export/health') ?>" class="btn btn-ghost btn-sm">📥 Export</a>
    </div>
  </div>
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
</div>
<?php endif ?>

<?= $this->endSection() ?>
