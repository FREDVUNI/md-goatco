<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="stat-grid stat-grid-3" style="margin-bottom:22px">
  <div class="stat-card stat-blue"><div class="stat-label">Current balance</div><div class="stat-val">UGX <?= number_format($balance??0) ?></div></div>
  <div class="stat-card stat-green"><div class="stat-label">Total credited</div><div class="stat-val">UGX <?= number_format($totalCredited??0) ?></div></div>
  <div class="stat-card stat-amber"><div class="stat-label">Transactions</div><div class="stat-val"><?= esc(isset($pager) ? $pager->getTotal() : count($transactions??[])) ?></div></div>
</div>
<div class="card">
  <div class="card-head">
    <h3>Transaction History</h3>
    <a href="<?= site_url('member/statements/download') ?>" class="btn btn-outline btn-sm">📥 Download statement (PDF)</a>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search description or reference…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
  </div>
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
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
