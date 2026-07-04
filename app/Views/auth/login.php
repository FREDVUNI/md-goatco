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
        <h1>One login.<br><em>Every role.</em><br>One farm.</h1>
        <p>Members, vets, managers and administrators all log in here. Your dashboard is waiting on the other side.</p>
      </div>

      <!-- Animated role cards -->
      <div class="role-preview-list">
        <div class="role-preview-item">
          <span class="rp-dot" style="background:#A78BFA"></span>
          <span class="rp-label">Super Admin</span>
          <span class="rp-desc">Applications · Staff · Herd · Settings</span>
        </div>
        <div class="role-preview-item">
          <span class="rp-dot" style="background:#FBBF24"></span>
          <span class="rp-label">Farm Manager</span>
          <span class="rp-desc">Herd · Health Flags · Members · Reports</span>
        </div>
        <div class="role-preview-item">
          <span class="rp-dot" style="background:#34D399"></span>
          <span class="rp-label">Veterinarian</span>
          <span class="rp-desc">Tasks · Visits · Animals · Flags</span>
        </div>
        <div class="role-preview-item">
          <span class="rp-dot" style="background:#60A5FA"></span>
          <span class="rp-label">Goat Banking Member</span>
          <span class="rp-desc">My Goats · Statements · Account</span>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT: login form -->
  <div class="auth-right">
    <div class="auth-card">

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
        <div class="form-errors" style="margin-bottom:16px"><p><?= esc(session('error')) ?></p></div>
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

        <div class="auth-divider">new to Goat Banking?</div>
        <a href="<?= site_url('auth/register') ?>" class="btn btn-ghost btn-full">Apply for Goat Banking →</a>
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
          <div class="status-banner status-pending"><span>⏳</span><div><strong>Under review.</strong> Submitted <?= date('j M Y', strtotime($application['created_at'])) ?>. We'll email you when a decision is made.</div></div>
          <?php elseif ($status === 'approved'): ?>
          <div class="status-banner status-approved"><span>✓</span><div><strong>Approved!</strong> <a href="#" onclick="switchTab('login');return false">Log in now →</a></div></div>
          <?php elseif ($status === 'rejected'): ?>
          <div class="status-banner status-rejected"><span>✗</span><div><strong>Not approved.</strong> Contact <a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a></div></div>
          <?php elseif ($status === 'info_requested'): ?>
          <div class="status-banner status-pending"><span>📋</span><div><strong>More info needed:</strong> <?= esc($application['info_request_note'] ?? '') ?></div></div>
          <?php elseif ($status === 'not_found'): ?>
          <div class="status-banner status-rejected"><span>?</span><div>No application found. <a href="<?= site_url('auth/register') ?>">Apply now →</a></div></div>
          <?php endif ?>
        </div>
        <?php endif ?>

        <p class="auth-foot" style="margin-top:16px">
          <a href="#" onclick="switchTab('login');return false">← Back to login</a>
        </p>
      </div>

    </div><!-- /auth-card -->
  </div><!-- /auth-right -->
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
.role-preview-list { display:grid; gap:10px; }
.role-preview-item {
  display:flex; align-items:center; gap:10px;
  background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1);
  border-radius:10px; padding:10px 14px;
}
.rp-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.rp-label { font-weight:700; color:#fff; font-size:0.84rem; min-width:120px; }
.rp-desc  { font-size:0.74rem; color:rgba(255,255,255,0.5); }
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
