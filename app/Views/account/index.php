<?= $this->extend('layouts/dashboard') ?>

<?php
$role      = $currentUser['role'] ?? '';
$isAdmin   = $role === 'super_admin';
$isManager = $role === 'manager';
$isVet     = $role === 'vet';
?>

<?= $this->section('portalName') ?>
<?php if ($isAdmin): ?>Administration
<?php elseif ($isManager): ?>Farm Management
<?php elseif ($isVet): ?>Veterinary Portal
<?php else: ?>Dashboard
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?php if ($isAdmin): ?>
  <?= $this->include('admin/_sidebar') ?>
<?php elseif ($isManager): ?>
  <?= $this->include('manager/_sidebar') ?>
<?php else: ?>
  <?= $this->include('vet/_sidebar') ?>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3>Account details</h3></div>
    <?= form_open('account/update', ['class'=>'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field-row">
        <div class="field"><label>First name</label><input type="text" name="first_name" value="<?= esc(old('first_name', $user['first_name'] ?? '')) ?>" required></div>
        <div class="field"><label>Last name</label><input type="text" name="last_name" value="<?= esc(old('last_name', $user['last_name'] ?? '')) ?>" required></div>
      </div>
      <div class="field"><label>Email</label><input type="email" value="<?= esc($user['email'] ?? '') ?>" disabled style="opacity:.6;cursor:not-allowed"></div>
      <div class="field"><label>Phone</label><input type="tel" name="phone" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>" placeholder="+256 700 000 000"></div>
      <div class="field"><label>Role</label><input type="text" value="<?= esc(roleLabel($user['role'] ?? '')) ?>" disabled style="opacity:.6;cursor:not-allowed"></div>
      <div class="form-actions"><button type="submit" class="btn btn-primary">Save changes</button></div>
    <?= form_close() ?>
  </div>
  <div class="card">
    <div class="card-head"><h3>Change password</h3></div>
    <?= form_open('account/password', ['class'=>'dash-form']) ?>
      <?= csrf_field() ?>
      <div class="field"><label>Current password *</label><input type="password" name="current_password" required></div>
      <div class="field"><label>New password *</label><input type="password" name="password" id="password" minlength="8" required></div>
      <div class="field"><label>Confirm new password *</label><input type="password" name="password_confirm" id="password_confirm" required></div>
      <div class="form-actions"><button type="submit" class="btn btn-primary">Update password</button></div>
    <?= form_close() ?>
  </div>
</div>
<?= $this->endSection() ?>
