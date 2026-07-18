<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Applications
    <?php if (($pendingCount ?? 0) > 0): ?><span class="sb-badge"><?= esc($pendingCount) ?></span><?php endif ?>
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    Members
  </a>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2M4.93 4.93l1.41 1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2"/></svg>
    Staff Accounts
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    Settings
  </a>
  <a href="<?= site_url('account') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    My Account
  </a>
</nav>
