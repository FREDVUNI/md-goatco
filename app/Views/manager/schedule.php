<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Farm Management<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Vet Schedule</h3>
    <a href="<?= site_url('manager/schedule/create') ?>" class="btn btn-primary btn-sm">+ Add task</a>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search tasks…" value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
    </form>
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
  <?= $pager->links('default', 'dashboard') ?>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
