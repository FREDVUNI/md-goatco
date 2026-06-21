<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role sb-role-green">Veterinarian</div>
<nav class="sb-nav">
  <div class="sb-section">My Work</div>
  <a href="<?= site_url('vet/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>My Dashboard
  </a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>My Flags
    <?php if (count($flags ?? []) > 0): ?><span class="sb-badge"><?= count($flags) ?></span><?php endif ?>
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (empty($flags)): ?>
<div class="empty-page">
  <div class="empty-icon">✅</div>
  <h2>No active flags</h2>
  <p>All your animals are cleared. Great work!</p>
  <a href="<?= site_url('vet/dashboard') ?>" class="btn btn-outline">Back to dashboard</a>
</div>
<?php else: ?>
<div class="card">
  <div class="card-head">
    <h3>🚨 My Active Health Flags</h3>
    <span style="font-size:0.84rem;color:var(--slate-light)"><?= count($flags) ?> open</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Tag</th><th>Animal</th><th>Issue</th>
        <th>Flagged</th><th>Follow-up</th><th>Days open</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($flags as $flag): ?>
      <tr>
        <td><span class="tag"><?= esc($flag['tag_number']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($flag['goat_name']) ?></td>
        <td><?= esc(substr($flag['flag_reason'], 0, 55)) . (strlen($flag['flag_reason']) > 55 ? '…' : '') ?></td>
        <td><?= date('j M Y', strtotime($flag['created_at'])) ?></td>
        <td>
          <?= $flag['followup_date']
              ? '<strong>' . date('j M Y', strtotime($flag['followup_date'])) . '</strong>'
              : '—' ?>
        </td>
        <td>
          <?php $days = floor((time() - strtotime($flag['created_at'])) / 86400); ?>
          <span style="font-family:var(--font-mono);font-size:0.8rem;color:<?= $days > 3 ? 'var(--red)' : 'var(--slate)' ?>;font-weight:<?= $days > 3 ? '700' : '400' ?>">
            <?= $days ?> day<?= $days !== 1 ? 's' : '' ?>
          </span>
        </td>
        <td style="display:flex;gap:8px;flex-wrap:wrap">
          <a href="<?= site_url('vet/visits/log?tag=' . urlencode($flag['tag_number'])) ?>"
             class="btn btn-primary btn-sm">Log update</a>
          <?= form_open('vet/flags/' . $flag['id'] . '/resolve', ['style' => 'display:inline']) ?>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-green btn-sm"
                    data-confirm="Mark this flag as resolved for <?= esc($flag['goat_name']) ?>?">
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
