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
  <a href="<?= site_url('member/statements') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item active">
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
</div>

<?php if (! $configured): ?>
<div class="form-errors form-errors-block" style="margin-bottom:20px;">
  <p>Pesapal isn't configured on this server yet. An administrator needs to set <code>pesapal.consumerKey</code> and <code>pesapal.consumerSecret</code> in <code>.env</code> before top-ups can be processed.</p>
</div>
<?php endif ?>

<div class="account-grid">

  <!-- Top-up form -->
  <div class="profile-card">
    <h4>Top up your wallet</h4>
    <p style="color:var(--slate-light, #718096); font-size:14px; margin:-8px 0 16px;">
      You'll be redirected to Pesapal's secure payment page to pay by mobile money, card, or bank transfer.
    </p>

    <?php if (!empty(session('errors'))): ?>
    <div class="form-errors form-errors-block">
      <?php foreach (session('errors') as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
    </div>
    <?php endif ?>

    <?= form_open('member/wallet/topup', ['class' => 'profile-form']) ?>
      <?= csrf_field() ?>
      <div class="profile-row edit-row">
        <label for="amount">Amount (UGX)</label>
        <input type="number" id="amount" name="amount" min="<?= esc($minAmount) ?>" max="<?= esc($maxAmount) ?>" step="1000"
               value="<?= esc(old('amount') ?? '') ?>" placeholder="e.g. 200000" required <?= $configured ? '' : 'disabled' ?>>
      </div>
      <p style="font-size:12px; color:var(--slate-light, #718096); margin:4px 0 0;">
        Minimum <?= esc(formatUgx($minAmount)) ?> · Maximum <?= esc(formatUgx($maxAmount)) ?>
      </p>
      <button type="submit" class="btn btn-primary" style="margin-top:16px;" <?= $configured ? '' : 'disabled' ?>>
        Continue to Pesapal
      </button>
    <?= form_close() ?>
  </div>

  <!-- Quick amounts -->
  <div class="profile-card">
    <h4>Quick amounts</h4>
    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:8px;">
      <?php foreach ([50000, 100000, 200000, 500000, 1000000] as $quick): ?>
      <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('amount').value=<?= (int) $quick ?>">
        <?= esc(formatUgx($quick)) ?>
      </button>
      <?php endforeach ?>
    </div>
  </div>

</div>

<!-- Recent top-ups -->
<div class="card" style="margin-top:24px;">
  <div class="card-head">
    <h3>Recent Top-ups</h3>
  </div>

  <?php if (empty($history)): ?>
  <div class="empty-state">No top-ups yet — your first one will appear here.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Reference</th>
        <th>Amount</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($history as $p): ?>
      <tr>
        <td><?= date('j M Y, g:i A', strtotime($p['created_at'])) ?></td>
        <td><span class="tag"><?= esc($p['merchant_reference']) ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc(formatUgx($p['amount'])) ?></td>
        <td><?= statusBadge($p['status']) ?></td>
        <td><a href="<?= site_url('member/wallet/topup/' . $p['merchant_reference']) ?>" class="btn btn-ghost btn-sm">View</a></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
