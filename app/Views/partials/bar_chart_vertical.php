<?php
/**
 * Dependency-free vertical (column) bar chart — same data shape as
 * partials/bar_chart.php, just drawn top-to-bottom instead of side-on.
 * @var array<int,string> $labels
 * @var array<int,int|float> $values  same length as $labels
 */
$max = ! empty($values) ? max($values) : 0;
?>
<div class="bar-chart-v">
  <?php if (empty($values) || $max <= 0): ?>
    <?= view('partials/empty_state', ['icon' => 'fas fa-chart-bar', 'title' => 'No data yet', 'message' => 'This chart will fill in once there\'s activity to show.']) ?>
  <?php else: ?>
    <?php foreach ($values as $i => $v): $pct = $max > 0 ? round(($v / $max) * 100) : 0; ?>
    <div class="bar-chart-v-col">
      <span class="bar-chart-v-value"><?= esc(is_float($v) ? number_format($v, 1) : $v) ?></span>
      <div class="bar-chart-v-track"><div class="bar-chart-v-fill" style="height:<?= max($pct, 3) ?>%"></div></div>
      <span class="bar-chart-v-label"><?= esc($labels[$i] ?? '') ?></span>
    </div>
    <?php endforeach ?>
  <?php endif ?>
</div>
