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
  <a href="<?= site_url('manager/schedule') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Vet Schedule
  </a>
  <a href="<?= site_url('manager/reports') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
  </a>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>📅 Veterinary Schedule</h3>
    <a href="<?= site_url('manager/schedule/create') ?>" class="btn btn-primary">+ Add task</a>
  </div>

  <?php if (empty($tasks)): ?>
  <div class="empty-state">No scheduled tasks yet. <a href="<?= site_url('manager/schedule/create') ?>">Add the first →</a></div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Task</th><th>Animals / Pen</th><th>Assigned vet</th>
        <th>Scheduled</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tasks as $task): ?>
      <tr>
        <td style="font-weight:600;color:var(--ink)"><?= esc($task['task']) ?></td>
        <td><?= esc($task['animals_desc'] ?? '—') ?></td>
        <td>
          <?php if ($task['first_name']): ?>
          <?= esc($task['first_name'] . ' ' . $task['last_name']) ?>
          <?php else: ?>
          <span style="color:var(--slate-light)">Unassigned</span>
          <?php endif ?>
        </td>
        <td style="white-space:nowrap"><?= date('j M Y, g:i A', strtotime($task['scheduled_at'])) ?></td>
        <td>
          <?php
            $statusMap = [
              'scheduled'   => 'badge-pending',
              'in_progress' => 'badge-pending',
              'completed'   => 'badge-active',
              'cancelled'   => 'badge-inactive',
            ];
          ?>
          <span class="badge <?= $statusMap[$task['status']] ?? 'badge-inactive' ?>">
            <?= esc(ucfirst(str_replace('_', ' ', $task['status']))) ?>
          </span>
        </td>
        <td style="display:flex;gap:8px;flex-wrap:wrap">
          <?php if ($task['status'] !== 'completed' && $task['status'] !== 'cancelled'): ?>
          <?= form_open('manager/schedule/' . $task['id'] . '/complete', ['style' => 'display:inline']) ?>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-green btn-sm"
                    data-confirm="Mark this task as completed?">✓ Done</button>
          <?= form_close() ?>
          <?= form_open('manager/schedule/' . $task['id'] . '/delete', ['style' => 'display:inline']) ?>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ghost btn-sm"
                    style="color:var(--red)"
                    data-confirm="Cancel this scheduled task?">Cancel</button>
          <?= form_close() ?>
          <?php endif ?>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
