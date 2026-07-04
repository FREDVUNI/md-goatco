<?php
/**
 * Dependency-free horizontal bar chart.
 * @var array<string,mixed> $labels  positional array of string labels
 * @var array<string,mixed> $values  positional array of numeric values, same length as $labels
 */
$max = ! empty($values) ? max($values) : 0;
?>
<div class="bar-chart">
  <?php if (empty($values) || $max <= 0): ?>
    <div class="empty-state">No data yet</div>
  <?php else: ?>
    <?php foreach ($values as $i => $v): $pct = $max > 0 ? round(($v / $max) * 100) : 0; ?>
    <div class="bar-chart-row">
      <span class="bar-chart-label"><?= esc($labels[$i] ?? '') ?></span>
      <div class="bar-chart-track"><div class="bar-chart-fill" style="width:<?= $pct ?>%"></div></div>
      <span class="bar-chart-value"><?= esc(is_float($v) ? number_format($v, 1) : $v) ?></span>
    </div>
    <?php endforeach ?>
  <?php endif ?>
</div>
