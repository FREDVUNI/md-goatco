<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Farm Management<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a href="<?= site_url('manager/herd') ?>" class="back-link">← Back to herd</a>
<div class="card" style="max-width:580px">
  <div class="card-head"><h3><?= isset($goat) ? 'Edit — '.esc($goat['name']) : 'Add Animal' ?></h3></div>
  <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e)?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open(isset($goat) ? 'manager/herd/'.$goat['id'].'/edit' : 'manager/herd/create', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>
    <div class="field-row">
      <div class="field"><label>Tag number *</label><input type="text" name="tag_number" value="<?= esc(old('tag_number',$goat['tag_number']??'')) ?>" required <?= isset($goat)?'readonly':'' ?> placeholder="e.g. GBK-0001"></div>
      <div class="field"><label>Name *</label><input type="text" name="name" value="<?= esc(old('name',$goat['name']??'')) ?>" required></div>
    </div>
    <div class="field-row">
      <div class="field"><label>Breed</label><input type="text" name="breed" value="<?= esc(old('breed',$goat['breed']??'')) ?>" placeholder="e.g. Kiko×Boer"></div>
      <div class="field"><label>Sex *</label><select name="sex" required><option value="">Select…</option><option value="male" <?= (old('sex',$goat['sex']??''))==='male'?'selected':'' ?>>Male</option><option value="female" <?= (old('sex',$goat['sex']??''))==='female'?'selected':'' ?>>Female</option></select></div>
    </div>
    <div class="field-row">
      <div class="field"><label>Date of birth</label><input type="date" name="dob" value="<?= esc(old('dob',$goat['dob']??'')) ?>"></div>
      <div class="field"><label>Pen</label><input type="text" name="pen_id" value="<?= esc(old('pen_id',$goat['pen_id']??'')) ?>" placeholder="e.g. Pen A"></div>
    </div>
    <div class="field">
      <label>Assign to member</label>
      <select name="member_id">
        <option value="">— Unassigned —</option>
        <?php foreach ($members??[] as $m): ?>
        <option value="<?= $m['id'] ?>" <?= (old('member_id',$goat['member_id']??''))==$m['id']?'selected':'' ?>><?= esc($m['first_name'].' '.$m['last_name']) ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <?php if (isset($goat)): ?>
    <div class="field"><label>Status</label><select name="status"><option value="active" <?= ($goat['status']??'')==='active'?'selected':'' ?>>Active</option><option value="sold" <?= ($goat['status']??'')==='sold'?'selected':'' ?>>Sold</option><option value="deceased" <?= ($goat['status']??'')==='deceased'?'selected':'' ?>>Deceased</option></select></div>
    <?php endif ?>
    <div class="field"><label>Notes</label><textarea name="notes" rows="2"><?= esc(old('notes',$goat['notes']??'')) ?></textarea></div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><?= isset($goat) ? 'Save changes' : 'Add to herd' ?></button>
      <a href="<?= site_url('manager/herd') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
