<?php /* Expects: $icon (FA class, optional), $title, $message (optional), $ctaText + $ctaUrl (optional) */ ?>
<div class="empty-state-rich">
  <div class="empty-state-icon"><i class="<?= esc($icon ?? 'fas fa-inbox') ?>"></i></div>
  <h4><?= esc($title) ?></h4>
  <?php if (! empty($message)): ?>
  <p><?= esc($message) ?></p>
  <?php endif ?>
  <?php if (! empty($ctaUrl)): ?>
  <a href="<?= site_url($ctaUrl) ?>" class="btn btn-primary btn-sm"><?= esc($ctaText ?? 'Get started') ?></a>
  <?php endif ?>
</div>
