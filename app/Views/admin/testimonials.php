<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Administration<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<?= $this->include('admin/_sidebar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-head">
    <h3>Testimonials</h3>
    <a href="<?= site_url('admin/testimonials/create') ?>" class="btn btn-primary btn-sm">+ Add testimonial</a>
  </div>
  <p class="field-hint" style="padding:0 20px 16px">
    These feed the "From our members" section on the public homepage — only <strong>active</strong> ones show, in the order below.
    With more than three active, the homepage automatically switches from a grid to a carousel.
  </p>
  <?php if (empty($testimonials)): ?>
  <?= view('partials/empty_state', [
    'icon'    => 'fas fa-quote-right',
    'title'   => 'No testimonials yet',
    'message' => 'Add a quote from a member, vet, or staffer to show it on the homepage.',
    'ctaUrl'  => 'admin/testimonials/create',
    'ctaText' => 'Add the first testimonial',
  ]) ?>
  <?php else: ?>
  <table class="bulk-table">
    <thead><tr><th style="width:1%"></th><th>Quote</th><th>Author</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($testimonials as $i => $t): ?>
      <tr>
        <td>
          <div style="display:flex;flex-direction:column;gap:2px">
            <?= form_open('admin/testimonials/'.$t['id'].'/move-up',['style'=>'display:inline']) ?><?= csrf_field() ?><button type="submit" class="btn btn-ghost btn-sm" style="padding:2px 6px" <?= $i===0?'disabled':'' ?> aria-label="Move up"><i class="fas fa-caret-up"></i></button><?= form_close() ?>
            <?= form_open('admin/testimonials/'.$t['id'].'/move-down',['style'=>'display:inline']) ?><?= csrf_field() ?><button type="submit" class="btn btn-ghost btn-sm" style="padding:2px 6px" <?= $i===count($testimonials)-1?'disabled':'' ?> aria-label="Move down"><i class="fas fa-caret-down"></i></button><?= form_close() ?>
          </div>
        </td>
        <td style="max-width:340px">
          <div style="font-style:italic;color:var(--slate);font-size:0.84rem;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">"<?= esc($t['quote']) ?>"</div>
        </td>
        <td>
          <div class="avatar-cell">
            <?php if (!empty($t['avatar_url'])): ?>
              <div class="avatar" style="background-image:url('<?= esc($t['avatar_url']) ?>');background-size:cover;background-position:center"></div>
            <?php else: ?>
              <div class="avatar"><?= esc(strtoupper(substr($t['author_name'],0,1))) ?></div>
            <?php endif ?>
            <div><strong style="display:block"><?= esc($t['author_name']) ?></strong><span style="font-size:0.72rem;color:var(--slate-light)"><?= esc($t['author_role']) ?></span></div>
          </div>
        </td>
        <td><span style="color:var(--blue)"><?= str_repeat('★', (int) $t['rating']) . str_repeat('☆', 5 - (int) $t['rating']) ?></span></td>
        <td>
          <?= form_open('admin/testimonials/'.$t['id'].'/toggle',['style'=>'display:inline']) ?><?= csrf_field() ?>
            <button type="submit" class="badge <?= $t['is_active'] ? 'badge-active' : 'badge-pending' ?>" style="border:none;cursor:pointer">
              <?= $t['is_active'] ? 'Active' : 'Hidden' ?>
            </button>
          <?= form_close() ?>
        </td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="<?= site_url('admin/testimonials/'.$t['id'].'/edit') ?>" class="btn btn-ghost btn-sm">Edit</a>
            <?= form_open('admin/testimonials/'.$t['id'].'/delete',['style'=>'display:inline']) ?><?= csrf_field() ?><button class="btn btn-ghost btn-sm" style="color:var(--red);border-color:var(--red)" data-confirm="Remove this testimonial? This can't be undone.">Delete</button><?= form_close() ?>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>
</div>
<?= $this->endSection() ?>
