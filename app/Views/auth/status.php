<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-simple">
  <div class="auth-simple-card" style="max-width:500px">

    <a href="<?= site_url('auth/login') ?>" class="back-link" style="display:inline-flex;align-items:center;gap:6px;font-size:0.84rem;color:var(--blue);font-weight:600;margin-bottom:24px">
      ← Back to login
    </a>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco" style="width:42px;height:42px;object-fit:contain">
      <div>
        <strong style="display:block;font-size:0.96rem;font-weight:800;color:var(--blue-deep)">MD Goatco Farm</strong>
        <small style="font-family:var(--font-mono);font-size:0.56rem;letter-spacing:0.16em;text-transform:uppercase;color:var(--blue)">Goat Banking Application</small>
      </div>
    </div>

    <h2 style="font-size:1.4rem;font-weight:700;color:var(--blue-deep);margin-bottom:6px">Check application status</h2>
    <p style="color:var(--slate);font-size:0.88rem;margin-bottom:24px">
      Enter the email address you used when applying to see the current status of your Goat Banking application.
    </p>

    <?= form_open('auth/status', ['class' => 'auth-form', 'method' => 'post']) ?>
    <?= csrf_field() ?>
    <div class="field">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email"
        value="<?= esc(old('email', $email ?? '')) ?>"
        placeholder="you@example.com"
        required autocomplete="email">
    </div>
    <button type="submit" class="btn btn-primary btn-full">Check status</button>
    <?= form_close() ?>

    <!-- Result display -->
    <?php if (isset($status)): ?>
      <div style="margin-top:24px">
        <?php if ($status === 'pending'): ?>
          <div class="status-banner status-pending">
            <span class="status-icon">⏳</span>
            <div>
              <strong>Under review</strong><br>
              Your application was received on
              <strong><?= date('j F Y', strtotime($application['created_at'])) ?></strong>
              and is currently being reviewed by our team.
              You'll receive an email once a decision has been made — usually within 2–3 working days.
            </div>
          </div>

        <?php elseif ($status === 'approved'): ?>
          <div class="status-banner status-approved">
            <span class="status-icon">✓</span>
            <div>
              <strong>Approved — your account is active!</strong><br>
              Your Goat Banking account has been activated.
              <a href="<?= site_url('auth/login') ?>" style="font-weight:600">Log in now →</a>
            </div>
          </div>

        <?php elseif ($status === 'rejected'): ?>
          <div class="status-banner status-rejected">
            <span class="status-icon">✗</span>
            <div>
              <strong>Application not approved</strong><br>
              Unfortunately we were unable to approve your application at this time.
              <?php if (!empty($application['rejection_reason'])): ?>
                <br><em><?= esc($application['rejection_reason']) ?></em>
              <?php endif ?>
              <br>Please contact us at
              <a href="mailto:hello@mdgoatco.farm" style="font-weight:600">hello@mdgoatco.farm</a>
              for more information.
            </div>
          </div>

        <?php elseif ($status === 'info_requested'): ?>
          <div class="status-banner status-pending">
            <span class="status-icon">📋</span>
            <div>
              <strong>Additional information requested</strong><br>
              Our team has requested more information from you:
              <br><em style="display:block;margin-top:6px"><?= esc($application['info_request_note'] ?? '') ?></em>
              <br>Please contact us at
              <a href="mailto:hello@mdgoatco.farm" style="font-weight:600">hello@mdgoatco.farm</a>
              to provide the requested details.
            </div>
          </div>

        <?php elseif ($status === 'not_found'): ?>
          <div class="status-banner status-rejected">
            <span class="status-icon">?</span>
            <div>
              <strong>No application found</strong><br>
              We couldn't find an application for that email address.
              <a href="<?= site_url('auth/register') ?>" style="font-weight:600">Apply now →</a>
            </div>
          </div>
        <?php endif ?>
      </div>
    <?php endif ?>

    <div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px">
      <a href="<?= site_url('auth/register') ?>" style="font-size:0.84rem;color:var(--blue);font-weight:600">
        Apply for Goat Banking →
      </a>
      <a href="<?= site_url('auth/login') ?>" style="font-size:0.84rem;color:var(--slate)">
        Already have an account? Log in
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>