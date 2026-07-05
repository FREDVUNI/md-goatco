<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'MD Goatco Farm Limited') ?></title>
    <meta name="description" content="<?= esc($metaDesc ?? 'MD Goatco Farm Limited — Ethics · Service · Genetics') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,500&family=Poppins:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?= base_url('img/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('css/public.css') ?>">
    <?= $this->renderSection('head') ?>
</head>

<body>

    <!-- ===== FLASH MESSAGES ===== -->
    <?php if (session()->has('success')): ?>
        <div class="flash flash-success"><?= esc(session('success')) ?> <button class="flash-close" onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>
    <?php if (session()->has('error')): ?>
        <div class="flash flash-error"><?= esc(session('error')) ?> <button class="flash-close" onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>
    <?php if (session()->has('warning')): ?>
        <div class="flash flash-warning"><?= esc(session('warning')) ?> <button class="flash-close" onclick="this.parentElement.remove()">×</button></div>
    <?php endif ?>

    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/public.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>