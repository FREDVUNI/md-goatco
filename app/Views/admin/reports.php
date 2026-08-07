<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-head" style="flex-wrap:wrap;gap:12px">
    <div class="report-range-tabs">
      <a href="<?= site_url('admin/reports') . '?range=week' ?>" class="report-range-tab <?= $range === 'week' ? 'active' : '' ?>">Weekly</a>
      <a href="<?= site_url('admin/reports') . '?range=month' ?>" class="report-range-tab <?= $range === 'month' ? 'active' : '' ?>">Monthly</a>
      <a href="<?= site_url('admin/reports') . '?range=year' ?>" class="report-range-tab <?= $range === 'year' ? 'active' : '' ?>">Yearly</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px;margin-left:auto">
      <a href="<?= site_url('admin/reports') . '?range='.$range.'&offset='.($offset-1) ?>" class="btn btn-ghost btn-sm" title="Previous period"><i class="fas fa-chevron-left"></i></a>
      <strong style="font-size:0.92rem;color:var(--ink);min-width:150px;text-align:center"><?= esc($periodLabel) ?></strong>
      <a href="<?= site_url('admin/reports') . '?range='.$range.'&offset='.($offset+1) ?>" class="btn btn-ghost btn-sm" title="Next period"><i class="fas fa-chevron-right"></i></a>
      <?php if ($offset !== 0): ?>
      <a href="<?= site_url('admin/reports') . '?range='.$range ?>" class="btn btn-ghost btn-sm">Today</a>
      <?php endif ?>
      <a href="<?= site_url('admin/reports/export') . '?range='.$range.'&offset='.$offset ?>" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Download Excel Report</a>
    </div>
  </div>

  <div class="stat-grid stat-grid-4" style="padding:20px">
    <div class="stat-card stat-blue">
      <div class="stat-label">Applications Submitted</div>
      <div class="stat-val"><?= esc($stats['applications_submitted']) ?></div>
    </div>
    <div class="stat-card stat-green">
      <div class="stat-label">Applications Approved</div>
      <div class="stat-val"><?= esc($stats['applications_approved']) ?></div>
    </div>
    <div class="stat-card stat-red">
      <div class="stat-label">Applications Rejected</div>
      <div class="stat-val"><?= esc($stats['applications_rejected']) ?></div>
    </div>
    <div class="stat-card stat-amber">
      <div class="stat-label">New Members</div>
      <div class="stat-val"><?= esc($stats['new_members']) ?></div>
    </div>
    <div class="stat-card stat-blue">
      <div class="stat-label">New Goats Registered</div>
      <div class="stat-val"><?= esc($stats['new_goats']) ?></div>
    </div>
    <div class="stat-card stat-red">
      <div class="stat-label">Health Flags Raised</div>
      <div class="stat-val"><?= esc($stats['health_flags_raised']) ?></div>
    </div>
    <div class="stat-card stat-green">
      <div class="stat-label">Wallet Credited</div>
      <div class="stat-val" style="font-size:1.3rem">UGX <?= number_format($stats['wallet_credited']) ?></div>
    </div>
    <div class="stat-card stat-amber">
      <div class="stat-label">Wallet Debited</div>
      <div class="stat-val" style="font-size:1.3rem">UGX <?= number_format($stats['wallet_debited']) ?></div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <h3><i class="fas fa-list-ul"></i> Activity Log</h3>
    <span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($activityTotal) ?> event<?= $activityTotal === 1 ? '' : 's' ?></span>
  </div>
  <?php if (empty($activity)): ?>
    <div class="empty-state">No activity recorded for this period.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Date &amp; Time</th><th>Type</th><th>Description</th></tr></thead>
    <tbody>
      <?php foreach ($activity as $a): ?>
      <tr>
        <td style="white-space:nowrap"><?= date('j M Y, g:i A', strtotime($a['date'])) ?></td>
        <td><span class="badge badge-pending"><?= esc($a['type']) ?></span></td>
        <td><?= esc($a['description']) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php if ($activityTotal > count($activity)): ?>
    <div style="padding:14px 20px;font-size:0.82rem;color:var(--slate-light);border-top:1px solid var(--border)">
      Showing the first <?= count($activity) ?> of <?= esc($activityTotal) ?> events — download the full report above to see everything.
    </div>
  <?php endif ?>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
