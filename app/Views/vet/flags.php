<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Veterinarian</div>
<nav class="sb-nav">
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/></svg>Log a Visit</a>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/></svg>Animals</a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>Health Flags<?php if(($flagCount??0)>0): ?><span class="sb-badge"><?= esc($flagCount) ?></span><?php endif ?></a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head"><h3>🚨 My Active Health Flags</h3><span style="font-size:0.84rem;color:var(--slate-light)"><?= count($flags??[]) ?> open</span></div>
  <?php if (empty($flags)): ?>
    <div class="empty-state">No active flags — great work! ✅</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Tag</th><th>Animal</th><th>Concern</th><th>Flagged on</th><th>Follow-up</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($flags as $flag): ?>
      <tr>
        <td><span class="tag"><?= esc($flag['tag_number']??'—') ?></span></td>
        <td style="font-weight:600;color:var(--ink)"><?= esc($flag['goat_name']??'—') ?></td>
        <td style="max-width:200px"><?= esc(substr($flag['flag_reason']??'—',0,60)) ?><?= strlen($flag['flag_reason']??'')>60?'…':'' ?></td>
        <td><?= date('j M Y', strtotime($flag['created_at']??'now')) ?></td>
        <td><?= $flag['followup_date'] ? date('j M Y', strtotime($flag['followup_date'])) : '—' ?></td>
        <td>
          <?= form_open('vet/flags/'.$flag['id'].'/resolve',['style'=>'display:inline']) ?><?= csrf_field() ?>
          <button type="submit" class="btn btn-green btn-sm" data-confirm="Mark this flag as resolved?">✓ Resolve</button>
          <?= form_close() ?>
          <a href="<?= site_url('vet/visits/log?goat_id='.($flag['goat_id']??'')) ?>" class="btn btn-primary btn-sm">Log update</a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
