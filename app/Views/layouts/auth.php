<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Login') ?> — MD Goatco Farm</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,500&family=Poppins:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="<?= base_url('img/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/loader.css') ?>">
</head>

<body>

    <?php if (session()->has('error')): ?>
        <div class="auth-flash auth-flash-error"><?= esc(session('error')) ?></div>
    <?php endif ?>
    <?php if (session()->has('warning')): ?>
        <div class="auth-flash auth-flash-warning"><?= esc(session('warning')) ?></div>
    <?php endif ?>
    <?php if (session()->has('success')): ?>
        <div class="auth-flash auth-flash-success"><?= esc(session('success')) ?></div>
    <?php endif ?>

    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/loader.js') ?>"></script>
    <script src="<?= base_url('js/auth.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>