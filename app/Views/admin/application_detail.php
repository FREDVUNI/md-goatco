<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
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
