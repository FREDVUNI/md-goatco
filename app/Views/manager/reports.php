<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Manager Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Overview</div>
  <a href="<?= site_url('manager/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard
  </a>
  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry
  </a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>Health Flags
  </a>
  <div class="sb-section">Members</div>
  <a href="<?= site_url('manager/members') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Members
  </a>
  <div class="sb-section">Operations</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
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
