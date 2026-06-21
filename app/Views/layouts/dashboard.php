<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle ?? 'Dashboard') ?> — MD Goatco</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
  <?= $this->renderSection('head') ?>
</head>

<body class="role-<?= esc($currentUser['role'] ?? 'unknown') ?>">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sb-logo">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco Farm">
      <div>
        <strong>MD Goatco Farm Limited</strong>
        <small><?= esc($this->renderSection('portalName')) ?></small>
      </div>
    </div>

    <?= $this->renderSection('sidebar') ?>

    <div class="sb-bottom">
      <div class="sb-user">
        <div class="sb-avatar">
          <?= esc(strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1) . substr($currentUser['last_name'] ?? '', 0, 1))) ?>
        </div>
        <div class="sb-user-info">
          <span class="sb-user-name"><?= esc(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? '')) ?></span>
          <span class="sb-user-email"><?= esc($currentUser['email'] ?? '') ?></span>
        </div>
      </div>
      <a href="<?= site_url('auth/logout') ?>" class="logout-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        Sign out
      </a>
    </div>
  </aside>

  <!-- ===== MAIN ===== -->
  <div class="main" id="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
        <div>
          <h1 class="topbar-title"><?= esc($pageTitle ?? 'Dashboard') ?></h1>
          <p class="topbar-date"><?= date('l, j F Y') ?></p>
        </div>
      </div>
      <div class="topbar-right">
        <!-- Notifications bell -->
        <div class="notif-wrap">
          <button class="notif-btn" onclick="toggleNotifs()" aria-label="Notifications">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
              <path d="M13.73 21a2 2 0 01-3.46 0" />
            </svg>
            <?php if (($unreadCount ?? 0) > 0): ?>
              <span class="notif-badge"><?= esc($unreadCount) ?></span>
            <?php endif ?>
          </button>
          <!-- Notification dropdown -->
          <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-head">
              <strong>Notifications</strong>
              <?php if (!empty($notifications)): ?>
                <a href="<?= site_url(($currentUser['role'] ?? '') . '/notifications') ?>" class="notif-mark-all">Mark all read</a>
              <?php endif ?>
            </div>
            <div class="notif-list">
              <?php if (empty($notifications)): ?>
                <div class="notif-empty">No new notifications</div>
              <?php else: ?>
                <?php foreach (array_slice($notifications ?? [], 0, 5) as $notif): ?>
                  <div class="notif-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="notif-dot notif-<?= esc($notif['type']) ?>"></div>
                    <div>
                      <p class="notif-title"><?= esc($notif['title']) ?></p>
                      <p class="notif-body"><?= esc(substr($notif['body'], 0, 80)) . (strlen($notif['body']) > 80 ? '…' : '') ?></p>
                      <span class="notif-time"><?= time_ago($notif['created_at']) ?></span>
                    </div>
                  </div>
                <?php endforeach ?>
              <?php endif ?>
            </div>
          </div>
        </div>
        <!-- Avatar -->
        <div class="topbar-avatar">
          <?= esc(strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1) . substr($currentUser['last_name'] ?? '', 0, 1))) ?>
        </div>
      </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->has('success')): ?>
      <div class="alert alert-success"><?= esc(session('success')) ?><button onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?><button onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>
    <?php if (session()->has('warning')): ?>
      <div class="alert alert-warning"><?= esc(session('warning')) ?><button onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>
    <?php if (session()->has('info')): ?>
      <div class="alert alert-info"><?= esc(session('info')) ?><button onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>

    <!-- CONTENT -->
    <div class="content">
      <?= $this->renderSection('content') ?>
    </div>

  </div><!-- /main -->

  <script src="<?= base_url('js/dashboard.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
      document.getElementById('main').classList.toggle('sidebar-open');
    }

    function toggleNotifs() {
      document.getElementById('notifDropdown').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.notif-wrap')) {
        document.getElementById('notifDropdown')?.classList.remove('show');
      }
    });
  </script>
</body>

</html>