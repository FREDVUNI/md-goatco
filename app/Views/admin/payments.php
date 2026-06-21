<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2"/></svg>
    Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-3">
  <div class="stat-card stat-green">
    <div class="stat-label">Completed</div>
    <div class="stat-val"><?= esc($totals['completed']) ?></div>
    <div class="stat-sub"><?= esc(formatUgx($totals['sum'])) ?> total credited</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Pending</div>
    <div class="stat-val"><?= esc($totals['pending']) ?></div>
    <div class="stat-sub">Awaiting Pesapal confirmation</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Failed / Reversed</div>
    <div class="stat-val"><?= esc($totals['failed']) ?></div>
    <div class="stat-sub">No funds were credited</div>
  </div>
</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head">
    <h3>All Wallet Top-ups</h3>
  </div>

  <?php if (empty($payments)): ?>
  <div class="empty-state">No wallet top-ups have been submitted yet.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Member</th>
        <th>Reference</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($payments as $p): ?>
      <tr>
        <td><?= date('j M Y, g:i A', strtotime($p['created_at'])) ?></td>
        <td><?= esc($p['first_name'] . ' ' . $p['last_name']) ?><br><span style="color:var(--slate-light, #718096); font-size:12px;"><?= esc($p['email']) ?></span></td>
        <td><span class="tag"><?= esc($p['merchant_reference']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc(formatUgx($p['amount'])) ?></td>
        <td><?= esc($p['payment_method'] ?: '—') ?></td>
        <td><?= statusBadge($p['status']) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
