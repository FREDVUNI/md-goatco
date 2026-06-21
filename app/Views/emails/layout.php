<?php
/**
 * Shared email layout — MD Goatco Farm Limited
 *
 * Every template in this folder extends this layout, the same way the
 * dashboard views extend layouts/dashboard.php. To re-brand all outgoing
 * email (logo, colours, footer, company address) edit this ONE file.
 *
 * Table-based layout + inline-friendly CSS is used intentionally —
 * most email clients (Outlook, Gmail) strip <style> blocks or ignore
 * modern CSS, so we keep it simple and compatible.
 */
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($subject ?? 'MD Goatco Farm Limited') ?></title>
</head>
<body style="margin:0; padding:0; background:#F4F7FD; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4F7FD; padding:32px 16px;">
<tr><td align="center">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#FFFFFF; border-radius:12px; overflow:hidden; border:1px solid #D8E1F0;">

    <!-- HEADER -->
    <tr>
      <td style="background:#1E3F7A; padding:28px 32px;">
        <span style="font-family:Georgia, 'Times New Roman', serif; font-size:20px; font-weight:700; color:#FFFFFF; letter-spacing:0.2px;">
          MD Goatco Farm Limited
        </span>
        <div style="font-size:12px; color:#AFC2E4; margin-top:2px;">Ethics · Service · Genetics</div>
      </td>
    </tr>

    <!-- BODY -->
    <tr>
      <td style="padding:36px 32px; color:#1A2238; font-size:15px; line-height:1.6;">
        <?= $this->renderSection('content') ?>
      </td>
    </tr>

    <!-- FOOTER -->
    <tr>
      <td style="padding:24px 32px; background:#F8F9FC; border-top:1px solid #D8E1F0; font-size:12px; color:#718096; line-height:1.6;">
        <strong style="color:#4A5568;">MD Goatco Farm Limited</strong><br>
        Mukono, Uganda · <a href="mailto:hello@mdgoatco.farm" style="color:#2B5BA8; text-decoration:none;">hello@mdgoatco.farm</a><br>
        <span style="color:#A0AEC0;">You're receiving this because you have an account or applied for Goat Banking with MD Goatco Farm Limited.</span>
      </td>
    </tr>

  </table>

</td></tr>
</table>

</body>
</html>
