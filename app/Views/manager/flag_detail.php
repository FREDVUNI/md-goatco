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
  <a href="<?= site_url('manager/herd') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('manager/health') ?>" class="back-link">← Back to health flags</a>

<div class="account-grid">
  <div class="profile-card">
    <h4><?= $goat ? esc($goat['name'] . ' (' . $goat['tag_number'] . ')') : 'Unknown animal' ?></h4>
    <div class="profile-row"><label>Visit type</label><span><?= esc(visitTypeLabel($flag['visit_type'])) ?></span></div>
    <div class="profile-row"><label>Visit date</label><span><?= date('j M Y, g:i A', strtotime($flag['visit_date'])) ?></span></div>
    <div class="profile-row"><label>Veterinarian</label><span><?= $vet ? esc($vet['first_name'] . ' ' . $vet['last_name']) : '—' ?></span></div>
    <?php if (!empty($flag['temperature'])): ?>
    <div class="profile-row"><label>Temperature</label><span><?= esc($flag['temperature']) ?> °C</span></div>
    <?php endif ?>
    <?php if (!empty($flag['weight_kg'])): ?>
    <div class="profile-row"><label>Weight</label><span><?= esc($flag['weight_kg']) ?> kg</span></div>
    <?php endif ?>
    <div class="profile-row"><label>Status</label>
      <span><?= empty($flag['flag_resolved_at']) ? '<span class="badge badge-flagged">Active</span>' : '<span class="badge badge-active">Resolved</span>' ?></span>
    </div>
  </div>

  <div class="profile-card">
    <h4>Health Flag</h4>
    <p style="font-size:14px; color:var(--slate); line-height:1.6;"><?= nl2br(esc($flag['flag_reason'] ?: 'No reason given.')) ?></p>

    <h4 style="margin-top:20px;">Clinical Notes</h4>
    <p style="font-size:14px; color:var(--slate); line-height:1.6;"><?= nl2br(esc($flag['clinical_notes'])) ?></p>

    <?php if (!empty($flag['medication'])): ?>
    <h4 style="margin-top:20px;">Medication</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($flag['medication'])) ?></p>
    <?php endif ?>

    <?php if (!empty($flag['followup_date'])): ?>
    <div class="profile-row" style="margin-top:16px;"><label>Follow-up due</label><span><?= date('j M Y', strtotime($flag['followup_date'])) ?></span></div>
    <?php endif ?>

    <?php if (empty($flag['flag_resolved_at'])): ?>
    <?= form_open('manager/health/' . $flag['id'] . '/resolve') ?>
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-primary" style="margin-top:16px;"
              data-confirm="Mark this flag as resolved? The member will be notified.">
        ✓ Mark as resolved
      </button>
    <?= form_close() ?>
    <?php else: ?>
    <p style="margin-top:16px; font-size:13px; color:var(--slate-light, #718096);">
      Resolved on <?= date('j M Y, g:i A', strtotime($flag['flag_resolved_at'])) ?>.
    </p>
    <?php endif ?>
  </div>
</div>

<?= $this->endSection() ?>
