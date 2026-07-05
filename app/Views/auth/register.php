<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="reg-page">
  <!-- Header -->
  <div class="reg-header">
    <a href="<?= site_url() ?>" class="auth-logo">
      <img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco">
      <div><strong>MD Goatco Farm Limited</strong><small>Ethics · Service · Genetics</small></div>
    </a>
    <a href="<?= site_url('auth/login') ?>" class="btn btn-ghost btn-sm">Already registered? Log in →</a>
  </div>

  <div class="reg-body">
    <div class="reg-intro">
      <h1>Apply for Goat Banking</h1>
      <p>Complete all four steps below. Your application will be reviewed within 2–3 working days.</p>
    </div>

    <?php if (!empty($errors ?? [])): ?>
      <div class="form-errors form-errors-block">
        <strong>Please fix the following:</strong>
        <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
      </div>
    <?php endif ?>

    <?= form_open_multipart('auth/register', ['id' => 'regForm']) ?>
    <?= csrf_field() ?>

    <!-- STEP INDICATORS -->
    <div class="step-nav">
      <div class="step-item active" id="step-nav-1" onclick="goToStep(1)">
        <div class="step-num">1</div>
        <div class="step-label">Personal details</div>
      </div>
      <div class="step-connector"></div>
      <div class="step-item" id="step-nav-2" onclick="goToStep(2)">
        <div class="step-num">2</div>
        <div class="step-label">Your ID documents</div>
      </div>
      <div class="step-connector"></div>
      <div class="step-item" id="step-nav-3" onclick="goToStep(3)">
        <div class="step-num">3</div>
        <div class="step-label">Next of kin</div>
      </div>
      <div class="step-connector"></div>
      <div class="step-item" id="step-nav-4" onclick="goToStep(4)">
        <div class="step-num">4</div>
        <div class="step-label">Account & submit</div>
      </div>
    </div>

    <!-- ── STEP 1: Personal Details ── -->
    <div class="reg-step active" id="step-1">
      <h3>Personal Details</h3>
      <div class="field-row">
        <div class="field">
          <label for="first_name">First name *</label>
          <input type="text" id="first_name" name="first_name"
            value="<?= esc(old('first_name')) ?>" placeholder="e.g. Esther" required>
        </div>
        <div class="field">
          <label for="last_name">Last name *</label>
          <input type="text" id="last_name" name="last_name"
            value="<?= esc(old('last_name')) ?>" placeholder="e.g. Nakato" required>
        </div>
      </div>
      <div class="field-row">
        <div class="field">
          <label for="dob">Date of birth *</label>
          <input type="date" id="dob" name="dob" value="<?= esc(old('dob')) ?>" required>
        </div>
        <div class="field">
          <label for="gender">Gender *</label>
          <select id="gender" name="gender" required>
            <option value="">Select…</option>
            <option value="male" <?= old('gender') === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= old('gender') === 'other' ? 'selected' : '' ?>>Prefer not to say</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="email">Email address *</label>
        <input type="email" id="email" name="email"
          value="<?= esc(old('email')) ?>" placeholder="you@example.com" required>
      </div>
      <div class="field">
        <label for="phone">Phone number *</label>
        <input type="tel" id="phone" name="phone"
          value="<?= esc(old('phone')) ?>" placeholder="+256 700 000 000" required>
      </div>
      <div class="field">
        <label for="address">Physical / home address *</label>
        <input type="text" id="address" name="address"
          value="<?= esc(old('address')) ?>" placeholder="Village, Sub-county, District" required>
      </div>
      <div class="field">
        <label for="occupation">Occupation</label>
        <input type="text" id="occupation" name="occupation"
          value="<?= esc(old('occupation')) ?>" placeholder="e.g. Farmer, Teacher, Business owner">
      </div>
      <div class="step-actions">
        <span></span>
        <button type="button" class="btn btn-primary" onclick="goToStep(2)">Next: Your ID documents →</button>
      </div>
    </div>

    <!-- ── STEP 2: ID Documents ── -->
    <div class="reg-step" id="step-2">
      <h3>Your Identification Documents</h3>
      <p class="step-note">These are required for KYC verification and are stored securely. They are never shared publicly.</p>

      <div class="field">
        <label for="nid_number">National ID number *</label>
        <input type="text" id="nid_number" name="nid_number"
          value="<?= esc(old('nid_number')) ?>" placeholder="CM00000000000" required>
      </div>
      <div class="field">
        <label>Upload front of National ID *</label>
        <label class="file-upload-label" for="nid_front">
          <div class="file-upload-inner">
            <span class="file-icon">📎</span>
            <div><strong>Click to upload</strong> — front side of your National ID</div>
            <span class="file-hint">JPG, PNG or PDF · max 5 MB</span>
          </div>
        </label>
        <input type="file" id="nid_front" name="nid_front" accept="image/*,.pdf" class="file-input" required>
        <div class="file-preview" id="preview-nid_front"></div>
      </div>
      <div class="field">
        <label>Upload back of National ID *</label>
        <label class="file-upload-label" for="nid_back">
          <div class="file-upload-inner">
            <span class="file-icon">📎</span>
            <div><strong>Click to upload</strong> — back side of your National ID</div>
            <span class="file-hint">JPG, PNG or PDF · max 5 MB</span>
          </div>
        </label>
        <input type="file" id="nid_back" name="nid_back" accept="image/*,.pdf" class="file-input" required>
        <div class="file-preview" id="preview-nid_back"></div>
      </div>
      <div class="field">
        <label>Passport-size photo of yourself (optional)</label>
        <p class="step-note" style="margin:-4px 0 10px">Not required if you've already uploaded both sides of your National ID above.</p>
        <label class="file-upload-label" for="headshot">
          <div class="file-upload-inner">
            <span class="file-icon">📷</span>
            <div><strong>Click to upload</strong> — a clear, recent headshot</div>
            <span class="file-hint">JPG or PNG · max 5 MB</span>
          </div>
        </label>
        <input type="file" id="headshot" name="headshot" accept="image/*" class="file-input">
        <div class="file-preview" id="preview-headshot"></div>
      </div>

      <div class="step-actions">
        <button type="button" class="btn btn-ghost" onclick="goToStep(1)">← Back</button>
        <button type="button" class="btn btn-primary" onclick="goToStep(3)">Next: Next of kin →</button>
      </div>
    </div>

    <!-- ── STEP 3: Next of Kin ── -->
    <div class="reg-step" id="step-3">
      <h3>Next of Kin Details</h3>
      <div class="field-row">
        <div class="field">
          <label for="nok_name">Full name *</label>
          <input type="text" id="nok_name" name="nok_name"
            value="<?= esc(old('nok_name')) ?>" placeholder="e.g. Joseph Nakato" required>
        </div>
        <div class="field">
          <label for="nok_relationship">Relationship *</label>
          <select id="nok_relationship" name="nok_relationship" required>
            <option value="">Select…</option>
            <?php foreach (['Spouse', 'Parent', 'Sibling', 'Child', 'Other'] as $r): ?>
              <option value="<?= strtolower($r) ?>" <?= old('nok_relationship') === strtolower($r) ? 'selected' : '' ?>><?= $r ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="field-row">
        <div class="field">
          <label for="nok_phone">Phone number *</label>
          <input type="tel" id="nok_phone" name="nok_phone"
            value="<?= esc(old('nok_phone')) ?>" placeholder="+256 700 000 000" required>
        </div>
        <div class="field">
          <label for="nok_address">Address</label>
          <input type="text" id="nok_address" name="nok_address"
            value="<?= esc(old('nok_address')) ?>" placeholder="Village, Sub-county, District">
        </div>
      </div>

      <div class="section-divider">Next of kin identification</div>

      <div class="field">
        <label for="nok_nid_number">National ID number *</label>
        <input type="text" id="nok_nid_number" name="nok_nid_number"
          value="<?= esc(old('nok_nid_number')) ?>" placeholder="CM00000000000" required>
      </div>
      <div class="field">
        <label>Upload next of kin's National ID (front) *</label>
        <label class="file-upload-label" for="nok_nid_front">
          <div class="file-upload-inner">
            <span class="file-icon">📎</span>
            <div><strong>Click to upload</strong> — front of next of kin's National ID</div>
            <span class="file-hint">JPG, PNG or PDF · max 5 MB</span>
          </div>
        </label>
        <input type="file" id="nok_nid_front" name="nok_nid_front" accept="image/*,.pdf" class="file-input" required>
        <div class="file-preview" id="preview-nok_nid_front"></div>
      </div>
      <div class="field">
        <label>Upload next of kin's National ID (back) *</label>
        <label class="file-upload-label" for="nok_nid_back">
          <div class="file-upload-inner">
            <span class="file-icon">📎</span>
            <div><strong>Click to upload</strong> — back of next of kin's National ID</div>
            <span class="file-hint">JPG, PNG or PDF · max 5 MB</span>
          </div>
        </label>
        <input type="file" id="nok_nid_back" name="nok_nid_back" accept="image/*,.pdf" class="file-input" required>
        <div class="file-preview" id="preview-nok_nid_back"></div>
      </div>

      <div class="step-actions">
        <button type="button" class="btn btn-ghost" onclick="goToStep(2)">← Back</button>
        <button type="button" class="btn btn-primary" onclick="goToStep(4)">Next: Create account →</button>
      </div>
    </div>

    <!-- ── STEP 4: Account & Submit ── -->
    <div class="reg-step" id="step-4">
      <h3>Create Your Account</h3>
      <div class="field">
        <label for="password">Create a password *</label>
        <input type="password" id="password" name="password"
          placeholder="Minimum 8 characters" required>
      </div>
      <div class="field">
        <label for="password_confirm">Confirm password *</label>
        <input type="password" id="password_confirm" name="password_confirm"
          placeholder="Repeat your password" required>
      </div>

      <div class="section-divider">Banking preferences</div>

      <div class="field">
        <label for="goats_requested">How many goats would you like to start with? *</label>
        <select id="goats_requested" name="goats_requested" required>
          <option value="">Select…</option>
          <?php foreach (['1–5', '6–10', '11–20', '20+'] as $g): ?>
            <option value="<?= $g ?>" <?= old('goats_requested') === $g ? 'selected' : '' ?>><?= $g ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="field">
        <label for="notes">Additional notes or questions</label>
        <textarea id="notes" name="notes" placeholder="Anything you'd like us to know before we contact you…"><?= esc(old('notes')) ?></textarea>
      </div>

      <div class="consent-box">
        By submitting this application you agree to our
        <a href="<?= site_url('terms') ?>" target="_blank">Terms of Service</a> and
        <a href="<?= site_url('privacy-policy') ?>" target="_blank">Privacy Policy</a>.
        Our team will review your application and contact you within 2–3 working days.
        <br><br>
        <label class="checkbox-label">
          <input type="checkbox" name="consent" value="1" required>
          I agree to the Terms of Service and Privacy Policy *
        </label>
      </div>

      <div class="step-actions">
        <button type="button" class="btn btn-ghost" onclick="goToStep(3)">← Back</button>
        <button type="submit" class="btn btn-primary">✓ Submit application</button>
      </div>
    </div>

    <?= form_close() ?>
  </div><!-- /reg-body -->
</div><!-- /reg-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>