<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">New Goat Banking application</h2>

<p><strong><?= esc($applicantName ?? '') ?></strong> has submitted a new application and is waiting for review.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#F4F7FD; border-radius:8px; padding:18px 20px; margin:20px 0; width:100%; font-size:14px;">
  <tr><td style="padding:4px 0; color:#718096; width:130px;">Goats requested</td><td style="padding:4px 0; font-weight:600; color:#1A2238;"><?= esc($goatsRequested ?? '') ?></td></tr>
  <tr><td style="padding:4px 0; color:#718096;">Submitted</td><td style="padding:4px 0; font-weight:600; color:#1A2238;"><?= date('j M Y, g:i A') ?></td></tr>
</table>

<p style="margin-top:24px;">
  <a href="<?= site_url('admin/applications') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Review applications
  </a>
</p>

<?= $this->endSection() ?>
