<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- SUMMARY STATS -->
<div class="stat-grid stat-grid-4" style="margin-bottom:28px">
  <div class="stat-card stat-green">
    <div class="stat-label">Births this month</div>
    <div class="stat-val"><?= esc($stats['births_this_month'] ?? 0) ?></div>
    <div class="stat-sub">New animals added</div>
  </div>
  <div class="stat-card stat-red">
    <div class="stat-label">Mortality this month</div>
    <div class="stat-val"><?= esc($stats['mortality_this_month'] ?? 0) ?></div>
    <div class="stat-sub">Animals lost</div>
  </div>
  <div class="stat-card stat-blue">
    <div class="stat-label">Report date</div>
    <div class="stat-val" style="font-size:1.2rem"><?= date('M Y') ?></div>
    <div class="stat-sub">Current period</div>
  </div>
  <div class="stat-card stat-amber">
    <div class="stat-label">Last export</div>
    <div class="stat-val" style="font-size:1.2rem">—</div>
    <div class="stat-sub">No exports yet</div>
  </div>
</div>

<!-- TRENDS -->
<div class="stat-grid stat-grid-3" style="margin-bottom:28px;align-items:start">
  <div class="card chart-card" style="margin-bottom:0">
    <div class="card-head"><h3>Herd growth</h3></div>
    <?= view('partials/bar_chart', ['labels' => $herdLabels ?? [], 'values' => $herdValues ?? []]) ?>
  </div>
  <div class="card chart-card" style="margin-bottom:0">
    <div class="card-head"><h3>Health flags</h3></div>
    <?= view('partials/bar_chart', ['labels' => $healthLabels ?? [], 'values' => $healthValues ?? []]) ?>
  </div>
  <div class="card chart-card" style="margin-bottom:0">
    <div class="card-head"><h3>Membership growth</h3></div>
    <?= view('partials/bar_chart', ['labels' => $memberLabels ?? [], 'values' => $memberValues ?? []]) ?>
  </div>
</div>

<!-- AVAILABLE REPORTS -->
<div class="card">
  <div class="card-head"><h3>Available Reports</h3></div>
  <div style="padding:0">

    <div class="report-row">
      <div class="report-info">
        <div class="report-title">Full Herd Registry</div>
        <div class="report-desc">All active animals — tag, name, breed, age, weight, pen, member owner and health status</div>
      </div>
      <div class="report-actions">
        <a href="<?= site_url('manager/reports/herd') ?>" class="btn btn-outline btn-sm">View</a>
        <a href="<?= site_url('manager/reports/export/herd') ?>" class="btn btn-ghost btn-sm">📥 CSV</a>
      </div>
    </div>

    <div class="report-row">
      <div class="report-info">
        <div class="report-title">Health Flags Report</div>
        <div class="report-desc">All active and recently resolved health flags, with vet details and dates</div>
      </div>
      <div class="report-actions">
        <a href="<?= site_url('manager/reports/health') ?>" class="btn btn-outline btn-sm">View</a>
        <a href="<?= site_url('manager/reports/export/health') ?>" class="btn btn-ghost btn-sm">📥 CSV</a>
      </div>
    </div>

    <div class="report-row">
      <div class="report-info">
        <div class="report-title">Member Portfolios</div>
        <div class="report-desc">All Goat Banking members with their goat count, join date and account status</div>
      </div>
      <div class="report-actions">
        <a href="<?= site_url('manager/reports/members') ?>" class="btn btn-outline btn-sm">View</a>
        <a href="<?= site_url('manager/reports/export/members') ?>" class="btn btn-ghost btn-sm">📥 CSV</a>
      </div>
    </div>

    <div class="report-row" style="border-bottom:none">
      <div class="report-info">
        <div class="report-title">Full Data Export</div>
        <div class="report-desc">Combined CSV export — herd, members and health data in one file</div>
      </div>
      <div class="report-actions">
        <a href="<?= site_url('manager/reports/export/all') ?>" class="btn btn-primary btn-sm">📥 Export all</a>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
.report-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 20px; border-bottom: 1px solid var(--border); gap: 16px;
  transition: background .12s ease;
}
.report-row:hover { background: var(--primary-tint, #FEFCE8); }
.report-info { flex: 1; }
.report-title { font-weight: 700; color: var(--ink); font-size: 0.94rem; margin-bottom: 4px; }
.report-desc { font-size: 0.82rem; color: var(--slate-light); }
.report-actions { display: flex; gap: 8px; flex-shrink: 0; }
</style>
<?= $this->endSection() ?>
