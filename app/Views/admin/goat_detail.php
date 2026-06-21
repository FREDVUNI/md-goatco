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
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item active">
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

<a href="<?= site_url('admin/herd') ?>" class="back-link">← Back to herd</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= esc($goat['name']) ?> <span class="tag"><?= esc($goat['tag_number']) ?></span></h4>
    <div class="profile-row"><label>Breed</label><span><?= esc($goat['breed'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Sex</label><span><?= esc(ucfirst($goat['sex'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Age</label><span><?= esc(goatAge($goat['dob'])) ?></span></div>
    <div class="profile-row"><label>Pen</label><span><?= esc($goat['pen_id'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($goat['status']) ?></span></div>
    <div class="profile-row"><label>Added</label><span><?= date('j M Y', strtotime($goat['created_at'])) ?></span></div>

    <?php
      $owner = null;
      foreach ($members ?? [] as $m) {
          if ((int) $m['id'] === (int) ($goat['member_id'] ?? 0)) { $owner = $m; break; }
      }
    ?>
    <div class="profile-row"><label>Owner</label><span><?= $owner ? esc($owner['first_name'] . ' ' . $owner['last_name']) : 'Farm stock (unassigned)' ?></span></div>

    <?php if (!empty($goat['notes'])): ?>
    <h4 style="margin-top:20px;">Notes</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($goat['notes'])) ?></p>
    <?php endif ?>

    <a href="<?= site_url('admin/herd/' . $goat['id'] . '/edit') ?>" class="btn btn-outline btn-sm" style="margin-top:16px;">Edit details</a>
  </div>

  <div class="profile-card">
    <h4>Quick facts</h4>
    <p style="font-size:14px; color:var(--slate); line-height:1.7;">
      For full health and weight history on this animal, use the
      <a href="<?= site_url('vet/animals/' . $goat['id']) ?>" style="color:var(--blue);">veterinary record</a>
      or the <a href="<?= site_url('manager/herd/' . $goat['id']) ?>" style="color:var(--blue);">manager herd view</a>,
      both of which track the same underlying record.
    </p>
  </div>
</div>

<?= $this->endSection() ?>
