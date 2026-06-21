<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">We need a bit more information</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>We're reviewing your Goat Banking application and need a little more information before we can make a decision.</p>

<table role="presentation" cellpadding="0" cellspacing="0" style="background:#FFFBEB; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%; border:1px solid #FDE68A;">
  <tr><td style="font-size:14px; color:#92400E;">
    <strong>What we need:</strong><br><?= nl2br(esc($note ?? '')) ?>
  </td></tr>
</table>

<p>Please reply to this email or contact us with the requested details and we'll continue the review right away.</p>

<p style="margin-top:24px;">
  <a href="mailto:hello@mdgoatco.farm" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Reply with details
  </a>
</p>

<?= $this->endSection() ?>
