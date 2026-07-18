<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($pageTitle ?? 'Dashboard') ?> — MD Goatco Farm</title>
<link rel="shortcut icon" href="<?= base_url('img/logo.png') ?>" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
<?= $this->renderSection('head') ?>
</head>
<body class="dash-body">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sb-header">
    <a href="<?= site_url('dashboard') ?>" class="sb-logo">
      <img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco">
      <div>
        <strong>MD Goatco</strong>
        <span><?= $this->renderSection('portalName') ?></span>
      </div>
    </a>
    <button class="sb-close" id="sidebarClose" aria-label="Close menu">×</button>
  </div>
  <?= $this->renderSection('sidebar') ?>
  <div class="sb-footer">
    <div class="sb-user">
      <div class="sb-user-avatar"><?= strtoupper(substr($currentUser['first_name']??'U',0,1).substr($currentUser['last_name']??'',0,1)) ?></div>
      <div class="sb-user-info">
        <strong><?= esc(($currentUser['first_name']??'').' '.($currentUser['last_name']??'')) ?></strong>
        <small><?= esc($currentUser['email']??'') ?></small>
      </div>
    </div>
    <a href="<?= site_url('auth/logout') ?>" class="sb-logout" title="Sign out">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    </a>
  </div>
</aside>

<!-- MAIN -->
<div class="dash-main">
  <!-- TOPBAR -->
  <header class="dash-topbar">
    <button class="topbar-menu-btn" id="sidebarToggle" aria-label="Open menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <div class="topbar-title"><?= esc($pageTitle ?? 'Dashboard') ?></div>
    <div class="topbar-actions">
      <?php if (($unreadCount??0) > 0): ?>
      <div class="notif-bell">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <span class="notif-dot"><?= esc($unreadCount) ?></span>
      </div>
      <?php endif ?>
    </div>
  </header>

  <!-- FLASH MESSAGES -->
  <div class="dash-flash-wrap">
    <?php foreach (['success','error','warning','info'] as $type): ?>
      <?php if (session()->has($type)): ?>
      <div class="flash flash-<?= $type ?>">
        <?= esc(session($type)) ?>
        <button onclick="this.parentElement.remove()">×</button>
      </div>
      <?php endif ?>
    <?php endforeach ?>
  </div>

  <!-- PAGE CONTENT -->
  <main class="dash-content">
    <?= $this->renderSection('content') ?>
  </main>
</div>

<!-- Overlay for mobile -->
<div class="sb-overlay" id="sbOverlay"></div>

<script src="<?= base_url('js/dashboard.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
