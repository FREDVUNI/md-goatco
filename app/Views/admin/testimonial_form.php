<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a href="<?= site_url('admin/testimonials') ?>" class="back-link"><i class="fas fa-arrow-left"></i> Back to testimonials</a>
<div class="card">
  <div class="card-head"><h3><?= isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial' ?></h3></div>
  <?php if (!empty($errors??[])): ?><div class="form-errors"><?php foreach($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open_multipart(isset($testimonial) ? 'admin/testimonials/'.$testimonial['id'].'/edit' : 'admin/testimonials/create', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>

    <div class="field">
      <label>Quote *</label>
      <textarea name="quote" rows="4" required placeholder="What did they say about the farm or Goat Banking?"><?= esc(old('quote', $testimonial['quote']??'')) ?></textarea>
    </div>

    <div class="field-row field-row-3">
      <div class="field"><label>Author name *</label><input type="text" name="author_name" value="<?= esc(old('author_name', $testimonial['author_name']??'')) ?>" placeholder="e.g. Esther N." required></div>
      <div class="field"><label>Author role *</label><input type="text" name="author_role" value="<?= esc(old('author_role', $testimonial['author_role']??'')) ?>" placeholder="e.g. Goat Banking member, Mukono" required></div>
      <div class="field">
        <label>Rating *</label>
        <select name="rating" required>
          <?php $currentRating = (int) old('rating', $testimonial['rating']??5); ?>
          <?php for ($r = 5; $r >= 1; $r--): ?>
            <option value="<?= $r ?>" <?= $currentRating===$r?'selected':'' ?>><?= str_repeat('★', $r) ?> (<?= $r ?>)</option>
          <?php endfor ?>
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Photo</label>
        <?php if (!empty($testimonial['avatar_url'])): ?>
          <div class="file-preview" style="margin-bottom:10px">
            <img src="<?= esc($testimonial['avatar_url']) ?>" alt="" class="file-thumb file-thumb-round">
            <span class="file-name" style="color:var(--slate-light)">Current photo</span>
          </div>
        <?php endif ?>
        <label class="file-upload-label" for="avatar">
          <div class="file-upload-inner">
            <span class="file-icon"><i class="fas fa-camera"></i></span>
            <div><strong>Click to upload</strong> — a clear photo of the person</div>
            <span class="file-hint">JPG or PNG · max 2 MB</span>
          </div>
        </label>
        <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input" data-max-size="2097152" data-round="true">
        <div class="file-preview" id="preview-avatar"></div>
        <p class="field-hint">Optional. Leave blank to <?= isset($testimonial) ? 'keep the current photo' : 'show initials instead' ?>.</p>
      </div>
      <div class="field">
        <label>Visible on homepage</label>
        <label style="display:flex;align-items:center;gap:8px;font-weight:500;color:var(--ink);padding:11px 0">
          <input type="checkbox" name="is_active" value="1" <?= (old('is_active', $testimonial['is_active']??1)) ? 'checked' : '' ?> style="width:auto">
          Active — shown on the public homepage
        </label>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><?= isset($testimonial) ? 'Save changes' : 'Add testimonial' ?></button>
      <a href="<?= site_url('admin/testimonials') ?>" class="btn btn-ghost">Cancel</a>
    </div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
