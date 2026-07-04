<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div style="max-width:640px;margin:0 auto;padding:40px 24px">
  <a href="<?= site_url('auth/login') ?>" class="back-link" style="display:inline-block;margin-bottom:20px;color:var(--blue);font-weight:600;text-decoration:none">← Back to login</a>
  <h1 style="margin-bottom:6px">Apply for Goat Banking</h1>
  <p style="color:var(--slate-light);margin-bottom:28px">Fill in the form below. We'll review your application within 2–3 working days.</p>
  <?php if (!empty($errors??[])): ?><div class="form-errors" style="margin-bottom:16px"><?php foreach($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?></div><?php endif ?>
  <?= form_open('auth/register', ['class'=>'auth-form','enctype'=>'multipart/form-data']) ?>
    <?= csrf_field() ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>First name *</label><input type="text" name="first_name" value="<?= esc(old('first_name')) ?>" required></div>
      <div class="field"><label>Last name *</label><input type="text" name="last_name" value="<?= esc(old('last_name')) ?>" required></div>
    </div>
    <div class="field"><label>Email *</label><input type="email" name="email" value="<?= esc(old('email')) ?>" required></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>Phone *</label><input type="tel" name="phone" value="<?= esc(old('phone')) ?>" placeholder="+256 700 000 000" required></div>
      <div class="field"><label>Date of birth *</label><input type="date" name="dob" value="<?= esc(old('dob')) ?>" required></div>
    </div>
    <div class="field"><label>Gender *</label>
      <select name="gender" required><option value="">Select…</option><option value="male" <?= old('gender')==='male'?'selected':''?>>Male</option><option value="female" <?= old('gender')==='female'?'selected':''?>>Female</option><option value="other" <?= old('gender')==='other'?'selected':''?>>Other</option></select>
    </div>
    <div class="field"><label>Residential address *</label><input type="text" name="address" value="<?= esc(old('address')) ?>" required></div>
    <div class="field"><label>National ID number *</label><input type="text" name="nid_number" value="<?= esc(old('nid_number')) ?>" required></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>NID front photo *</label><input type="file" name="nid_front" accept="image/*,.pdf"></div>
      <div class="field"><label>NID back photo *</label><input type="file" name="nid_back" accept="image/*,.pdf"></div>
    </div>
    <div class="field"><label>Your passport photo *</label><input type="file" name="headshot" accept="image/*"></div>
    <p style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--slate-light);margin:18px 0 10px">Next of kin</p>
    <div class="field"><label>Full name *</label><input type="text" name="nok_name" value="<?= esc(old('nok_name')) ?>" required></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>Relationship *</label><input type="text" name="nok_relationship" value="<?= esc(old('nok_relationship')) ?>" required placeholder="e.g. Spouse, Parent"></div>
      <div class="field"><label>Phone *</label><input type="tel" name="nok_phone" value="<?= esc(old('nok_phone')) ?>" required></div>
    </div>
    <div class="field"><label>Next of kin NID number *</label><input type="text" name="nok_nid_number" value="<?= esc(old('nok_nid_number')) ?>" required></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>NOK NID front</label><input type="file" name="nok_nid_front" accept="image/*,.pdf"></div>
      <div class="field"><label>NOK NID back</label><input type="file" name="nok_nid_back" accept="image/*,.pdf"></div>
    </div>
    <div class="field"><label>Number of goats *</label><input type="number" name="goats_requested" value="<?= esc(old('goats_requested','1')) ?>" min="1" required></div>
    <div class="field"><label>Additional notes</label><textarea name="notes" rows="2" placeholder="Anything you'd like us to know"><?= esc(old('notes')) ?></textarea></div>
    <p style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--slate-light);margin:18px 0 10px">Set your password</p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="field"><label>Password *</label><input type="password" name="password" id="password" minlength="8" required></div>
      <div class="field"><label>Confirm password *</label><input type="password" name="password_confirm" id="password_confirm" required></div>
    </div>
    <div style="height:3px;background:var(--border);border-radius:2px;margin-bottom:6px"><div id="pw-strength-bar" style="height:100%;border-radius:2px;width:0;transition:all .3s"></div></div>
    <p id="pw-strength-label" style="font-size:0.72rem;color:var(--slate-light);margin-bottom:14px"></p>
    <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px">Submit application</button>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
