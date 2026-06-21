<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Payment received ✅</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>We've confirmed your payment and credited your Goat Banking wallet.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#ECFDF5; border-radius:8px; padding:18px 20px; margin:20px 0; width:100%; font-size:14px; border:1px solid #A7F3D0;">
  <tr><td style="padding:4px 0; color:#065F46;">Amount</td><td style="padding:4px 0; text-align:right; font-weight:700; color:#065F46;"><?= esc(formatUgx($amount ?? 0)) ?></td></tr>
  <tr><td style="padding:4px 0; color:#065F46;">Reference</td><td style="padding:4px 0; text-align:right; font-weight:600; color:#065F46; font-family:monospace;"><?= esc($reference ?? '') ?></td></tr>
  <tr><td style="padding:4px 0; color:#065F46;">New balance</td><td style="padding:4px 0; text-align:right; font-weight:700; color:#065F46;"><?= esc(formatUgx($balance ?? 0)) ?></td></tr>
</table>

<p style="margin-top:24px;">
  <a href="<?= site_url('member/statements') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    View statement
  </a>
</p>

<?= $this->endSection() ?>
