<?php
/**
 * Single testimonial card — shared by the homepage's grid (≤3 active
 * testimonials) and carousel (>3) so both stay visually identical.
 * Expects $t (a row from TestimonialModel).
 */
$rating = max(1, min(5, (int) ($t['rating'] ?? 5)));
?>
<div class="test-card">
  <div class="test-stars"><?= str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) ?></div>
  <p>"<?= esc($t['quote']) ?>"</p>
  <div class="test-who">
    <?php if (!empty($t['avatar_url'])): ?>
      <div class="test-avatar" style="background-image:url('<?= esc($t['avatar_url']) ?>')"></div>
    <?php else: ?>
      <div class="test-avatar test-avatar-fallback"><?= esc(strtoupper(substr($t['author_name'], 0, 1))) ?></div>
    <?php endif ?>
    <div><strong><?= esc($t['author_name']) ?></strong><span><?= esc($t['author_role']) ?></span></div>
  </div>
</div>
