<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Your password has been reset</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>An administrator has reset the password on your MD Goatco Farm staff account. Here is your new temporary password:</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#F4F7FD; border-radius:8px; padding:18px 20px; margin:20px 0; width:100%; font-size:14px;">
  <tr><td style="padding:4px 0; color:#718096;">Temporary password</td><td style="padding:4px 0; text-align:right; font-weight:600; font-family:monospace; color:#1A2238;"><?= esc($tempPassword ?? '') ?></td></tr>
</table>

<p style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:8px; padding:12px 16px; font-size:13px; color:#92400E;">
  ⚠️ Please log in and change this password immediately.
</p>

<p style="margin-top:24px;">
  <a href="<?= site_url('auth/' . ($loginPath ?? 'login')) ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Log in now
  </a>
</p>

<p style="margin-top:24px; color:#718096; font-size:13px;">If you didn't expect this, contact the administrator immediately.</p>

<?= $this->endSection() ?>
