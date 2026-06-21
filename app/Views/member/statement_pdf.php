<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Helvetica, Arial, sans-serif; color: #1A2238; font-size: 12px; margin: 0; padding: 0; }
  .header { background: #1E3F7A; color: #fff; padding: 24px 30px; }
  .header h1 { margin: 0; font-size: 18px; }
  .header p { margin: 4px 0 0; font-size: 11px; color: #AFC2E4; }
  .meta { padding: 16px 30px; border-bottom: 1px solid #D8E1F0; }
  .meta table { width: 100%; }
  .meta td { padding: 2px 0; font-size: 11px; }
  .meta .label { color: #718096; width: 140px; }
  .summary { padding: 16px 30px; }
  .summary table { width: 100%; border-collapse: collapse; }
  .summary td { padding: 10px; text-align: center; border: 1px solid #D8E1F0; }
  .summary .label { font-size: 10px; color: #718096; text-transform: uppercase; }
  .summary .val { font-size: 15px; font-weight: bold; color: #1E3F7A; }
  .txns { padding: 0 30px 30px; }
  .txns table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  .txns th { background: #F4F7FD; text-align: left; padding: 8px; font-size: 10px; text-transform: uppercase; color: #4A5568; border-bottom: 1px solid #D8E1F0; }
  .txns td { padding: 8px; font-size: 11px; border-bottom: 1px solid #EDF2FB; }
  .credit { color: #059669; }
  .debit { color: #DC2626; }
  .footer { padding: 16px 30px; font-size: 9px; color: #A0AEC0; border-top: 1px solid #D8E1F0; margin-top: 20px; }
</style>
</head>
<body>

  <div class="header">
    <h1>MD Goatco Farm Limited</h1>
    <p>Goat Banking Statement</p>
  </div>

  <div class="meta">
    <table>
      <tr><td class="label">Member</td><td><?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></td></tr>
      <tr><td class="label">Email</td><td><?= esc($user['email'] ?? '') ?></td></tr>
      <tr><td class="label">Generated</td><td><?= esc($generatedAt) ?></td></tr>
    </table>
  </div>

  <div class="summary">
    <table>
      <tr>
        <td>
          <div class="label">Current Balance</div>
          <div class="val"><?= esc(formatUgx($balance)) ?></div>
        </td>
        <td>
          <div class="label">Total Credited</div>
          <div class="val"><?= esc(formatUgx($totalCredited)) ?></div>
        </td>
        <td>
          <div class="label">Total Debited</div>
          <div class="val"><?= esc(formatUgx($totalDebited)) ?></div>
        </td>
      </tr>
    </table>
  </div>

  <div class="txns">
    <?php if (empty($transactions)): ?>
    <p>No transactions recorded yet.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Description</th>
          <th>Reference</th>
          <th>Credit</th>
          <th>Debit</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $txn): ?>
        <tr>
          <td><?= date('j M Y', strtotime($txn['created_at'])) ?></td>
          <td><?= esc($txn['description']) ?></td>
          <td><?= esc($txn['reference']) ?></td>
          <td class="credit"><?= $txn['type'] === 'credit' ? esc(formatUgx($txn['amount'])) : '—' ?></td>
          <td class="debit"><?= $txn['type'] === 'debit' ? esc(formatUgx($txn['amount'])) : '—' ?></td>
          <td><?= esc(formatUgx($txn['balance_after'])) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>

  <div class="footer">
    MD Goatco Farm Limited · Mukono–Kayunga Road, Mukono District, Uganda · hello@mdgoatco.farm<br>
    This is a system-generated statement and does not require a signature.
  </div>

</body>
</html>
