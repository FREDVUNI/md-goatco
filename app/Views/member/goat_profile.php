<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-profile">
  <div class="sb-profile-avatar"><?= esc(strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1))) ?></div>
  <div class="sb-profile-name"><?= esc($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
</div>
<nav class="sb-nav">
  <div class="sb-section">My Portfolio</div>
  <a href="<?= site_url('member/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <a href="<?= site_url('member/goats') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>My Goats
  </a>
  <div class="sb-section">Finances</div>
  <a href="<?= site_url('member/statements') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M3 11h18"/></svg>Statements
  </a>
  <a href="<?= site_url('member/wallet/topup') ?>" class="sb-item <?= str_starts_with(uri_string(), 'member/wallet') ? 'active' : '' ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Top Up Wallet
  </a>
  <div class="sb-section">Account</div>
  <a href="<?= site_url('member/account') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Account
  </a>
  <a href="<?= site_url('member/support') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/></svg>Support
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= site_url('member/goats') ?>" class="back-link">← Back to my goats</a>

<div class="goat-profile-grid">

  <!-- ID CARD -->
  <div class="goat-id-card">
    <div class="gic-photo">
      <span style="font-size:4rem">🐐</span>
    </div>
    <div class="gic-body">
      <div class="gic-tag"><?= esc($goat['tag_number']) ?></div>
      <div class="gic-name"><?= esc($goat['name']) ?></div>
      <div class="gic-row"><span>Breed</span><span><?= esc($goat['breed'] ?? '—') ?></span></div>
      <div class="gic-row"><span>Sex</span><span><?= esc(ucfirst($goat['sex'] ?? '—')) ?></span></div>
      <div class="gic-row"><span>Age</span><span><?= esc(goatAge($goat['dob'] ?? null)) ?></span></div>
      <div class="gic-row"><span>Date of birth</span><span><?= $goat['dob'] ? date('j M Y', strtotime($goat['dob'])) : '—' ?></span></div>
      <?php if ($latestWeight): ?>
      <div class="gic-row"><span>Current weight</span><span><?= esc($latestWeight['weight_kg']) ?> kg</span></div>
      <div class="gic-row"><span>Last weighed</span><span><?= date('j M Y', strtotime($latestWeight['logged_at'])) ?></span></div>
      <?php endif ?>
      <div class="gic-row"><span>Pen</span><span><?= esc($goat['pen_id'] ?? '—') ?></span></div>
      <?php if (empty($goat['is_flagged'])): ?>
      <div class="gic-status gic-healthy">● Healthy · Cleared by vet</div>
      <?php else: ?>
      <div class="gic-status gic-flagged">⚠ Health flag active — monitoring</div>
      <?php endif ?>
    </div>
  </div>

  <!-- RIGHT COLUMN -->
  <div class="goat-profile-right">

    <!-- WEIGHT GROWTH CHART -->
    <?php if (!empty($growthChart)): ?>
    <div class="card">
      <div class="card-head"><h3>📈 Weight Growth</h3></div>
      <div class="growth-bars">
        <?php
          $maxWeight = max(array_column($growthChart, 'max_weight'));
          foreach ($growthChart as $month):
            $pct = $maxWeight > 0 ? round(($month['avg_weight'] / $maxWeight) * 100) : 0;
        ?>
        <div class="growth-row">
          <span class="growth-month"><?= date('M', strtotime($month['month'] . '-01')) ?></span>
          <div class="growth-bar-wrap">
            <div class="growth-bar" style="width:<?= $pct ?>%"></div>
          </div>
          <span class="growth-weight"><?= number_format($month['avg_weight'], 1) ?> kg</span>
        </div>
        <?php endforeach ?>
      </div>
    </div>
    <?php endif ?>

    <!-- HEALTH HISTORY -->
    <div class="card">
      <div class="card-head"><h3>🩺 Health History</h3></div>
      <?php if (empty($healthHistory)): ?>
      <div class="empty-state">No vet visits recorded yet</div>
      <?php else: ?>
      <div class="timeline">
        <?php foreach ($healthHistory as $visit): ?>
        <div class="tl-item">
          <div class="tl-dot tl-<?= $visit['is_flagged'] ? 'amber' : 'green' ?>"></div>
          <div class="tl-content">
            <p>
              <strong><?= esc(str_replace('_', ' ', ucfirst($visit['visit_type']))) ?></strong>
              <?php if ($visit['weight_kg']): ?>— <?= esc($visit['weight_kg']) ?> kg<?php endif ?>
              <?php if ($visit['medication']): ?>— <?= esc($visit['medication']) ?><?php endif ?>
            </p>
            <span class="tl-notes"><?= esc($visit['clinical_notes']) ?></span>
            <div class="tl-meta">
              <small><?= date('j M Y', strtotime($visit['visit_date'])) ?></small>
              <span class="tl-vet">Dr. <?= esc($visit['first_name'] . ' ' . $visit['last_name']) ?></span>
            </div>
          </div>
        </div>
        <?php endforeach ?>
      </div>
      <?php endif ?>
    </div>

    <!-- WEIGHT LOG TABLE -->
    <?php if (!empty($weightHistory)): ?>
    <div class="card">
      <div class="card-head"><h3>⚖️ Weight Log</h3></div>
      <table>
        <thead><tr><th>Date</th><th>Weight</th><th>Change</th></tr></thead>
        <tbody>
          <?php
            $prev = null;
            foreach ($weightHistory as $i => $w):
              $change = $prev !== null ? $w['weight_kg'] - $prev : null;
              $prev = $w['weight_kg'];
          ?>
          <tr>
            <td><?= date('j M Y', strtotime($w['logged_at'])) ?></td>
            <td><?= esc($w['weight_kg']) ?> kg</td>
            <td>
              <?php if ($change !== null): ?>
              <span class="<?= $change >= 0 ? 'text-green' : 'text-red' ?>">
                <?= $change >= 0 ? '+' : '' ?><?= number_format($change, 1) ?> kg
              </span>
              <?php else: ?>—<?php endif ?>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <?php endif ?>

  </div><!-- /right -->
</div><!-- /profile grid -->

<?= $this->endSection() ?>
