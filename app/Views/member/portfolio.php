<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-profile"><div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name']??'U',0,1).substr($currentUser['last_name']??'',0,1))) ?></div><div class="sb-profile-name"><?= esc(($currentUser['first_name']??'').' '.($currentUser['last_name']??'')) ?></div><div class="sb-profile-meta"><?= esc($goatCount??0) ?> goat<?= ($goatCount??0)!==1?'s':'' ?></div></div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats</a>
  <a href="<?= site_url('member/statements') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/></svg>Statements</a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Top Up Wallet</a>
  <a href="<?= site_url('member/account') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>🐐 My Goats</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($goatCount??0) ?> animal<?= ($goatCount??0)!==1?'s':'' ?></span></div>
  <?php if (empty($goats)): ?>
    <div class="empty-state">No goats assigned yet. Your animals will appear here once assigned by the farm team.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Name</th><th>Breed</th><th>Age</th><th>Weight</th><th>Last check</th><th>Health</th></tr></thead>
    <tbody>
      <?php foreach ($goats as $g): ?>
      <tr>
        <td><span class="tag"><?= esc($g['tag_number']) ?></span></td>
        <td><a href="<?= site_url('member/goats/'.$g['id']) ?>" class="link-strong"><?= esc($g['name']) ?></a></td>
        <td><?= esc($g['breed']??'—') ?></td>
        <td><?= esc(goatAge($g['dob']??null)) ?></td>
        <td><?= isset($g['latest_weight']) ? esc($g['latest_weight']).' kg' : '—' ?></td>
        <td><?= isset($g['weight_date']) ? date('j M Y', strtotime($g['weight_date'])) : '—' ?></td>
        <td><?= ($g['is_flagged']??0) ? '<span class="badge badge-flagged">Flagged</span>' : '<span class="badge badge-active">Healthy</span>' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
