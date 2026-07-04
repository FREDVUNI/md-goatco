<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="stat-grid stat-grid-3" style="margin-bottom:22px">
  <div class="stat-card stat-blue"><div class="stat-label">Current balance</div><div class="stat-val">UGX <?= number_format($balance??0) ?></div></div>
</div>
<div class="card" style="max-width:460px">
  <div class="card-head"><h3>Top Up Wallet</h3></div>
  <?= form_open('member/wallet/topup', ['class'=>'dash-form']) ?>
    <?= csrf_field() ?>
    <div class="field">
      <label>Amount (UGX) *</label>
      <input type="number" name="amount" min="1000" step="1000" placeholder="e.g. 100000" required>
      <p class="field-hint">Minimum: UGX 1,000</p>
    </div>
    <div class="field"><label>Description (optional)</label><input type="text" name="description" placeholder="e.g. Monthly payment"></div>
    <div style="background:var(--bg);border-radius:10px;padding:14px 16px;margin-bottom:16px;font-size:0.82rem;color:var(--slate)">
      💳 You'll be securely redirected to PesaPal to complete your payment via Mobile Money or card.
    </div>
    <div class="form-actions"><button type="submit" class="btn btn-primary">Proceed to payment →</button></div>
  <?= form_close() ?>
</div>
<?= $this->endSection() ?>
