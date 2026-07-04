<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1A2238; margin: 0; padding: 24px; }
  h1 { font-size: 18px; color: #2B5BA8; margin: 0 0 4px; }
  .sub { color: #718096; font-size: 11px; margin-bottom: 20px; }
  .meta { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
  .meta td { padding: 3px 0; font-size: 11px; }
  .meta td.label { color: #718096; width: 140px; }
  .summary { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  .summary td { padding: 8px 12px; background: #F4F7FD; font-size: 11px; }
  .summary td.val { font-weight: bold; font-size: 13px; }
  table.txns { width: 100%; border-collapse: collapse; }
  table.txns th { text-align: left; font-size: 10px; text-transform: uppercase; color: #718096; border-bottom: 1px solid #D8E1F0; padding: 6px 8px; }
  table.txns td { font-size: 11px; padding: 6px 8px; border-bottom: 1px solid #F0F2F7; }
  .credit { color: #059669; font-weight: bold; }
  .debit { color: #DC2626; font-weight: bold; }
  .footer { margin-top: 24px; font-size: 9px; color: #718096; }
</style>
</head>
<body>
  <h1>MD Goatco Farm — Wallet Statement</h1>
  <div class="sub">Generated <?= esc($generatedAt ?? date('j M Y, g:i A')) ?></div>

  <table class="meta">
    <tr><td class="label">Member</td><td><?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></td></tr>
    <tr><td class="label">Email</td><td><?= esc($user['email'] ?? '') ?></td></tr>
  </table>

  <table class="summary">
    <tr>
      <td>Current balance<br><span class="val">UGX <?= number_format($balance ?? 0) ?></span></td>
      <td>Total credited<br><span class="val">UGX <?= number_format($totalCredited ?? 0) ?></span></td>
      <td>Transactions<br><span class="val"><?= count($transactions ?? []) ?></span></td>
    </tr>
  </table>

  <table class="txns">
    <thead><tr><th>Date</th><th>Description</th><th>Reference</th><th>Type</th><th>Amount (UGX)</th><th>Balance</th></tr></thead>
    <tbody>
      <?php foreach (($transactions ?? []) as $t): ?>
      <tr>
        <td><?= date('j M Y', strtotime($t['created_at'])) ?></td>
        <td><?= esc($t['description'] ?? '') ?></td>
        <td><?= esc($t['reference'] ?? '—') ?></td>
        <td><?= esc(ucfirst($t['type'] ?? '')) ?></td>
        <td class="<?= ($t['type'] ?? '') === 'credit' ? 'credit' : 'debit' ?>"><?= (($t['type'] ?? '') === 'credit' ? '+' : '-') . number_format($t['amount'] ?? 0) ?></td>
        <td><?= number_format($t['balance_after'] ?? 0) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <div class="footer">MD Goatco Farm Limited — this is a system-generated statement.</div>
</body>
</html>
