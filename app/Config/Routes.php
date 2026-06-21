<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * MD Goatco Farm Limited — Routes
 *
 * @var RouteCollection $routes
 */

// ─── PUBLIC SITE ─────────────────────────────────────────────────────────────
$routes->get('/',                'PublicController::index');
$routes->get('about',            'PublicController::about');
$routes->get('services',         'PublicController::services');
$routes->get('goat-banking',     'PublicController::goatBanking');
$routes->get('contact',          'PublicController::contact');
$routes->post('contact',         'PublicController::sendContact');
$routes->get('privacy-policy',   'PublicController::privacy');
$routes->get('terms',            'PublicController::terms');

// ─── AUTH ─────────────────────────────────────────────────────────────────────
$routes->group('auth', ['filter' => 'guest'], function ($routes) {

    // Single login for every role. Which dashboard you land on after
    // authenticating is decided by AuthController::doLogin() based on
    // the user's `role` column — there's nothing role-specific in the
    // routing or the view anymore.
    $routes->get('login',              'AuthController::login');
    $routes->post('login',             'AuthController::doLogin');

    // Member self-service (staff accounts are created by super admin,
    // so registration/status checks only ever apply to members)
    $routes->get('register',           'AuthController::register');
    $routes->post('register',          'AuthController::doRegister');
    $routes->get('status',             'AuthController::checkStatus');
    $routes->post('status',            'AuthController::doCheckStatus');
    $routes->get('forgot-password',    'AuthController::forgotPassword');
    $routes->post('forgot-password',   'AuthController::doForgotPassword');
    $routes->get('reset-password/(:alphanum)', 'AuthController::resetPassword/$1');
    $routes->post('reset-password',    'AuthController::doResetPassword');

    // Old staff-portal URLs — kept as redirects so any bookmarks/links
    // people already have still land them on the unified login page.
    $routes->get('admin',              'AuthController::redirectToLogin');
    $routes->get('manager',            'AuthController::redirectToLogin');
    $routes->get('vet',                'AuthController::redirectToLogin');
});

// Shared logout — outside the guest filter group (must work while logged in)
$routes->get('auth/logout', 'AuthController::logout');

// ─── SUPER ADMIN ─────────────────────────────────────────────────────────────
$routes->group('admin', [
    'filter'    => 'role:super_admin',
    'namespace' => 'App\Modules\Admin\Controllers',
], function ($routes) {
    $routes->get('/',                                   'DashboardController::index');
    $routes->get('dashboard',                           'DashboardController::index');

    // Applications
    $routes->get('applications',                        'ApplicationController::index');
    $routes->get('applications/(:num)',                 'ApplicationController::show/$1');
    $routes->post('applications/(:num)/approve',        'ApplicationController::approve/$1');
    $routes->post('applications/(:num)/reject',         'ApplicationController::reject/$1');
    $routes->post('applications/(:num)/request-info',   'ApplicationController::requestInfo/$1');

    // Private document viewing (NID scans, headshots) — admin only
    $routes->get('documents/(:num)/(:segment)',         'DocumentController::serve/$1/$2');

    // Members
    $routes->get('members',                             'MemberController::index');
    $routes->get('members/(:num)',                      'MemberController::show/$1');
    $routes->post('members/(:num)/deactivate',          'MemberController::deactivate/$1');
    $routes->post('members/(:num)/reactivate',          'MemberController::reactivate/$1');

    // Staff management
    $routes->get('staff',                               'StaffController::index');
    $routes->get('staff/create',                        'StaffController::create');
    $routes->post('staff/create',                       'StaffController::store');
    $routes->get('staff/(:num)/edit',                   'StaffController::edit/$1');
    $routes->post('staff/(:num)/edit',                  'StaffController::update/$1');
    $routes->post('staff/(:num)/deactivate',            'StaffController::deactivate/$1');
    $routes->post('staff/(:num)/reset-password',        'StaffController::resetPassword/$1');

    // Herd
    $routes->get('herd',                                'HerdController::index');
    $routes->get('herd/(:num)',                         'HerdController::show/$1');
    $routes->get('herd/create',                         'HerdController::create');
    $routes->post('herd/create',                        'HerdController::store');
    $routes->get('herd/(:num)/edit',                    'HerdController::edit/$1');
    $routes->post('herd/(:num)/edit',                   'HerdController::update/$1');

    // Settings
    $routes->get('settings',                            'SettingsController::index');
    $routes->post('settings',                           'SettingsController::update');

    // Payments (Pesapal top-ups across all members)
    $routes->get('payments',                            'PaymentController::index');
});

