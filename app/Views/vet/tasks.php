<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('vet/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- TODAY'S TASKS -->
<div class="card">
  <div class="card-head">
    <h3>📋 Today's Tasks — <?= esc($today) ?></h3>
    <span style="font-size:0.84rem;color:var(--slate-light)">
      <?= count(array_filter($tasks, fn($t) => $t['status'] === 'completed')) ?> / <?= count($tasks) ?> completed
    </span>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tasks…" value="<?= esc($search ?? '') ?>">
    </form>
  </div>

  <?php if (empty($tasks)): ?>
  <div class="empty-state">
    No tasks scheduled for today 🎉
    <br><br>
    <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-primary btn-sm">+ Log a visit</a>
  </div>
  <?php else: ?>
  <div style="padding:0">
    <?php foreach ($tasks as $task): ?>
    <div class="task-row <?= $task['status'] === 'completed' ? 'task-done' : '' ?>">
      <div class="task-status-dot">
        <?php if ($task['status'] === 'completed'): ?>
        <span class="task-check">✓</span>
        <?php else: ?>
        <span class="task-pending-dot"></span>
        <?php endif ?>
      </div>
      <div class="task-body">
        <div class="task-name"><?= esc($task['task']) ?></div>
        <?php if ($task['description']): ?>
        <div class="task-desc"><?= esc($task['description']) ?></div>
        <?php endif ?>
        <?php if ($task['animals_desc']): ?>
        <div class="task-meta">🐐 <?= esc($task['animals_desc']) ?></div>
        <?php endif ?>
        <div class="task-time">🕐 <?= date('g:i A', strtotime($task['scheduled_at'])) ?></div>
      </div>
      <div class="task-actions">
        <?php if ($task['status'] !== 'completed'): ?>
        <?= form_open('vet/tasks/' . $task['id'] . '/complete', ['style' => 'display:inline']) ?>
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-green btn-sm">✓ Mark done</button>
        <?= form_close() ?>
        <a href="<?= site_url('vet/visits/log') ?>" class="btn btn-outline btn-sm">Log visit</a>
        <?php else: ?>
        <span class="badge badge-active">Completed</span>
        <?php endif ?>
      </div>
    </div>
    <?php endforeach ?>
  </div>
  <?= $pager->links('today', 'dashboard') ?>
  <?php endif ?>
</div>

<!-- UPCOMING (next 7 days) -->
<?php if (!empty($upcoming)): ?>
<div class="card">
  <div class="card-head"><h3>📅 Upcoming — Next 7 days</h3></div>
  <table>
    <thead><tr><th>Task</th><th>Animals</th><th>Date &amp; Time</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($upcoming as $u): ?>
      <tr>
        <td style="font-weight:600;color:var(--ink)"><?= esc($u['task']) ?></td>
        <td><?= esc($u['animals_desc'] ?? '—') ?></td>
        <td><?= date('j M, g:i A', strtotime($u['scheduled_at'])) ?></td>
        <td><span class="badge badge-pending">Scheduled</span></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?= $pager->links('upcoming', 'dashboard') ?>
</div>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
.task-row {
  display: flex; align-items: center; gap: 16px; padding: 16px 20px;
  border-bottom: 1px solid var(--border); transition: background .12s ease;
}
.task-row:last-child { border-bottom: none; }
.task-row:hover { background: var(--primary-tint, var(--green-tint, #F0FDF4)); }
.task-done { opacity: .6; }
.task-status-dot { width: 28px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.task-check { width: 24px; height: 24px; border-radius: 50%; background: var(--green); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.74rem; font-weight: 700; }
.task-pending-dot { width: 12px; height: 12px; border-radius: 50%; border: 2px solid var(--border); background: var(--white); }
.task-body { flex: 1; }
.task-name { font-weight: 600; color: var(--ink); font-size: 0.94rem; margin-bottom: 3px; }
.task-desc { font-size: 0.82rem; color: var(--slate); margin-bottom: 4px; }
.task-meta { font-size: 0.8rem; color: var(--slate); margin-bottom: 3px; }
.task-time { font-size: 0.78rem; color: var(--slate-light); font-family: var(--font-mono); }
.task-actions { display: flex; gap: 8px; flex-shrink: 0; }
</style>
<?= $this->endSection() ?>
