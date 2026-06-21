<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-simple">
  <div class="auth-simple-card">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco" style="width:42px;height:42px;object-fit:contain">
      <div>
        <strong style="display:block;font-size:0.96rem;font-weight:800;color:var(--blue-deep)">MD Goatco Farm</strong>
        <small style="font-family:var(--font-mono);font-size:0.56rem;letter-spacing:0.16em;text-transform:uppercase;color:var(--blue)">Create new password</small>
      </div>
    </div>

    <h2 style="font-size:1.4rem;font-weight:700;color:var(--blue-deep);margin-bottom:6px">Set a new password</h2>
    <p class="auth-sub" style="margin-bottom:24px">Choose a strong password of at least 8 characters.</p>

    <?php if (!empty($errors ?? [])): ?>
      <div class="form-errors" style="margin-bottom:16px">
        <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
      </div>
    <?php endif ?>

    <?= form_open('auth/reset-password', ['class' => 'auth-form']) ?>
    <?= csrf_field() ?>
    <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

    <div class="field">
      <label for="password">New password</label>
      <input type="password" id="password" name="password"
        placeholder="Minimum 8 characters"
        required autocomplete="new-password"
        minlength="8">
    </div>

    <div class="field">
      <label for="password_confirm">Confirm new password</label>
      <input type="password" id="password_confirm" name="password_confirm"
        placeholder="Repeat your new password"
        required autocomplete="new-password">
    </div>

    <div id="pw-strength-bar" style="height:3px;border-radius:2px;margin:-4px 0 14px;transition:width .3s,background .3s;width:0"></div>
    <p id="pw-strength-label" style="font-size:0.72rem;color:var(--slate-light);margin-bottom:14px"></p>

    <button type="submit" class="btn btn-primary btn-full">Update password</button>
    <?= form_close() ?>

    <p class="auth-foot" style="margin-top:18px">
      <a href="<?= site_url('auth/login') ?>">← Back to login</a>
    </p>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  // Inline password strength so it works standalone without auth.js
  const pw = document.getElementById('password');
  const bar = document.getElementById('pw-strength-bar');
  const label = document.getElementById('pw-strength-label');
  const conf = document.getElementById('password_confirm');

  pw?.addEventListener('input', () => {
    const v = pw.value;
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const levels = [{
        pct: '25%',
        color: '#DC2626',
        text: 'Weak'
      },
      {
        pct: '50%',
        color: '#D97706',
        text: 'Fair'
      },
      {
        pct: '75%',
        color: '#2B5BA8',
        text: 'Good'
      },
      {
        pct: '100%',
        color: '#059669',
        text: 'Strong'
      },
    ];
    if (v.length === 0) {
      bar.style.width = '0';
      label.textContent = '';
      return;
    }
    const lvl = levels[Math.max(0, score - 1)];
    bar.style.width = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent = lvl.text;
    label.style.color = lvl.color;
  });

  conf?.addEventListener('input', () => {
    conf.style.borderColor = conf.value && conf.value !== pw.value ? 'var(--red)' : '';
  });
</script>
<?= $this->endSection() ?>