// ─── FARM MANAGER ─────────────────────────────────────────────────────────────
$routes->group('manager', [
    'filter'    => 'role:manager,super_admin',
    'namespace' => 'App\Modules\Manager\Controllers',
], function ($routes) {
    $routes->get('/',               'DashboardController::index');
    $routes->get('dashboard',       'DashboardController::index');

    // Herd
    $routes->get('herd',            'HerdController::index');
    $routes->get('herd/(:num)',     'HerdController::show/$1');
    $routes->get('herd/create',     'HerdController::create');
    $routes->post('herd/create',    'HerdController::store');
    $routes->get('herd/(:num)/edit', 'HerdController::edit/$1');
    $routes->post('herd/(:num)/edit', 'HerdController::update/$1');

    // Health
    $routes->get('health',          'HealthController::index');
    $routes->get('health/(:num)',   'HealthController::show/$1');
    $routes->post('health/(:num)/resolve', 'HealthController::resolve/$1');

    // Members (read-only for manager)
    $routes->get('members',         'MemberController::index');
    $routes->get('members/(:num)',  'MemberController::show/$1');

    // Vet schedule
    $routes->get('schedule',        'ScheduleController::index');
    $routes->get('schedule/create', 'ScheduleController::create');
    $routes->post('schedule/create', 'ScheduleController::store');
    $routes->post('schedule/(:num)/complete', 'ScheduleController::complete/$1');
    $routes->post('schedule/(:num)/delete',   'ScheduleController::delete/$1');

    // Reports
    $routes->get('reports',         'ReportController::index');
    $routes->get('reports/herd',    'ReportController::herd');
    $routes->get('reports/health',  'ReportController::health');
    $routes->get('reports/members', 'ReportController::members');
    $routes->get('reports/export/(:alpha)', 'ReportController::export/$1');
});

// ─── VETERINARIAN ─────────────────────────────────────────────────────────────
$routes->group('vet', [
    'filter'    => 'role:vet,manager,super_admin',
    'namespace' => 'App\Modules\Vet\Controllers',
], function ($routes) {
    $routes->get('/',                       'DashboardController::index');
    $routes->get('dashboard',               'DashboardController::index');

    // Today's tasks
    $routes->get('tasks',                   'TaskController::index');
    $routes->post('tasks/(:num)/complete',  'TaskController::complete/$1');

    // Visit logging
    $routes->get('visits/log',              'VisitController::create');
    $routes->post('visits/log',             'VisitController::store');
    $routes->get('visits/history',          'VisitController::history');
    $routes->get('visits/(:num)',           'VisitController::show/$1');

    // Animal records
    $routes->get('animals',                 'AnimalController::index');
    $routes->get('animals/lookup',          'AnimalController::lookup');
    $routes->get('animals/(:num)',          'AnimalController::show/$1');

    // Health flags
    $routes->get('flags',                   'FlagController::index');
    $routes->post('flags/(:num)/resolve',   'FlagController::resolve/$1');
});

// ─── MEMBER (GOAT BANKING) ────────────────────────────────────────────────────
$routes->group('member', [
    'filter'    => 'role:member',
    'namespace' => 'App\Modules\Member\Controllers',
], function ($routes) {
    $routes->get('/',                       'DashboardController::index');
    $routes->get('dashboard',               'DashboardController::index');

    // Portfolio
    $routes->get('goats',                   'PortfolioController::index');
    $routes->get('goats/(:num)',            'PortfolioController::show/$1');

    // Statements
    $routes->get('statements',              'StatementController::index');
    $routes->get('statements/download',     'StatementController::download');

    // Wallet / Pesapal top-ups
    $routes->get('wallet/topup',            'WalletController::topup');
    $routes->post('wallet/topup',           'WalletController::initiateTopup');
    $routes->get('wallet/topup/(:segment)', 'WalletController::topupStatus/$1');

    // Account
    $routes->get('account',                 'AccountController::index');
    $routes->post('account/update',         'AccountController::update');
    $routes->post('account/password',       'AccountController::changePassword');

    // Support
    $routes->get('support',                 'SupportController::index');
    $routes->post('support',                'SupportController::send');
});

