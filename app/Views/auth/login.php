<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-split">

    <!-- LEFT: brand panel -->
    <div class="auth-left auth-left-blue">
        <div class="auth-left-inner">
            <a href="<?= site_url() ?>" class="auth-logo">
                <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco">
                <div>
                    <strong>MD Goatco Farm Limited</strong>
                    <small>Member &amp; Staff Portal</small>
                </div>
            </a>

            <div class="auth-hero">
                <div class="auth-role-badge">MD Goatco Farm Limited</div>
                <h1>One login.<br><em>Your dashboard.</em><br>Our farm.</h1>
                <p>Members track their goats and statements. Staff manage the herd, health records, and applications. Same door, different view once you're in.</p>
            </div>

            <!-- Sample goat tag card -->
            <div class="tag-card">
                <div class="tag-card-head">
                    <span class="tag-id">TAG · PGF-1187</span>
                </div>
                <div class="tag-card-name">Kito</div>
                <div class="tag-row"><span>Breed</span><span>Boer Cross</span></div>
                <div class="tag-row"><span>Age</span><span>8 months</span></div>
                <div class="tag-row"><span>Weight</span><span>24.6 kg</span></div>
                <div class="tag-row"><span>Last checkup</span><span>3 days ago</span></div>
                <div class="tag-status">● Healthy · Cleared by vet</div>
            </div>
        </div>
    </div>

    <!-- RIGHT: login form -->
    <div class="auth-right">
        <div class="auth-card">

            <!-- Tabs: Login | Check status -->
            <div class="auth-tabs">
                <button class="auth-tab <?= ($activeTab ?? 'login') === 'login' ? 'active' : '' ?>"
                    onclick="switchTab('login', this)">Log in</button>
                <button class="auth-tab <?= ($activeTab ?? 'login') === 'status' ? 'active' : '' ?>"
                    onclick="switchTab('status', this)">Check status</button>
            </div>

            <!-- LOGIN PANE -->
            <div class="auth-pane" id="pane-login" style="display:<?= ($activeTab ?? 'login') === 'login' ? 'block' : 'none' ?>">
                <h2>Welcome back</h2>
                <p class="auth-sub">Log in to your dashboard</p>

                <?php if (session()->has('warning')): ?>
                    <div class="status-banner status-pending">
                        <span>⏳</span>
                        <div><?= esc(session('warning')) ?></div>
                    </div>
                <?php endif ?>

                <?php if (!empty($errors ?? [])): ?>
                    <div class="form-errors">
                        <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
                    </div>
                <?php endif ?>

                <?= form_open('auth/login', ['class' => 'auth-form']) ?>
                <?= csrf_field() ?>
                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                        value="<?= esc(old('email')) ?>"
                        placeholder="you@example.com" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                        placeholder="••••••••" required>
                </div>
                <div class="field-aux">
                    <a href="<?= site_url('auth/forgot-password') ?>">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Log in to my dashboard</button>
                <?= form_close() ?>

                <div class="auth-divider">new to Goat Banking?</div>
                <a href="<?= site_url('auth/register') ?>" class="btn btn-ghost btn-full">Apply for Goat Banking →</a>
                <p class="auth-foot">Applied already?
                    <a href="#" onclick="switchTab('status'); return false;">Check your application status</a>
                </p>
            </div>

            <!-- STATUS CHECK PANE -->
            <div class="auth-pane" id="pane-status" style="display:<?= ($activeTab ?? 'login') === 'status' ? 'block' : 'none' ?>">
                <h2>Check your status</h2>
                <p class="auth-sub">Enter the email you used when applying.</p>

                <?= form_open('auth/status', ['class' => 'auth-form', 'method' => 'post']) ?>
                <?= csrf_field() ?>
                <div class="field">
                    <label for="check_email">Email address</label>
                    <input type="email" id="check_email" name="email"
                        value="<?= esc(old('email', $email ?? '')) ?>"
                        placeholder="you@example.com" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Check status</button>
                <?= form_close() ?>

                <?php if (isset($status)): ?>
                    <div class="status-result">
                        <?php if ($status === 'pending'): ?>
                            <div class="status-banner status-pending">
                                <span>⏳</span>
                                <div><strong>Under review.</strong> Your application was received on
                                    <?= date('j M Y', strtotime($application['created_at'])) ?> and
                                    is being reviewed. You'll get an email when a decision is made.</div>
                            </div>
                        <?php elseif ($status === 'approved'): ?>
                            <div class="status-banner status-approved">
                                <span>✓</span>
                                <div><strong>Approved — your account is active!</strong>
                                    <a href="#" onclick="switchTab('login'); return false;">Log in now</a> to view your dashboard.
                                </div>
                            </div>
                        <?php elseif ($status === 'rejected'): ?>
                            <div class="status-banner status-rejected">
                                <span>✗</span>
                                <div><strong>Application not approved.</strong> Please contact us at
                                    <a href="mailto:hello@mdgoatco.farm">hello@mdgoatco.farm</a> for more information.
                                </div>
                            </div>
                        <?php elseif ($status === 'info_requested'): ?>
                            <div class="status-banner status-pending">
                                <span>📋</span>
                                <div><strong>Additional information requested.</strong>
                                    <?= esc($application['info_request_note'] ?? '') ?></div>
                            </div>
                        <?php elseif ($status === 'not_found'): ?>
                            <div class="status-banner status-rejected">
                                <span>?</span>
                                <div>No application found for that email address.
                                    <a href="<?= site_url('auth/register') ?>">Apply now →</a>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <p class="auth-foot">Already have an account?
                    <a href="#" onclick="switchTab('login'); return false;">Log in</a>
                </p>
            </div>

        </div><!-- /auth-card -->
    </div><!-- /auth-right -->

</div><!-- /auth-split -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function switchTab(tab, el) {
        document.querySelectorAll('.auth-pane').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('pane-' + tab).style.display = 'block';
        if (el) el.classList.add('active');
        else document.querySelectorAll('.auth-tab')[tab === 'login' ? 0 : 1].classList.add('active');
    }
</script>
<?= $this->endSection() ?>