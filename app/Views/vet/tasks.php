<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Veterinary Portal<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<div class="sb-role sb-role-green">Veterinarian</div>
<nav class="sb-nav">
  <div class="sb-section">My Work</div>
  <a href="<?= site_url('vet/dashboard') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>My Dashboard
  </a>
  <a href="<?= site_url('vet/tasks') ?>" class="sb-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>Today's Tasks
  </a>
  <a href="<?= site_url('vet/visits/log') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>Log a Visit
  </a>
  <div class="sb-section">Animals</div>
  <a href="<?= site_url('vet/animals') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>Animal Records
  </a>
  <a href="<?= site_url('vet/flags') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>My Flags
  </a>
  <div class="sb-section">History</div>
  <a href="<?= site_url('vet/visits/history') ?>" class="sb-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Visit History
  </a>
</nav>
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
