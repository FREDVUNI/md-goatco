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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2"/></svg>Staff Accounts
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

<a href="<?= site_url('admin/members') ?>" class="back-link">← Back to members</a>

<div class="account-grid">

  <div class="profile-card">
    <h4><?= esc($member['first_name'] . ' ' . $member['last_name']) ?></h4>
    <div class="profile-row"><label>Email</label><span><?= esc($member['email']) ?></span></div>
    <div class="profile-row"><label>Phone</label><span><?= esc($member['phone'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($member['status']) ?></span></div>
    <div class="profile-row"><label>Joined</label><span><?= date('j M Y', strtotime($member['created_at'])) ?></span></div>
    <div class="profile-row"><label>Last login</label><span><?= !empty($member['last_login_at']) ? date('j M Y, g:i A', strtotime($member['last_login_at'])) : 'Never' ?></span></div>

    <div style="display:flex; gap:10px; margin-top:16px;">
      <?php if ($member['status'] === 'active'): ?>
      <?= form_open('admin/members/' . $member['id'] . '/deactivate', ['style' => 'display:inline']) ?>
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:var(--red)"
                data-confirm="Deactivate <?= esc($member['first_name']) ?>'s account?">Deactivate</button>
      <?= form_close() ?>
      <?php else: ?>
      <?= form_open('admin/members/' . $member['id'] . '/reactivate', ['style' => 'display:inline']) ?>
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-outline btn-sm">Reactivate</button>
      <?= form_close() ?>
      <?php endif ?>
      <?php if (! empty($application)): ?>
      <a href="<?= site_url('admin/applications/' . $application['id']) ?>" class="btn btn-ghost btn-sm">View application</a>
      <?php endif ?>
    </div>
  </div>

  <?php if (! empty($application)): ?>
  <div class="profile-card">
    <h4>Application Summary</h4>
    <div class="profile-row"><label>National ID</label><span><?= esc($application['nid_number']) ?></span></div>
    <div class="profile-row"><label>Next of kin</label><span><?= esc($application['nok_name'] . ' (' . ucfirst($application['nok_relationship']) . ')') ?></span></div>
    <div class="profile-row"><label>Goats requested</label><span><?= esc($application['goats_requested']) ?></span></div>
    <div class="profile-row"><label>Application status</label><span><?= statusBadge($application['status']) ?></span></div>
  </div>
  <?php endif ?>

</div>

<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Goats in Portfolio</h3></div>

  <?php if (empty($goats)): ?>
  <div class="empty-state">No goats assigned to this member yet.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>Tag</th><th>Name</th><th>Breed</th><th>Age</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php foreach ($goats as $g): ?>
      <tr>
        <td><span class="tag"><?= esc($g['tag_number']) ?></span></td>
        <td><a href="<?= site_url('admin/herd/' . $g['id']) ?>" style="font-weight:600;color:var(--blue)"><?= esc($g['name']) ?></a></td>
        <td><?= esc($g['breed'] ?: '—') ?></td>
        <td><?= esc(goatAge($g['dob'])) ?></td>
        <td><?= statusBadge($g['status']) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
