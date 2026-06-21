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

<?php if (! $payment): ?>

<div class="empty-page">
  <div class="empty-icon">🔍</div>
  <h2>Top-up not found</h2>
  <p>We couldn't find that payment reference on your account.</p>
  <a href="<?= site_url('member/wallet/topup') ?>" class="btn btn-primary" style="margin-top:16px;">Back to Top Up</a>
</div>

<?php else: ?>

<div class="profile-card" style="max-width:560px; margin:0 auto;">

  <?php if ($payment['status'] === 'completed'): ?>
    <div style="text-align:center; padding:8px 0 20px;">
      <div style="font-size:48px;">✅</div>
      <h2 style="margin:8px 0 0; color:#059669;">Payment received</h2>
      <p style="color:var(--slate-light, #718096);">Your wallet has been credited.</p>
    </div>
  <?php elseif (in_array($payment['status'], ['failed', 'invalid', 'reversed'], true)): ?>
    <div style="text-align:center; padding:8px 0 20px;">
      <div style="font-size:48px;">❌</div>
      <h2 style="margin:8px 0 0; color:#DC2626;">Payment unsuccessful</h2>
      <p style="color:var(--slate-light, #718096);">No funds were deducted. You can try again any time.</p>
    </div>
  <?php else: ?>
    <div style="text-align:center; padding:8px 0 20px;">
      <div style="font-size:48px;">⏳</div>
      <h2 style="margin:8px 0 0; color:#D97706;">Payment pending</h2>
      <p style="color:var(--slate-light, #718096);">We're waiting for Pesapal to confirm this payment. This page updates automatically — you can also tap "Check now" below.</p>
    </div>
  <?php endif ?>

  <div class="profile-row"><label>Reference</label><span><?= esc($payment['merchant_reference']) ?></span></div>
  <div class="profile-row"><label>Amount</label><span><?= esc(formatUgx($payment['amount'])) ?></span></div>
  <div class="profile-row"><label>Status</label><span><?= statusBadge($payment['status']) ?></span></div>
  <div class="profile-row"><label>Submitted</label><span><?= date('j M Y, g:i A', strtotime($payment['created_at'])) ?></span></div>

  <div style="display:flex; gap:10px; margin-top:20px;">
    <?php if ($payment['status'] === 'pending' && !empty($payment['order_tracking_id'])): ?>
    <a href="<?= site_url('payments/callback?OrderTrackingId=' . urlencode($payment['order_tracking_id']) . '&OrderMerchantReference=' . urlencode($payment['merchant_reference'])) ?>" class="btn btn-outline btn-sm">
      🔄 Check now
    </a>
    <?php endif ?>
    <a href="<?= site_url('member/statements') ?>" class="btn btn-primary btn-sm">View statement</a>
  </div>
</div>

<?php if ($payment['status'] === 'pending'): ?>
<script>
  // Lightweight auto-refresh while the payment is still pending, so members
  // don't have to manually reload after returning from Pesapal.
  setTimeout(function () { window.location.reload(); }, 8000);
</script>
<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
