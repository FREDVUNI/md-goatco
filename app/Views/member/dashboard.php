<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
  <div class="sb-profile-meta">
    <?= esc($goatCount ?? 0) ?> goat<?= ($goatCount ?? 0) !== 1 ? 's' : '' ?> in portfolio
  </div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item <?= uri_string() === 'member/dashboard' ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/goats') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/statements') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
    Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/wallet') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Top Up Wallet
  </a>
  <div class="sb-section">Account</div>
  <a href="<?= site_url('member/account') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/account') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    My Account
  </a>
  <a href="<?= site_url('member/support') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/support') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- WELCOME BANNER -->
<div class="welcome-banner">
  <div>
    <h2>Good <?= date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') ?>, <?= esc($currentUser['first_name']) ?>! 👋</h2>
    <p>
      <?php if (($healthyCount ?? 0) === ($goatCount ?? 0) && $goatCount > 0): ?>
        All <?= esc($goatCount) ?> of your goats are healthy — no concerns flagged.
      <?php elseif ($goatCount > 0): ?>
        You have <?= esc($goatCount) ?> goats in your portfolio. <?= esc($goatCount - $healthyCount) ?> have active health flags.
      <?php else: ?>
        Welcome to MD Goatco Farm Goat Banking. Your goats will appear here once assigned.
      <?php endif ?>
    </p>
    <div class="wb-stats">
      <div class="wb-stat"><strong><?= esc($goatCount ?? 0) ?></strong><span>Your goats</span></div>
      <div class="wb-stat"><strong><?= esc($healthyCount ?? 0) ?></strong><span>Healthy</span></div>
      <div class="wb-stat"><strong>UGX <?= number_format($balance ?? 0) ?></strong><span>Balance</span></div>
    </div>
  </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid stat-grid-3">
  <div class="stat-card stat-blue">
    <div class="stat-label">Goats in portfolio</div>
    <div class="stat-val"><?= esc($goatCount ?? 0) ?></div>
    <div class="stat-sub">All assigned and tagged</div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">Healthy animals</div>
    <div class="stat-val"><?= esc($healthyCount ?? 0) ?>/<?= esc($goatCount ?? 0) ?></div>
    <div class="stat-sub">Last vet check on record</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Total credited</div>
    <div class="stat-val">UGX <?= number_format($totalCredited ?? 0) ?></div>
    <div class="stat-sub">Since joining</div>
  </div>
</div>

<!-- QUICK GOAT VIEW -->
<?php if (!empty($goats)): ?>
<div class="card">
  <div class="card-head">
    <h3>🐐 Your Goats — Quick View</h3>
    <a href="<?= site_url('member/goats') ?>" class="btn btn-outline btn-sm">View all →</a>
  </div>
  <table>
    <thead>
      <tr><th>Tag</th><th>Name</th><th>Breed</th><th>Latest weight</th><th>Last checkup</th><th>Health</th></tr>
    </thead>
    <tbody>
      <?php foreach (array_slice($goats, 0, 5) as $goat): ?>
      <tr>
        <td><span class="tag"><?= esc($goat['tag_number']) ?></span></td>
        <td><a href="<?= site_url('member/goats/' . $goat['id']) ?>" class="link-strong"><?= esc($goat['name']) ?></a></td>
        <td><?= esc($goat['breed'] ?? '—') ?></td>
        <td><?= $goat['latest_weight'] ? esc($goat['latest_weight']) . ' kg' : '—' ?></td>
        <td><?= $goat['weight_date'] ? date('j M Y', strtotime($goat['weight_date'])) : '—' ?></td>
        <td>
          <?php if (!empty($goat['is_flagged'])): ?>
          <span class="badge badge-flagged">Flagged</span>
          <?php else: ?>
          <span class="badge badge-active">Healthy</span>
          <?php endif ?>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<?php endif ?>

<!-- RECENT ACTIVITY -->
<div class="card">
  <div class="card-head"><h3>📋 Recent Activity</h3></div>
  <?php if (empty($notifications)): ?>
  <div class="empty-state">No recent activity yet</div>
  <?php else: ?>
  <div class="timeline">
    <?php foreach (array_slice($notifications, 0, 6) as $notif): ?>
    <div class="tl-item">
      <div class="tl-dot tl-<?= esc($notif['type']) ?>"></div>
      <div class="tl-content">
        <p><?= esc($notif['title']) ?></p>
        <span><?= esc($notif['body']) ?></span>
        <small><?= date('j M Y', strtotime($notif['created_at'])) ?></small>
      </div>
    </div>
    <?php endforeach ?>
  </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
