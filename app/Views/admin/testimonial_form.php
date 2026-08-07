<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a href="<?= site_url('admin/testimonials') ?>" class="back-link"><i class="fas fa-arrow-left"></i> Back to testimonials</a>
<div class="card" style="max-width:640px">
  <div class="card-head"><h3><?= isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial' ?></h3></div>
  <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open_multipart(isset($testimonial) ? 'admin/testimonials/'.$testimonial['id'].'/edit' : 'admin/testimonials/create', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>
    <div class="field">
      <label>Quote *</label>
      <textarea name="quote" rows="4" required placeholder="What did they say about the farm or Goat Banking?"><?= esc(old('quote', $testimonial['quote']??'')) ?></textarea>
    </div>
    <div class="field-row">
      <div class="field"><label>Author name *</label><input type="text" name="author_name" value="<?= esc(old('author_name', $testimonial['author_name']??'')) ?>" placeholder="e.g. Esther N." required></div>
      <div class="field"><label>Author role *</label><input type="text" name="author_role" value="<?= esc(old('author_role', $testimonial['author_role']??'')) ?>" placeholder="e.g. Goat Banking member, Mukono" required></div>
    </div>
    <div class="field-row">
      <div class="field">
        <label>Rating *</label>
        <select name="rating" required>
          <?php $currentRating = (int) old('rating', $testimonial['rating']??5); ?>
          <?php for ($r = 5; $r >= 1; $r--): ?>
            <option value="<?= $r ?>" <?= $currentRating===$r?'selected':'' ?>><?= str_repeat('★', $r) ?> (<?= $r ?>)</option>
          <?php endfor ?>
        </select>
      </div>
      <div class="field">
        <label>Visible on homepage</label>
        <label style="display:flex;align-items:center;gap:8px;font-weight:500;color:var(--ink);padding:11px 0">
          <input type="checkbox" name="is_active" value="1" <?= (old('is_active', $testimonial['is_active']??1)) ? 'checked' : '' ?> style="width:auto">
          Active
        </label>
      </div>
    </div>
    <div class="field">
      <label>Photo</label>
      <?php if (!empty($testimonial['avatar_url'])): ?>
        <div style="margin-bottom:8px"><img src="<?= esc($testimonial['avatar_url']) ?>" alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--border)"></div>
      <?php endif ?>
      <input type="file" name="avatar" accept="image/*">
      <p class="field-hint">Optional. JPG/PNG, max 2 MB. Leave blank to <?= isset($testimonial) ? 'keep the current photo' : 'show initials instead' ?>.</p>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><?= isset($testimonial) ? 'Save changes' : 'Add testimonial' ?></button>
      <a href="<?= site_url('admin/testimonials') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
