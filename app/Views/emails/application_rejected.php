<?= $this->extend('emails/layout') ?>
<?= $this->section('content') ?>

<h2 style="margin:0 0 16px; font-size:20px; color:#1E3F7A;">Update on your application</h2>

<p>Hi <?= esc($firstName ?? 'there') ?>,</p>

<p>Thank you for your interest in MD Goatco Farm's Goat Banking program. After review, we're unable to approve your application at this time.</p>

<?php if (! empty($reason)): ?>
<table role="presentation" cellpadding="0" cellspacing="0" style="background:#FEF2F2; border-radius:8px; padding:16px 20px; margin:20px 0; width:100%; border:1px solid #FECACA;">
  <tr><td style="font-size:14px; color:#991B1B;">
    <strong>Reason given:</strong><br><?= nl2br(esc($reason)) ?>
  </td></tr>
</table>
<?php endif ?>

<p>If you believe this was a mistake or would like more information, please get in touch — we're happy to talk it through.</p>

<p style="margin-top:24px;">
  <a href="mailto:hello@mdgoatco.farm" style="display:inline-block; background:#2B5BA8; color:#FFFFFF; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:600; font-size:14px;">
    Contact us
  </a>
</p>

<?= $this->endSection() ?>
