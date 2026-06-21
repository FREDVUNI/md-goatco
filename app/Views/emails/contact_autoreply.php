<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Thanks for reaching out, <?= esc($name ?? 'there') ?></h2>

<p>We've received your message and a member of our team will get back to you within <strong>1–2 working days</strong>.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#F4F7FD; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%;">
  <tr><td style="font-size:13px; color:#718096;">Your message</td></tr>
  <tr><td style="font-size:14px; color:#1A2238; padding-top:6px; white-space:pre-wrap;"><?= esc($message ?? '') ?></td></tr>
</table>

<p style="color:#718096; font-size:13px;">In the meantime, feel free to explore our <a href="<?= site_url('goat-banking') ?>" style="color:#2B5BA8;">Goat Banking program</a>.</p>

<?= $this->endSection() ?>
