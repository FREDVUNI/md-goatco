<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-profile"><div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name']??'U',0,1).substr($currentUser['last_name']??'',0,1))) ?></div><div class="sb-profile-name"><?= esc(($currentUser['first_name']??'').' '.($currentUser['last_name']??'')) ?></div></div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/></svg>My Goats</a>
  <a href="<?= site_url('member/statements') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/></svg>Statements</a>
  <a href="<?= site_url('member/account') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3>Account details</h3></div>
    <?= form_open('member/account/update', ['class'=>'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field"><label>Email</label><input type="email" value="<?= esc($currentUser['email']??'') ?>" disabled style="opacity:.6;cursor:not-allowed"></div>
      <div class="field"><label>Phone</label><input type="tel" name="phone" value="<?= esc($currentUser['phone']??'') ?>" placeholder="+256 700 000 000"></div>
      <div class="form-actions"><button type="submit" class="btn btn-primary">Save changes</button></div>
    <?= form_close() ?>
  </div>
  <div class="card">
    <div class="card-head"><h3>Change password</h3></div>
    <?= form_open('member/account/password', ['class'=>'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field"><label>Current password *</label><input type="password" name="current_password" required></div>
      <div class="field"><label>New password *</label><input type="password" name="password" id="password" minlength="8" required></div>
      <div class="field"><label>Confirm new password *</label><input type="password" name="password_confirm" id="password_confirm" required></div>
      <div class="form-actions"><button type="submit" class="btn btn-primary">Update password</button></div>
    <?= form_close() ?>
  </div>
</div>
<?= $this->endSection() ?>
