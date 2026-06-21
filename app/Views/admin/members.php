<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>Goat Banking Members</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search members…"
             data-search-table="membersTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:220px">
    </div>
  </div>

  <?php if (empty($members)): ?>
  <div class="empty-state">No members yet — applications will appear here once approved.</div>
  <?php else: ?>
  <table id="membersTable">
    <thead>
      <tr>
        <th>Name</th><th>Email</th><th>Phone</th><th>Goats</th>
        <th>Joined</th><th>Last login</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar"><?= esc(initials($m['first_name'] ?? '', $m['last_name'] ?? '')) ?></div>
            <div>
              <a href="<?= site_url('admin/members/' . $m['id']) ?>" style="font-weight:600;color:var(--blue)">
                <?= esc(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? '')) ?>
              </a>
            </div>
          </div>
        </td>
        <td><?= esc($m['email']) ?></td>
        <td><?= esc($m['phone'] ?? '—') ?></td>
        <td><?= esc($m['goat_count'] ?? 0) ?></td>
        <td><?= date('j M Y', strtotime($m['created_at'])) ?></td>
        <td><?= $m['last_login_at'] ? date('j M Y', strtotime($m['last_login_at'])) : 'Never' ?></td>
        <td><?= statusBadge($m['status']) ?></td>
        <td>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="<?= site_url('admin/members/' . $m['id']) ?>" class="btn btn-ghost btn-sm">View</a>
            <?php if ($m['status'] === 'active'): ?>
            <?= form_open('admin/members/' . $m['id'] . '/deactivate', ['style' => 'display:inline']) ?>
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:var(--red)"
                      data-confirm="Deactivate <?= esc($m['first_name']) ?>'s account?">
                Deactivate
              </button>
            <?= form_close() ?>
            <?php else: ?>
            <?= form_open('admin/members/' . $m['id'] . '/reactivate', ['style' => 'display:inline']) ?>
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-green btn-sm"
                      data-confirm="Reactivate <?= esc($m['first_name']) ?>'s account?">
                Reactivate
              </button>
            <?= form_close() ?>
            <?php endif ?>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <div style="padding:12px 20px;font-size:0.8rem;color:var(--slate-light);border-top:1px solid var(--border)">
    <?= count($members) ?> member<?= count($members) !== 1 ? 's' : '' ?> total
  </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
