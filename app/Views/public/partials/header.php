<?php
// Supports: manual session('isLoggedIn'), Myth/Auth, or simple user_id presence
$loggedIn = false;

if (function_exists('auth') && method_exists(auth(), 'loggedIn')) {
    // Myth/Auth or similar
    $loggedIn = auth()->loggedIn();
} elseif (session()->has('isLoggedIn') && session()->get('isLoggedIn') === true) {
    $loggedIn = true;
} elseif (session()->has('user_id')) {
    // fallback if you only store user_id
    $loggedIn = true;
}
?>
<header>
    <nav class="nav wrap">
        <a href="<?= site_url('/') ?>" class="logo">
            <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco Farm Limited">
            <div class="logo-text">
                <strong>MD Goatco Farm Limited</strong>
                <small>Ethics · Service · Genetics</small>
            </div>
        </a>
        <ul class="nav-links">
            <li><a href="<?= site_url('/') ?>">Home</a></li>
            <li><a href="<?= site_url('/') ?>#about">About</a></li>
            <li><a href="<?= site_url('/') ?>#services">Services</a></li>
            <li><a href="<?= site_url('/') ?>#goat-banking">Goat Banking</a></li>
            <li><a href="<?= site_url('/') ?>#contact">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if ($loggedIn): ?>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline btn-sm">Dashboard</a>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-primary btn-sm"><i class="fas fa-sign-out-alt"></i> Log out</a>
            <?php else: ?>
                <a href="<?= site_url('auth/login') ?>" class="btn btn-outline btn-sm">Log in</a>
                <a href="<?= site_url('auth/register') ?>" class="btn btn-primary btn-sm">Join Goat Banking</a>
            <?php endif; ?>
        </div>
        <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <div class="nav-mobile-menu wrap">
        <a href="<?= site_url('/') ?>">Home</a>
        <a href="<?= site_url('/') ?>#about">About</a>
        <a href="<?= site_url('/') ?>#services">Services</a>
        <a href="<?= site_url('/') ?>#goat-banking">Goat Banking</a>
        <a href="<?= site_url('/') ?>#contact">Contact</a>
        <div class="nav-mobile-actions">
            <?php if ($loggedIn): ?>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline">Dashboard</a>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-primary btn-logout"><i class="fas fa-sign-out-alt"></i> Log out</a>
            <?php else: ?>
                <a href="<?= site_url('auth/login') ?>" class="btn btn-outline">Log in</a>
                <a href="<?= site_url('auth/register') ?>" class="btn btn-primary">Join Goat Banking</a>
            <?php endif; ?>
        </div>
    </div>
</header>