<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($pageTitle ?? 'Log In') ?> — MD Goatco Farm</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
<?= $this->renderSection('head') ?>
</head>
<body class="auth-body">
<?= $this->renderSection('content') ?>
<script src="<?= base_url('js/auth.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
