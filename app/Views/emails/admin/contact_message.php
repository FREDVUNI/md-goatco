<?php helper(['email']); ?>
<?= emailHeading('New contact form message') ?>
<?= emailParagraph('Someone submitted the contact form on the public website. Reply directly to this email to respond to them.') ?>
<?= emailLabel('Name', $contact['name'] ?? '') ?>
<?= emailLabel('Email', $contact['email'] ?? '') ?>
<?= emailLabel('Phone', $contact['phone'] ?? '') ?>
<?= emailLabel('Subject', $contact['subject'] ?? '') ?>
<?= emailDivider() ?>
<?= emailParagraph(nl2br(esc($contact['message'] ?? ''))) ?>
