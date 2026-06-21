<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">New contact form message</h2>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#F4F7FD; border-radius:8px; padding:18px 20px; margin:0 0 16px; width:100%; font-size:14px;">
  <tr><td style="padding:4px 0; color:#718096; width:90px;">From</td><td style="padding:4px 0; font-weight:600; color:#1A2238;"><?= esc($name ?? '') ?> &lt;<?= esc($email ?? '') ?>&gt;</td></tr>
  <tr><td style="padding:4px 0; color:#718096;">Subject</td><td style="padding:4px 0; font-weight:600; color:#1A2238;"><?= esc($subject ?? '') ?></td></tr>
</table>

<p style="white-space:pre-wrap;"><?= esc($message ?? '') ?></p>

<p style="margin-top:24px;">
  <a href="mailto:<?= esc($email ?? '') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Reply to <?= esc($name ?? 'sender') ?>
  </a>
</p>

<?= $this->endSection() ?>
