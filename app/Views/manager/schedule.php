<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Farm Management<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<div class="sb-role">Farm Manager</div>
<nav class="sb-nav">
  <div class="sb-section">Main</div>
  <a href="<?= site_url('dashboard') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
  <div class="sb-section">Herd</div>
  <a href="<?= site_url('manager/herd') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Herd Registry</a>
  <a href="<?= site_url('manager/health') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>Health Flags</a>
  <div class="sb-section">Veterinary</div>
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Vet Schedule</a>
  <div class="sb-section">Reports</div>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
</nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Vet Schedule</h3>
    <a href="<?= site_url('manager/schedule/create') ?>" class="btn btn-primary btn-sm">+ Add task</a>
  </div>
  <?php if (empty($tasks)): ?>
    <div class="empty-state">No tasks scheduled yet. <a href="<?= site_url('manager/schedule/create') ?>">Add the first →</a></div>
  <?php else: ?>
  <table>
    <thead><tr><th>Task</th><th>Animals / Pen</th><th>Date &amp; Time</th><th>Assigned vet</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($tasks as $task): ?>
      <tr>
        <td style="font-weight:600;color:var(--ink)"><?= esc($task['task']) ?></td>
        <td><?= esc($task['animals_desc']??'—') ?></td>
        <td><?= date('j M Y, g:i A', strtotime($task['scheduled_at'])) ?></td>
        <td>
          <?php
            $vet = null;
            if ($task['assigned_vet_id']) {
              foreach (($vets??[]) as $v) { if ($v['id']==$task['assigned_vet_id']) { $vet=$v; break; } }
            }
          ?>
          <?= $vet ? esc($vet['first_name'].' '.$vet['last_name']) : '<span style="color:var(--slate-light)">Unassigned</span>' ?>
        </td>
        <td>
          <?php if ($task['status']==='completed'): ?>
            <span class="badge badge-active">Completed</span>
          <?php elseif ($task['status']==='cancelled'): ?>
            <span class="badge badge-rejected">Cancelled</span>
          <?php else: ?>
            <span class="badge badge-pending">Scheduled</span>
          <?php endif ?>
        </td>
        <td>
          <div style="display:flex;gap:6px">
            <?php if ($task['status']==='scheduled'): ?>
            <?= form_open('manager/schedule/'.$task['id'].'/complete',['style'=>'display:inline']) ?><?= csrf_field() ?>
            <button type="submit" class="btn btn-green btn-sm" data-confirm="Mark as completed?">✓ Done</button>
            <?= form_close() ?>
            <?= form_open('manager/schedule/'.$task['id'].'/delete',['style'=>'display:inline']) ?><?= csrf_field() ?>
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red)" data-confirm="Cancel this task?">Cancel</button>
            <?= form_close() ?>
            <?php endif ?>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
