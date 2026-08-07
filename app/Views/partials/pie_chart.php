<?php
/**
 * Dependency-free donut chart using a CSS conic-gradient — no JS charting
 * library, consistent with partials/bar_chart.php.
 * @var array<int,string> $labels
 * @var array<int,int|float> $values  same length as $labels
 * @var array<int,string> $colors  optional — one hex per slice, else palette
 * @var string $centerLabel  optional — text under the total in the middle (e.g. "Total")
 */
$palette = ['#2b5ba8', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2', '#db2777'];
$colors  = $colors ?? [];
$total   = array_sum($values ?? []);
?>
<div class="pie-chart">
  <?php if (empty($values) || $total <= 0): ?>
    <?= view('partials/empty_state', ['icon' => 'fas fa-chart-pie', 'title' => 'No data yet', 'message' => 'This chart will fill in once there\'s activity to show.']) ?>
  <?php else: ?>
    <?php
      $segments = [];
      $cum = 0;
      foreach ($values as $i => $v) {
        if ($v <= 0) continue;
        $color = $colors[$i] ?? $palette[$i % count($palette)];
        $start = round($cum / $total * 100, 3);
        $cum  += $v;
        $end   = round($cum / $total * 100, 3);
        $segments[] = "$color {$start}% {$end}%";
      }
    ?>
    <div class="pie-visual-wrap">
      <div class="pie-visual" style="background: conic-gradient(<?= implode(', ', $segments) ?>)"></div>
      <div class="pie-center">
        <strong><?= esc(number_format($total)) ?></strong>
        <span><?= esc($centerLabel ?? 'Total') ?></span>
      </div>
    </div>
    <div class="pie-legend">
      <?php foreach ($values as $i => $v): if ($v <= 0) continue; $color = $colors[$i] ?? $palette[$i % count($palette)]; ?>
      <div class="pie-legend-item">
        <span class="pie-dot" style="background:<?= esc($color) ?>"></span>
        <span class="pie-legend-label"><?= esc($labels[$i] ?? '') ?></span>
        <strong><?= esc(number_format($v)) ?></strong>
      </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</div>
