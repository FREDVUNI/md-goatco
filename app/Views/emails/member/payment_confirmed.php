<?php helper(['email','goatco']); ?>
<?= emailHeading(ucwords('payment confirmed')) ?>
<?= emailParagraph('Hi <strong>' . esc($user['first_name'] ?? $admin['first_name'] ?? $manager['first_name'] ?? 'there') . '</strong>,') ?>
<?= emailParagraph('This is a notification from MD Goatco Farm Limited.') ?>
<?= emailButton('Go to dashboard', site_url('dashboard')) ?>
