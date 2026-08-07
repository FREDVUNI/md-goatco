<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-split">

  <!-- LEFT: brand panel -->
  <div class="auth-left auth-left-blue">
    <div class="auth-left-inner">
      <a href="<?= site_url('/') ?>" class="auth-logo">
        <img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco">
        <div>
          <strong>MD Goatco Farm Limited</strong>
          <small>Ethics · Service · Genetics</small>
        </div>
      </a>

      <div class="auth-hero">
        <div class="auth-role-badge">MD Goatco Farm Portal</div>
        <h1>One login.<br><em>Welcome Back!</em><br>One farm.</h1>
        <p>One door for everyone who keeps this farm running — from daily care to full oversight. Your dashboard is waiting on the other side.</p>
      </div>
    </div>
  </div>

  <!-- RIGHT: login form -->
  <div class="auth-right">
    <div class="auth-card">

      <!-- Only visible on mobile, where the left brand panel (with its own
           logo/home link) is hidden — otherwise there's no way back. -->
      <a href="<?= site_url('/') ?>" class="auth-card-home-link">
        <img src="<?= base_url('img/logo.png') ?>" alt="MD Goatco">
        <span>MD Goatco Farm</span>
      </a>

      <!-- Tab strip: Log in | Check status -->
      <div class="auth-tabs">
        <button class="auth-tab active" onclick="switchTab('login', this)">Log in</button>
        <button class="auth-tab" onclick="switchTab('status', this)">Check status</button>
      </div>

      <!-- LOGIN PANE -->
      <div class="auth-pane" id="pane-login">
        <h2>Welcome back</h2>
        <p class="auth-sub">Enter your email and password to access your dashboard</p>

        <?php if (session()->has('warning')): ?>
          <div class="status-banner status-pending" style="margin-bottom:16px">
            <span>⏳</span>
            <div><?= esc(session('warning')) ?></div>
          </div>
        <?php endif ?>

        <?php if (session()->has('error')): ?>
          <div class="form-errors" style="margin-bottom:16px">
            <p><?= esc(session('error')) ?></p>
          </div>
        <?php endif ?>

        <?php if (session()->has('success')): ?>
          <div style="background:var(--green-pale);border:1px solid #A7F3D0;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.84rem;color:#065F46">
            <?= esc(session('success')) ?>
          </div>
        <?php endif ?>

        <?php if (!empty($errors ?? [])): ?>
          <div class="form-errors">
            <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
          </div>
        <?php endif ?>

        <?= form_open('auth/login', ['class' => 'auth-form']) ?>
        <?= csrf_field() ?>
        <div class="field">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email"
            value="<?= esc(old('email')) ?>"
            placeholder="you@example.com"
            required autocomplete="username">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password"
            placeholder="••••••••"
            required autocomplete="current-password">
        </div>
        <div class="field-aux">
          <a href="<?= site_url('auth/forgot-password') ?>">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Log in to my dashboard</button>
        <?= form_close() ?>

        <?php if (! empty($googleEnabled)): ?>
        <div class="auth-divider">or</div>
        <a href="<?= site_url('auth/google') ?>" class="btn-google">
          <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
            <path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 01-1.8 2.72v2.26h2.9c1.7-1.57 2.7-3.88 2.7-6.62z"/>
            <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.9-2.26c-.81.54-1.85.86-3.06.86-2.35 0-4.34-1.59-5.05-3.72H.9v2.33A9 9 0 009 18z"/>
            <path fill="#FBBC05" d="M3.95 10.7A5.4 5.4 0 013.68 9c0-.59.1-1.16.27-1.7V4.97H.9A9 9 0 000 9c0 1.45.35 2.83.9 4.03l3.05-2.33z"/>
            <path fill="#EA4335" d="M9 3.58c1.32 0 2.51.46 3.44 1.35l2.58-2.58C13.46.89 11.43 0 9 0A9 9 0 00.9 4.97l3.05 2.33C4.66 5.17 6.65 3.58 9 3.58z"/>
          </svg>
          Sign in with Google
        </a>
        <p class="field-hint" style="text-align:center;margin-top:8px">Only works for an email already registered with an active account.</p>
        <?php endif ?>

        <div class="auth-divider">new to Goat Banking?</div>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-ghost btn-full">Apply for Goat Banking <i class="fas fa-arrow-right"></i></a>
        <p class="auth-foot">Already applied?
          <a href="#" onclick="switchTab('status');return false">Check your application status</a>
        </p>
      </div>

      <!-- STATUS CHECK PANE -->
      <div class="auth-pane" id="pane-status" style="display:none">
        <h2>Check your status</h2>
        <p class="auth-sub">Enter the email you used when applying</p>

        <?= form_open('auth/status', ['class' => 'auth-form', 'method' => 'post']) ?>
        <?= csrf_field() ?>
        <div class="field">
          <label for="check_email">Email address</label>
          <input type="email" id="check_email" name="email"
            value="<?= esc(old('email', $email ?? '')) ?>"
            placeholder="you@example.com" required>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Check status</button>
        <?= form_close() ?>

        <?php if (isset($status)): ?>
          <div class="status-result" style="margin-top:18px">
            <?php if ($status === 'pending'): ?>
              <div class="status-banner status-pending"><span>⏳</span>
                <div><strong>Under review.</strong> Submitted <?= date('j M Y', strtotime($application['created_at'])) ?>. We'll email you when a decision is made.</div>
              </div>
            <?php elseif ($status === 'approved'): ?>
              <div class="status-banner status-approved"><span>✓</span>
                <div><strong>Approved!</strong> <a href="#" onclick="switchTab('login');return false">Log in now <i class="fas fa-arrow-right"></i></a></div>
              </div>
            <?php elseif ($status === 'rejected'): ?>
              <div class="status-banner status-rejected"><span>✗</span>
                <div><strong>Not approved.</strong> Contact <a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a></div>
              </div>
            <?php elseif ($status === 'info_requested'): ?>
              <div class="status-banner status-pending"><span><i class="fas fa-clipboard-list"></i></span>
                <div><strong>More info needed:</strong> <?= esc($application['info_request_note'] ?? '') ?></div>
              </div>
            <?php elseif ($status === 'not_found'): ?>
              <div class="status-banner status-rejected"><span>?</span>
                <div>No application found. <a href="<?= site_url('auth/register') ?>">Apply now <i class="fas fa-arrow-right"></i></a></div>
              </div>
            <?php endif ?>
          </div>
        <?php endif ?>

        <p class="auth-foot" style="margin-top:16px">
          <a href="#" onclick="switchTab('login');return false"><i class="fas fa-arrow-left"></i> Back to login</a>
        </p>
      </div>

    </div><!-- /auth-card -->
  </div><!-- /auth-right -->
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
  .role-preview-list {
    display: grid;
    gap: 10px;
  }

  .role-preview-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 10px 14px;
  }

  .rp-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .rp-label {
    font-weight: 700;
    color: #fff;
    font-size: 0.84rem;
    min-width: 120px;
  }

  .rp-desc {
    font-size: 0.74rem;
    color: rgba(255, 255, 255, 0.5);
  }
</style>
<script>
  function switchTab(tab, el) {
    document.querySelectorAll('.auth-pane').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('pane-' + tab).style.display = 'block';
    if (el) el.classList.add('active');
    else {
      const tabs = document.querySelectorAll('.auth-tab');
      if (tab === 'login') tabs[0].classList.add('active');
      else tabs[1].classList.add('active');
    }
  }
</script>
<?= $this->endSection() ?>