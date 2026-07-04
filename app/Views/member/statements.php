<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-profile"><div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name']??'U',0,1).substr($currentUser['last_name']??'',0,1))) ?></div><div class="sb-profile-name"><?= esc(($currentUser['first_name']??'').' '.($currentUser['last_name']??'')) ?></div></div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats</a>
  <a href="<?= site_url('member/statements') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/></svg>Statements</a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Top Up Wallet</a>
  <a href="<?= site_url('member/account') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="stat-grid stat-grid-3" style="margin-bottom:22px">
  <div class="stat-card stat-blue"><div class="stat-label">Current balance</div><div class="stat-val">UGX <?= number_format($balance??0) ?></div></div>
  <div class="stat-card stat-green"><div class="stat-label">Total credited</div><div class="stat-val">UGX <?= number_format($totalCredited??0) ?></div></div>
  <div class="stat-card stat-amber"><div class="stat-label">Transactions</div><div class="stat-val"><?= count($transactions??[]) ?></div></div>
</div>
<div class="card">
  <div class="card-head"><h3>Transaction History</h3></div>
  <?php if (empty($transactions)): ?>
    <div class="empty-state">No transactions yet. <a href="<?= site_url('member/wallet/topup') ?>">Make your first payment →</a></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Description</th><th>Reference</th><th>Type</th><th>Amount (UGX)</th><th>Balance</th></tr></thead>
    <tbody>
      <?php foreach ($transactions as $t): ?>
      <tr>
        <td><?= date('j M Y', strtotime($t['created_at'])) ?></td>
        <td><?= esc($t['description']) ?></td>
        <td><span class="tag" style="font-size:0.66rem"><?= esc($t['reference']??'—') ?></span></td>
        <td><span class="badge <?= $t['type']==='credit'?'badge-active':'badge-pending' ?>"><?= esc(ucfirst($t['type'])) ?></span></td>
        <td style="font-weight:700;color:<?= $t['type']==='credit'?'var(--green)':'var(--red)' ?>"><?= ($t['type']==='credit'?'+':'−').number_format($t['amount']) ?></td>
        <td><?= number_format($t['balance_after']) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
