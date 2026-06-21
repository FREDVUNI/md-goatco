<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Payment unsuccessful</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>Your recent wallet top-up of <strong><?= esc(formatUgx($amount ?? 0)) ?></strong> (reference <?= esc($reference ?? '') ?>) could not be completed.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#FEF2F2; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%; border:1px solid #FECACA;">
  <tr><td style="font-size:14px; color:#991B1B;">
    No funds were deducted from your wallet. You can safely try again, or use a different payment method.
  </td></tr>
</table>

<p style="margin-top:24px;">
  <a href="<?= site_url('member/wallet/topup') ?>" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Try again
  </a>
</p>

<p style="margin-top:24px; color:#718096; font-size:13px;">Still having trouble? Reply to this email and we'll help sort it out.</p>

<?= $this->endSection() ?>
