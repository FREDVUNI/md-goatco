<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Overview</div>
  <a href="<?= site_url('manager/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>Goat Banking Members</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <input type="text" placeholder="Search members…"
             data-search-table="mgrMembersTable"
             style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:0.84rem;width:200px">
    </div>
  </div>

  <?php if (empty($members)): ?>
  <div class="empty-state">No active members yet</div>
  <?php else: ?>
  <table id="mgrMembersTable">
    <thead>
      <tr><th>Name</th><th>Phone</th><th>Goats</th><th>Joined</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar"><?= esc(initials($m['first_name'] ?? '', $m['last_name'] ?? '')) ?></div>
            <?= esc(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? '')) ?>
          </div>
        </td>
        <td><?= esc($m['phone'] ?? '—') ?></td>
        <td><?= esc($m['goat_count'] ?? 0) ?></td>
        <td><?= date('j M Y', strtotime($m['created_at'])) ?></td>
        <td><?= statusBadge($m['status']) ?></td>
        <td>
          <a href="<?= site_url('manager/members/' . $m['id']) ?>" class="btn btn-ghost btn-sm">View portfolio</a>
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
