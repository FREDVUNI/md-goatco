<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Payment Transactions</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($pager->getTotal() ?? count($payments??[])) ?> records</span></div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search by member or reference…" value="<?= esc($search ?? '') ?>">
    </form>
    <a href="<?= site_url('admin/payments/export') . (!empty($search) ? '?q='.urlencode($search) : '') ?>" class="btn btn-outline btn-sm">📥 Download CSV</a>
  </div>
  <?php if (empty($payments)): ?>
    <div class="empty-state">No payments recorded yet</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Member</th><th>Reference</th><th>Amount (UGX)</th><th>Status</th><th>Method</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($payments as $p): ?>
      <tr>
        <td><?= esc(($p['first_name']??'').' '.($p['last_name']??'')) ?></td>
        <td><span class="tag"><?= esc($p['reference']) ?></span></td>
        <td><?= number_format($p['amount']) ?></td>
        <td><?= statusBadge($p['status']) ?></td>
        <td><?= esc($p['payment_method']??'PesaPal') ?></td>
        <td><?= date('j M Y', strtotime($p['created_at'])) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
