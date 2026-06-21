<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">You're approved, <?= esc($firstName ?? 'there') ?>! 🎉</h2>

<p>Great news — your Goat Banking application has been <strong style="color:#059669;">approved</strong>. Your account is now active and ready to use.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#ECFDF5; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%; border:1px solid #A7F3D0;">
  <tr><td style="font-size:14px; color:#065F46;">
    Welcome to MD Goatco Farm Goat Banking. Log in any time to view your goats, track their growth and health, and review your statements.
  </td></tr>
</table>

<p style="margin-top:24px;">
  <a href="<?= site_url('auth/login') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Log in to your dashboard
  </a>
</p>

<p style="margin-top:28px; color:#718096; font-size:13px;">Questions? Just reply to this email or reach us at hello@mdgoatco.farm.</p>

<?= $this->endSection() ?>
