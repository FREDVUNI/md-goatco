<?php
/** @var \CodeIgniter\Pager\PagerRenderer $pager */
if ($pager->getPageCount() <= 1) return;
?>
<nav class="pager" aria-label="Pagination">
  <?php if ($pager->hasPreviousPage()): ?>
    <a class="pager-link" href="<?= $pager->getPrevious() ?>">&larr; Prev</a>
  <?php else: ?>
    <span class="pager-link pager-disabled">&larr; Prev</span>
  <?php endif ?>

  <?php foreach ($pager->links() as $link): ?>
    <a class="pager-link<?= $link['active'] ? ' pager-current' : '' ?>" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
  <?php endforeach ?>

  <?php if ($pager->hasNextPage()): ?>
    <a class="pager-link" href="<?= $pager->getNext() ?>">Next &rarr;</a>
  <?php else: ?>
    <span class="pager-link pager-disabled">Next &rarr;</span>
  <?php endif ?>

  <span class="pager-total"><?= esc($pager->getTotal()) ?> total</span>
</nav>
