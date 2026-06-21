<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Super Administrator</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('admin/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="<?= site_url('admin/applications') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Applications
  </a>
  <a href="<?= site_url('admin/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    Members
  </a>
  <div class="sb-section">Farm</div>
  <a href="<?= site_url('admin/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
    Herd Overview
  </a>
  <div class="sb-section">Staff</div>
  <a href="<?= site_url('admin/staff') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 19.07l1.41-1.41M2 12h2M20 12h2"/></svg>
    Staff Accounts
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('admin/payments') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Wallet Top-ups
  </a>
  <div class="sb-section">System</div>
  <a href="<?= site_url('admin/settings') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    Settings
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (empty($applications)): ?>
<div class="empty-page">
  <div class="empty-icon">✅</div>
  <h2>All caught up!</h2>
  <p>There are no pending applications to review.</p>
</div>
<?php else: ?>
<div class="review-grid">
  <?php foreach ($applications as $app): ?>
  <div class="review-card">
    <div class="review-card-head">
      <div>
        <strong><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></strong>
        <div class="review-app-id"><?= esc($app['id']) ?> · Submitted <?= date('j M Y', strtotime($app['created_at'])) ?></div>
      </div>
      <span class="badge badge-pending">Pending</span>
    </div>
    <div class="review-card-body">
      <div class="review-row"><span>Phone</span><span><?= esc($app['phone']) ?></span></div>
      <div class="review-row"><span>NID No.</span><span><?= esc($app['nid_number']) ?></span></div>
      <div class="review-row"><span>Next of kin</span><span><?= esc($app['nok_name'] . ' (' . ucfirst($app['nok_relationship']) . ')') ?></span></div>
      <div class="review-row"><span>Goats requested</span><span><?= esc($app['goats_requested']) ?></span></div>

      <!-- Document chips -->
      <div class="doc-chips">
        <?php if (!empty($app['nid_front_path'])): ?>
        <a href="<?= site_url('admin/documents/' . $app['id'] . '/nid_front') ?>" target="_blank" class="doc-chip">📄 NID Front</a>
        <?php endif ?>
        <?php if (!empty($app['nid_back_path'])): ?>
        <a href="<?= site_url('admin/documents/' . $app['id'] . '/nid_back') ?>" target="_blank" class="doc-chip">📄 NID Back</a>
        <?php endif ?>
        <?php if (!empty($app['photo_path'])): ?>
        <a href="<?= site_url('admin/documents/' . $app['id'] . '/photo') ?>" target="_blank" class="doc-chip">🖼 Headshot</a>
        <?php endif ?>
        <?php if (!empty($app['nok_nid_front_path'])): ?>
        <a href="<?= site_url('admin/documents/' . $app['id'] . '/nok_nid_front') ?>" target="_blank" class="doc-chip">📄 NOK NID</a>
        <?php endif ?>
      </div>

      <?php if (!empty($app['notes'])): ?>
      <div class="review-notes"><?= esc($app['notes']) ?></div>
      <?php endif ?>

      <!-- Actions -->
      <div class="review-actions">
        <?= form_open('admin/applications/' . $app['id'] . '/approve', ['style' => 'display:inline']) ?>
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-green btn-sm"
                  onclick="return confirm('Approve application for <?= esc($app['first_name']) ?>?')">
            ✓ Approve
          </button>
        <?= form_close() ?>

        <button type="button" class="btn btn-red btn-sm"
                onclick="openRejectModal(<?= $app['id'] ?>, '<?= esc($app['first_name'] . ' ' . $app['last_name']) ?>')">
          ✗ Reject
        </button>

        <button type="button" class="btn btn-ghost btn-sm"
                onclick="openInfoModal(<?= $app['id'] ?>)">
          Request info
        </button>

        <a href="<?= site_url('admin/applications/' . $app['id']) ?>" class="btn btn-outline btn-sm">View details →</a>
      </div>
    </div>
  </div>
  <?php endforeach ?>
</div>
<?php endif ?>

<!-- REJECT MODAL -->
<div class="modal-overlay" id="rejectModal">
  <div class="modal">
    <h3>Reject Application</h3>
    <p class="modal-sub" id="rejectName"></p>
    <?= form_open('', ['id' => 'rejectForm']) ?>
      <?= csrf_field() ?>
      <div class="field">
        <label>Reason for rejection (optional — sent to applicant)</label>
        <textarea name="reason" placeholder="e.g. Incomplete documents. Please reapply with clear ID scans."></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal('rejectModal')">Cancel</button>
        <button type="submit" class="btn btn-red">Confirm rejection</button>
      </div>
    <?= form_close() ?>
  </div>
</div>

<!-- REQUEST INFO MODAL -->
<div class="modal-overlay" id="infoModal">
  <div class="modal">
    <h3>Request Additional Information</h3>
    <p class="modal-sub">This note will be sent to the applicant.</p>
    <?= form_open('', ['id' => 'infoForm']) ?>
      <?= csrf_field() ?>
      <div class="field">
        <label>What information is needed? *</label>
        <textarea name="note" placeholder="e.g. Please upload a clearer photo of your National ID back." required></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal('infoModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Send request</button>
      </div>
    <?= form_close() ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openRejectModal(id, name) {
  document.getElementById('rejectName').textContent = 'Rejecting application from: ' + name;
  document.getElementById('rejectForm').action = '<?= site_url('admin/applications/') ?>' + id + '/reject';
  document.getElementById('rejectModal').classList.add('open');
}
function openInfoModal(id) {
  document.getElementById('infoForm').action = '<?= site_url('admin/applications/') ?>' + id + '/request-info';
  document.getElementById('infoModal').classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});
</script>
<?= $this->endSection() ?>
