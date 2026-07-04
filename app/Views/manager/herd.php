<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('portalName') ?>Portal<?= $this->endSection() ?>
<?= $this->section('sidebar') ?>
<nav class="sb-nav"><a href="<?= site_url('dashboard') ?>" class="sb-item">← Dashboard</a></nav>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-head"><h3>Herd</h3></div><div class="empty-state">This section is coming soon.<br><a href="<?= site_url('dashboard') ?>">← Return to dashboard</a></div></div>
<?= $this->endSection() ?>
