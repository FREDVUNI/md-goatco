<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-simple">
  <div class="auth-simple-card">

    <a href="<?= site_url('auth/login') ?>" class="back-link" style="display:inline-flex;align-items:center;gap:6px;font-size:0.84rem;color:var(--blue);font-weight:600;margin-bottom:24px">
      ← Back to login
    </a>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco" style="width:42px;height:42px;object-fit:contain">
      <div>
        <strong style="display:block;font-size:0.96rem;font-weight:800;color:var(--blue-deep)">MD Goatco Farm</strong>
        <small style="font-family:var(--font-mono);font-size:0.56rem;letter-spacing:0.16em;text-transform:uppercase;color:var(--blue)">Reset your password</small>
      </div>
    </div>

    <h2 style="font-size:1.4rem;font-weight:700;color:var(--blue-deep);margin-bottom:6px">Forgot your password?</h2>
    <p class="auth-sub" style="margin-bottom:24px">Enter your email address and we'll send you a link to reset your password.</p>

    <?php if (!empty($errors ?? [])): ?>
      <div class="form-errors" style="margin-bottom:16px">
        <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
      </div>
    <?php endif ?>

    <?= form_open('auth/forgot-password', ['class' => 'auth-form']) ?>
    <?= csrf_field() ?>
    <div class="field">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email"
        value="<?= esc(old('email')) ?>"
        placeholder="you@example.com"
        required autocomplete="email">
    </div>
    <button type="submit" class="btn btn-primary btn-full" style="margin-top:4px">
      Send reset link
    </button>
    <?= form_close() ?>

    <p class="auth-foot" style="margin-top:20px">
      Remember your password? <a href="<?= site_url('auth/login') ?>">Log in</a>
    </p>

    <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border)">
      <p style="font-size:0.78rem;color:var(--slate-light);text-align:center">
        Back:
        <a href="<?= site_url('/') ?>">Home</a>
      </p>
    </div>

  </div>
</div>

<?= $this->endSection() ?>