<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>Applications</a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members</a>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Wallet Top-ups</a>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd</a>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>Staff</a>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/></svg>Settings</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>Payment Transactions</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= count($payments??[]) ?> records</span></div>
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
  <?php endif ?>
</div>
<?= $this->endSection() ?>
