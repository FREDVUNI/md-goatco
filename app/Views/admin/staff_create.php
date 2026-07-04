<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a href="<?= site_url('admin/staff') ?>" class="back-link">← Back to staff</a>
<div class="card" style="max-width:560px">
  <div class="card-head"><h3><?= isset($staff) ? 'Edit Staff Account' : 'Create Staff Account' ?></h3></div>
  <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open(isset($staff) ? 'admin/staff/'.$staff['id'].'/edit' : 'admin/staff/create', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>
    <div class="field-row">
      <div class="field"><label>First name *</label><input type="text" name="first_name" value="<?= esc(old('first_name', $staff['first_name']??'')) ?>" required></div>
      <div class="field"><label>Last name *</label><input type="text" name="last_name" value="<?= esc(old('last_name', $staff['last_name']??'')) ?>" required></div>
    </div>
    <div class="field"><label>Email address *</label><input type="email" name="email" value="<?= esc(old('email', $staff['email']??'')) ?>" required <?= isset($staff)?'readonly':'' ?>></div>
    <div class="field"><label>Phone</label><input type="tel" name="phone" value="<?= esc(old('phone', $staff['phone']??'')) ?>" placeholder="+256 700 000 000"></div>
    <div class="field">
      <label>Role *</label>
      <select name="role" required>
        <option value="">Select role…</option>
        <option value="vet" <?= (old('role',$staff['role']??''))==='vet'?'selected':'' ?>>Veterinarian</option>
        <option value="manager" <?= (old('role',$staff['role']??''))==='manager'?'selected':'' ?>>Farm Manager</option>
        <option value="super_admin" <?= (old('role',$staff['role']??''))==='super_admin'?'selected':'' ?>>Super Administrator</option>
      </select>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><?= isset($staff) ? 'Save changes' : 'Create account' ?></button>
      <a href="<?= site_url('admin/staff') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
