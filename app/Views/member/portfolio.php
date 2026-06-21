<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
  <div class="sb-profile-meta"><?= count($goats ?? []) ?> goat<?= count($goats ?? []) !== 1 ? 's' : '' ?> in portfolio</div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item">
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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="portfolio-header">
  <div>
    <h2 class="section-title">My Goats <span class="count-chip"><?= count($goats ?? []) ?></span></h2>
    <p class="section-sub">Click any goat to see their full profile and health history</p>
  </div>
</div>

<?php if (empty($goats)): ?>
<div class="empty-page">
  <div class="empty-icon">🐐</div>
  <h2>No goats assigned yet</h2>
  <p>Once your application is approved and your first animals are assigned, they'll appear here.</p>
  <a href="<?= site_url('member/support') ?>" class="btn btn-outline">Contact support</a>
</div>

<?php else: ?>
<div class="goat-grid">
  <?php foreach ($goats as $goat): ?>
  <a href="<?= site_url('member/goats/' . $goat['id']) ?>" class="goat-card">
    <div class="goat-card-photo" style="background:<?= goatCardColor($goat['breed'] ?? '') ?>">
      <span class="goat-emoji">🐐</span>
      <div class="goat-tag-chip"><?= esc($goat['tag_number']) ?></div>
      <?php if (!empty($goat['is_flagged'])): ?>
      <div class="health-chip health-chip-flagged">● Flagged</div>
      <?php else: ?>
      <div class="health-chip health-chip-healthy">● Healthy</div>
      <?php endif ?>
    </div>
    <div class="goat-card-body">
      <h4><?= esc($goat['name']) ?></h4>
      <div class="goat-breed"><?= esc($goat['breed'] ?? 'Unknown breed') ?> · <?= esc(ucfirst($goat['sex'] ?? '')) ?> · <?= esc(goatAge($goat['dob'] ?? null)) ?></div>
      <div class="goat-stats-mini">
        <div class="goat-stat-mini">
          <div class="gstat-label">Weight</div>
          <div class="gstat-val"><?= $goat['latest_weight'] ? esc($goat['latest_weight']) . ' kg' : 'Not recorded' ?></div>
        </div>
        <div class="goat-stat-mini">
          <div class="gstat-label">Last check</div>
          <div class="gstat-val"><?= $goat['weight_date'] ? date('j M', strtotime($goat['weight_date'])) : 'No record' ?></div>
        </div>
      </div>
    </div>
    <div class="goat-card-footer">View full profile →</div>
  </a>
  <?php endforeach ?>
</div>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Utility: generate a pastel gradient colour per breed for the card header
function goatCardColor(breed) {
  return ''; // handled via PHP helper below
}
</script>
<?= $this->endSection() ?>