// ─── PESAPAL PAYMENT CALLBACKS (public — Pesapal calls these directly) ───────
// These must NOT sit behind the session/role filters or CSRF, since Pesapal's
// servers (the IPN) and the customer's browser (the redirect callback) hit
// them directly, with no MD Goatco session of their own.
$routes->group('payments', function ($routes) {
    $routes->get('callback',  'PaymentController::callback');   // browser redirect back from Pesapal
    $routes->get('ipn',       'PaymentController::ipn');        // Pesapal server-to-server notification (GET)
    $routes->post('ipn',      'PaymentController::ipn');        // Pesapal server-to-server notification (POST)
});

// ─── REST API v1 ─────────────────────────────────────────────────────────────
$routes->group('api/v1', ['filter' => 'cors', 'namespace' => 'App\Modules\Api\Controllers\V1'], function ($routes) {

    // Auth (no JWT filter here — these issue the token)
    $routes->post('auth/login',             'AuthApiController::login');
    $routes->post('auth/refresh',           'AuthApiController::refresh');
    $routes->post('auth/logout',            'AuthApiController::logout');
    $routes->post('auth/register',          'AuthApiController::register');
    $routes->post('auth/status',            'AuthApiController::checkStatus');

    // Protected — require valid JWT
    $routes->group('', ['filter' => 'jwt'], function ($routes) {

        // Member endpoints
        $routes->get('member/dashboard',        'MemberApiController::dashboard');
        $routes->get('member/goats',            'MemberApiController::goats');
        $routes->get('member/goats/(:num)',     'MemberApiController::goat/$1');
        $routes->get('member/statements',       'MemberApiController::statements');
        $routes->get('member/notifications',    'MemberApiController::notifications');
        $routes->post('member/support',         'MemberApiController::support');

        // Goat endpoints (shared — role filtered inside controller)
        $routes->get('goats',                   'GoatApiController::index');
        $routes->get('goats/(:num)',            'GoatApiController::show/$1');
        $routes->get('goats/(:num)/health',     'GoatApiController::healthHistory/$1');
        $routes->get('goats/(:num)/weight',     'GoatApiController::weightHistory/$1');
        $routes->post('goats/(:num)/weight',    'GoatApiController::logWeight/$1');

        // Vet endpoints
        $routes->get('vet/tasks',               'VetApiController::tasks');
        $routes->post('vet/tasks/(:num)/complete', 'VetApiController::completeTask/$1');
        $routes->get('vet/visits',              'VetApiController::visits');
        $routes->post('vet/visits',             'VetApiController::logVisit');
        $routes->get('vet/flags',               'VetApiController::flags');
        $routes->post('vet/flags/(:num)/resolve', 'VetApiController::resolveFlag/$1');

        // Manager endpoints
        $routes->get('manager/herd',            'ManagerApiController::herd');
        $routes->get('manager/herd/(:num)',     'ManagerApiController::goat/$1');
        $routes->get('manager/health-flags',    'ManagerApiController::healthFlags');
        $routes->get('manager/schedule',        'ManagerApiController::schedule');
        $routes->get('manager/reports/(:alpha)', 'ManagerApiController::report/$1');

        // Admin endpoints
        $routes->get('admin/applications',      'AdminApiController::applications');
        $routes->post('admin/applications/(:num)/approve', 'AdminApiController::approve/$1');
        $routes->post('admin/applications/(:num)/reject',  'AdminApiController::reject/$1');
        $routes->get('admin/members',           'AdminApiController::members');
        $routes->get('admin/staff',             'AdminApiController::staff');
        $routes->post('admin/staff',            'AdminApiController::createStaff');

        // Payments (mobile wallet top-up via Pesapal)
        $routes->post('payments/topup',         'PaymentApiController::initiateTopup');
        $routes->get('payments/(:segment)',    'PaymentApiController::status/$1');

        // Notifications (all roles)
        $routes->get('notifications',           'NotificationApiController::index');
        $routes->post('notifications/(:num)/read', 'NotificationApiController::markRead/$1');
        $routes->post('notifications/read-all', 'NotificationApiController::markAllRead');
    });
});

// ─── 404 ──────────────────────────────────────────────────────────────────────
$routes->set404Override('PublicController::notFound');
