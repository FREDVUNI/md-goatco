<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
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

<a href="<?= site_url('admin/applications') ?>" class="back-link">← Back to applications</a>

<div class="account-grid">

  <!-- Applicant details -->
  <div class="profile-card">
    <h4>Applicant</h4>
    <div class="profile-row"><label>Full name</label><span><?= esc($application['first_name'] . ' ' . $application['last_name']) ?></span></div>
    <div class="profile-row"><label>Email</label><span><?= esc($application['email'] ?? '—') ?></span></div>
    <div class="profile-row"><label>Phone</label><span><?= esc($application['phone']) ?></span></div>
    <div class="profile-row"><label>Date of birth</label><span><?= !empty($application['dob']) ? date('j M Y', strtotime($application['dob'])) : '—' ?></span></div>
    <div class="profile-row"><label>Gender</label><span><?= esc(ucfirst($application['gender'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Occupation</label><span><?= esc($application['occupation'] ?: '—') ?></span></div>
    <div class="profile-row"><label>Address</label><span><?= esc($application['address']) ?></span></div>
    <div class="profile-row"><label>National ID No.</label><span><?= esc($application['nid_number']) ?></span></div>
    <div class="profile-row"><label>Goats requested</label><span><?= esc($application['goats_requested']) ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($application['status']) ?></span></div>
    <div class="profile-row"><label>Submitted</label><span><?= date('j M Y, g:i A', strtotime($application['created_at'])) ?></span></div>

    <?php if (!empty($application['notes'])): ?>
    <h4 style="margin-top:20px;">Applicant's notes</h4>
    <p style="font-size:14px; color:var(--slate);"><?= nl2br(esc($application['notes'])) ?></p>
    <?php endif ?>
  </div>

  <!-- Next of kin -->
  <div class="profile-card">
    <h4>Next of Kin</h4>
    <div class="profile-row"><label>Full name</label><span><?= esc($application['nok_name']) ?></span></div>
    <div class="profile-row"><label>Relationship</label><span><?= esc(ucfirst($application['nok_relationship'])) ?></span></div>
    <div class="profile-row"><label>Phone</label><span><?= esc($application['nok_phone']) ?></span></div>
    <div class="profile-row"><label>Address</label><span><?= esc($application['nok_address'] ?: '—') ?></span></div>
    <div class="profile-row"><label>National ID No.</label><span><?= esc($application['nok_nid_number']) ?></span></div>

    <h4 style="margin-top:20px;">Documents</h4>
    <div class="doc-chips">
      <?php if (!empty($application['nid_front_path'])): ?>
      <a href="<?= site_url('admin/documents/' . $application['id'] . '/nid_front') ?>" target="_blank" class="doc-chip">📄 NID Front</a>
      <?php endif ?>
      <?php if (!empty($application['nid_back_path'])): ?>
      <a href="<?= site_url('admin/documents/' . $application['id'] . '/nid_back') ?>" target="_blank" class="doc-chip">📄 NID Back</a>
      <?php endif ?>
      <?php if (!empty($application['photo_path'])): ?>
      <a href="<?= site_url('admin/documents/' . $application['id'] . '/photo') ?>" target="_blank" class="doc-chip">🖼 Headshot</a>
      <?php endif ?>
      <?php if (!empty($application['nok_nid_front_path'])): ?>
      <a href="<?= site_url('admin/documents/' . $application['id'] . '/nok_nid_front') ?>" target="_blank" class="doc-chip">📄 NOK NID Front</a>
      <?php endif ?>
      <?php if (!empty($application['nok_nid_back_path'])): ?>
      <a href="<?= site_url('admin/documents/' . $application['id'] . '/nok_nid_back') ?>" target="_blank" class="doc-chip">📄 NOK NID Back</a>
      <?php endif ?>
      <?php if (empty($application['nid_front_path']) && empty($application['photo_path'])): ?>
      <span style="color:var(--slate-light, #718096); font-size:13px;">No documents uploaded.</span>
      <?php endif ?>
    </div>
  </div>

</div>

<?php if ($application['status'] === 'pending' || $application['status'] === 'info_requested'): ?>
<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Review Decision</h3></div>
  <div style="padding:20px; display:flex; flex-direction:column; gap:24px;">

    <?php if (!empty($application['info_request_note'])): ?>
    <div style="background:var(--amber-pale, #FFFBEB); border:1px solid #FDE68A; border-radius:8px; padding:14px 16px; font-size:13px; color:#92400E;">
      <strong>Previously requested:</strong> <?= esc($application['info_request_note']) ?>
    </div>
    <?php endif ?>

    <!-- Approve -->
    <?= form_open('admin/applications/' . $application['id'] . '/approve') ?>
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-primary" data-confirm="Approve this application and activate the member's account?">
        ✓ Approve application
      </button>
    <?= form_close() ?>

    <!-- Request more info -->
    <?= form_open('admin/applications/' . $application['id'] . '/request-info', ['class' => 'dash-form', 'style' => 'margin:0;']) ?>
      <?= csrf_field() ?>
      <div class="field">
        <label for="note">Request more information</label>
        <textarea id="note" name="note" placeholder="What do you need from the applicant?"></textarea>
      </div>
      <button type="submit" class="btn btn-outline btn-sm">Send request</button>
    <?= form_close() ?>

    <!-- Reject -->
    <?= form_open('admin/applications/' . $application['id'] . '/reject', ['class' => 'dash-form', 'style' => 'margin:0;']) ?>
      <?= csrf_field() ?>
      <div class="field">
        <label for="reason">Reject this application</label>
        <textarea id="reason" name="reason" placeholder="Reason (optional — shared with the applicant)"></textarea>
      </div>
      <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:var(--red)"
              data-confirm="Reject this application? The applicant will be notified.">
        ✕ Reject application
      </button>
    <?= form_close() ?>

  </div>
</div>
<?php elseif ($application['status'] === 'rejected'): ?>
<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Rejected</h3></div>
  <div style="padding:0 20px 20px; font-size:14px; color:var(--slate);">
    <?= !empty($application['rejection_reason']) ? nl2br(esc($application['rejection_reason'])) : 'No reason given.' ?>
  </div>
</div>
<?php else: ?>
<div class="card" style="margin-top:24px;">
  <div class="card-head"><h3>Approved</h3></div>
  <div style="padding:0 20px 20px; font-size:14px; color:var(--slate);">
    Reviewed <?= !empty($application['reviewed_at']) ? date('j M Y, g:i A', strtotime($application['reviewed_at'])) : '' ?>.
  </div>
</div>
<?php endif ?>

<?= $this->endSection() ?>
