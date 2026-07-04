<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Goat Banking Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('member/_sidebar') ?>
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
