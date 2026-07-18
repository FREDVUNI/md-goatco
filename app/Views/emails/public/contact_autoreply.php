<?php helper(['email']); ?>
<?= emailHeading('Thanks for reaching out') ?>
<?= emailParagraph('Hi <strong>' . esc($contact['name'] ?? 'there') . '</strong>,') ?>
<?= emailParagraph('We\'ve received your message and someone from our team will get back to you within 1–2 working days.') ?>
<?= emailLabel('Subject', $contact['subject'] ?? '') ?>
<?= emailDivider() ?>
<?= emailParagraph('For anything urgent, you can also reach us directly on <strong>+256 700 000 000</strong> or reply to this email.') ?>
