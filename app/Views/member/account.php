<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
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
