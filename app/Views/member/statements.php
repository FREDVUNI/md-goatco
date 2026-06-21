<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/wallet') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Top Up Wallet
  </a>
  <div class="sb-section">Account</div>
  <a href="<?= site_url('member/account') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account
  </a>
  <a href="<?= site_url('member/support') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/></svg>Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stat-grid stat-grid-3">
  <div class="stat-card stat-blue">
    <div class="stat-label">Current balance</div>
    <div class="stat-val"><?= esc(formatUgx($balance)) ?></div>
    <div class="stat-sub">As of today</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Total credited</div>
    <div class="stat-val"><?= esc(formatUgx($totalCredited)) ?></div>
    <div class="stat-sub">Since joining</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Total invested</div>
    <div class="stat-val"><?= esc(formatUgx($totalDebited)) ?></div>
    <div class="stat-sub">Payments made</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <h3>Transaction History</h3>
    <a href="<?= site_url('member/statements/download') ?>" class="btn btn-ghost btn-sm">📥 Download PDF</a>
  </div>

  <?php if (empty($transactions)): ?>
  <div class="empty-state">No transactions yet</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Reference</th>
        <th>Credit</th>
        <th>Debit</th>
        <th>Balance</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $txn): ?>
      <tr>
        <td><?= date('j M Y', strtotime($txn['created_at'])) ?></td>
        <td><?= esc($txn['description']) ?></td>
        <td><span class="tag"><?= esc($txn['reference']) ?></span></td>
        <td>
          <?php if ($txn['type'] === 'credit'): ?>
          <span class="text-green">+ <?= esc(formatUgx($txn['amount'])) ?></span>
          <?php else: ?>—<?php endif ?>
        </td>
        <td>
          <?php if ($txn['type'] === 'debit'): ?>
          <span class="text-red">- <?= esc(formatUgx($txn['amount'])) ?></span>
          <?php else: ?>—<?php endif ?>
        </td>
        <td style="font-weight:600;color:var(--ink)"><?= esc(formatUgx($txn['balance_after'])) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
