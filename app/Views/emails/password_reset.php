<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Reset your password</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>We received a request to reset the password on your MD Goatco Farm account. Click the button below to choose a new password. This link expires in <strong>1 hour</strong>.</p>

<p style="margin-top:24px;">
  <a href="<?= site_url('auth/reset-password/' . ($token ?? '')) ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Reset my password
  </a>
</p>

<p style="margin-top:24px; font-size:13px; color:#718096;">If the button doesn't work, copy and paste this link into your browser:<br>
<span style="word-break:break-all; color:#2B5BA8;"><?= esc(site_url('auth/reset-password/' . ($token ?? ''))) ?></span></p>

<p style="margin-top:24px; color:#718096; font-size:13px;">If you didn't request this, you can safely ignore this email — your password will not be changed.</p>

<?= $this->endSection() ?>
