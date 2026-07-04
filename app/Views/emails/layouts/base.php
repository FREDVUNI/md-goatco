<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title><?= esc($emailSubject??'MD Goatco Farm') ?></title>
<style>body{margin:0;padding:0;background:#F4F7FD;font-family:Arial,sans-serif}table{border-collapse:collapse}img{border:0}</style>
</head>
<body>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#F4F7FD"><tr><td align="center" style="padding:32px 16px">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(26,34,56,0.1)">
<tr><td style="background:linear-gradient(135deg,#1E3F7A 0%,#2B5BA8 100%);padding:32px 40px;text-align:center">
  <p style="margin:0 0 6px;font-size:20px;font-weight:800;color:#ffffff">MD Goatco Farm Limited</p>
  <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase">Ethics · Service · Genetics</p>
</td></tr>
<tr><td style="padding:40px 44px 32px"><?= $emailContent??'' ?></td></tr>
<tr><td style="padding:0 44px"><hr style="border:none;border-top:1px solid #D8E1F0;margin:0"></td></tr>
<tr><td style="padding:24px 44px 32px;text-align:center">
  <p style="margin:0 0 8px;font-size:13px;color:#718096">MD Goatco Farm Limited · Mukono–Kayunga Road, Mukono, Uganda</p>
  <p style="margin:0;font-size:12px;color:#A0AEC0">© <?= date('Y') ?> MD Goatco Farm Limited</p>
</td></tr>
</table>
</td></tr></table>
</body></html>
