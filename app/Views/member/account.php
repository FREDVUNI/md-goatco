<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/></svg>Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/wallet') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Top Up Wallet
  </a>
  <div class="sb-section">Account</div>
  <a href="<?= site_url('member/account') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account
  </a>
  <a href="<?= site_url('member/support') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/></svg>Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="account-grid">

  <!-- Personal Details -->
  <div class="profile-card">
    <h4>Personal Details</h4>
    <?= form_open('member/account/update', ['class' => 'profile-form']) ?>
      <?= csrf_field() ?>
      <div class="profile-row"><label>Full name</label><span><?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></span></div>
      <div class="profile-row"><label>Email</label><span><?= esc($user['email'] ?? '') ?></span></div>
      <div class="profile-row edit-row">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>" placeholder="+256 700 000 000">
      </div>
      <div class="profile-row edit-row">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= esc($application['address'] ?? '') ?>" placeholder="Village, Sub-county, District">
      </div>
      <div class="profile-row"><label>National ID</label><span><?= esc($application['nid_number'] ?? '—') ?></span></div>
      <button type="submit" class="btn btn-outline btn-sm" style="margin-top:14px">Save changes</button>
    <?= form_close() ?>
  </div>

  <!-- Next of Kin -->
  <div class="profile-card">
    <h4>Next of Kin</h4>
    <div class="profile-row"><label>Full name</label><span><?= esc($application['nok_name'] ?? '—') ?></span></div>
    <div class="profile-row"><label>Relationship</label><span><?= esc(ucfirst($application['nok_relationship'] ?? '—')) ?></span></div>
    <div class="profile-row"><label>Phone</label><span><?= esc($application['nok_phone'] ?? '—') ?></span></div>
    <div class="profile-row"><label>Address</label><span><?= esc($application['nok_address'] ?? '—') ?></span></div>
    <div class="profile-row"><label>National ID</label><span><?= esc($application['nok_nid_number'] ?? '—') ?></span></div>
    <p style="font-size:0.78rem;color:var(--slate-light);margin-top:14px;">To update next-of-kin details, please <a href="<?= site_url('member/support') ?>">contact support</a>.</p>
  </div>

  <!-- Membership Info -->
  <div class="profile-card">
    <h4>Membership</h4>
    <div class="profile-row"><label>Member ID</label><span style="font-family:var(--font-mono);font-size:0.86rem;font-weight:600"><?= esc('MBR-' . str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT)) ?></span></div>
    <div class="profile-row"><label>Status</label><span><?= statusBadge($user['status']) ?></span></div>
    <div class="profile-row"><label>Member since</label><span><?= date('j F Y', strtotime($user['created_at'])) ?></span></div>
    <div class="profile-row"><label>Last login</label><span><?= $user['last_login_at'] ? date('j M Y, g:i A', strtotime($user['last_login_at'])) : 'First login' ?></span></div>
  </div>

  <!-- Security -->
  <div class="profile-card">
    <h4>Change Password</h4>
    <?= form_open('member/account/password', ['class' => 'profile-form']) ?>
      <?= csrf_field() ?>
      <div class="profile-row edit-row">
        <label for="current_password">Current password</label>
        <input type="password" id="current_password" name="current_password" placeholder="••••••••" required>
      </div>
      <div class="profile-row edit-row">
        <label for="new_password">New password</label>
        <input type="password" id="new_password" name="new_password" placeholder="Minimum 8 characters" required>
      </div>
      <div class="profile-row edit-row">
        <label for="confirm_password">Confirm new password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password" required>
      </div>
      <button type="submit" class="btn btn-outline btn-sm" style="margin-top:14px">Update password</button>
    <?= form_close() ?>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
.account-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.profile-card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 22px; }
.profile-card h4 { font-size: 0.96rem; font-weight: 700; color: var(--primary-deep); margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid var(--border); }
.profile-row { display: flex; flex-direction: column; gap: 4px; margin-bottom: 14px; }
.profile-row label { font-family: var(--font-mono); font-size: 0.68rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--slate-light); }
.profile-row span { font-size: 0.9rem; color: var(--ink); font-weight: 500; }
.edit-row input { padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 0.88rem; color: var(--ink); width: 100%; transition: border-color .15s; }
.edit-row input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(43,91,168,.1); }
@media (max-width: 760px) { .account-grid { grid-template-columns: 1fr; } }
</style>
<?= $this->endSection() ?>
