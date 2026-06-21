<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Application received, <?= esc($firstName ?? 'there') ?> 👋</h2>

<p>Thank you for applying to MD Goatco Farm's Goat Banking program. We've received your application and our team will review it within <strong>2–3 working days</strong>.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#F4F7FD; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%;">
  <tr><td style="font-size:14px; color:#4A5568;">
    <strong>What happens next?</strong><br>
    Our team verifies your ID documents and next-of-kin details, then approves your account. You'll get another email the moment a decision is made.
  </td></tr>
</table>

<p style="margin-top:24px;">
  <a href="<?= site_url('auth/status') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Check application status
  </a>
</p>

<p style="margin-top:28px; color:#718096; font-size:13px;">If you didn't apply for Goat Banking, you can safely ignore this email.</p>

<?= $this->endSection() ?>
