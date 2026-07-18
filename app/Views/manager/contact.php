<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Farm Management<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->include('manager/_sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-head">
    <h3>📨 Contact Messages</h3>
    <span style="font-size:0.84rem;color:var(--slate-light)"><?= esc($contactNewCount ?? 0) ?> new</span>
  </div>
  <div class="table-toolbar">
    <form method="get" style="flex:1;display:flex">
      <input type="text" name="q" class="search-input" placeholder="Search by name, email, phone, or subject…" value="<?= esc($search ?? '') ?>">
    </form>
  </div>

  <?php if (empty($messages)): ?>
    <div class="empty-state">No contact messages yet.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>From</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Respond</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($messages as $m): ?>
      <tr>
        <td>
          <div style="font-weight:600;color:var(--ink)"><?= esc($m['name']) ?></div>
          <div style="font-size:0.76rem;color:var(--slate-light)"><?= esc($m['email']) ?></div>
          <div style="font-size:0.76rem;color:var(--slate-light)"><?= esc($m['phone']) ?></div>
        </td>
        <td><?= esc($m['subject']) ?></td>
        <td style="max-width:220px"><?= esc(substr($m['message'],0,80)) ?><?= strlen($m['message'])>80?'…':'' ?></td>
        <td><?= date('j M Y, g:i A', strtotime($m['created_at'])) ?></td>
        <td>
          <?php if ($m['status']==='responded'): ?>
            <span class="badge badge-active">Responded</span>
          <?php else: ?>
            <span class="badge badge-pending">New</span>
          <?php endif ?>
        </td>
        <td>
          <div style="display:flex;gap:6px;flex-wrap:wrap">
            <a href="mailto:<?= esc($m['email']) ?>?subject=<?= rawurlencode('Re: '.$m['subject']) ?>" class="btn btn-outline btn-sm" title="Reply by email">✉️ Email</a>
            <a href="https://wa.me/<?= esc(preg_replace('/\D/','',$m['phone'])) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm" title="Reply on WhatsApp">💬 WhatsApp</a>
            <?php if ($m['status'] !== 'responded'): ?>
            <?= form_open('manager/contact/'.$m['id'].'/respond',['style'=>'display:inline']) ?><?= csrf_field() ?>
            <button type="submit" class="btn btn-green btn-sm" data-confirm="Mark this message as responded?">✓ Mark responded</button>
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
