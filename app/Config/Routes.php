<?php
declare(strict_types=1);
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */

// PUBLIC
$routes->get('/',              'PublicController::index');
$routes->get('about',          'PublicController::about');
$routes->get('services',       'PublicController::services');
$routes->get('goat-banking',   'PublicController::goatBanking');
$routes->get('contact',        'PublicController::contact');
$routes->post('contact',       'PublicController::sendContact');
$routes->get('privacy-policy', 'PublicController::privacy');
$routes->get('terms',          'PublicController::terms');

// AUTH — guest filter means logged-in users are redirected away
$routes->group('auth', ['filter' => 'guest'], function ($routes) {
    $routes->get('login',                      'AuthController::login');
    $routes->post('login',                     'AuthController::doLogin');
    $routes->get('register',                   'AuthController::register');
    $routes->post('register',                  'AuthController::doRegister');
    $routes->get('status',                     'AuthController::checkStatus');
    $routes->post('status',                    'AuthController::doCheckStatus');
    $routes->get('forgot-password',            'AuthController::forgotPassword');
    $routes->post('forgot-password',           'AuthController::doForgotPassword');
    $routes->get('reset-password/(:alphanum)', 'AuthController::resetPassword/$1');
    $routes->post('reset-password',            'AuthController::doResetPassword');
    // Old separate portal URLs → redirect to single login
    $routes->get('admin',   'AuthController::redirectToLogin');
    $routes->get('manager', 'AuthController::redirectToLogin');
    $routes->get('vet',     'AuthController::redirectToLogin');
});
$routes->get('auth/logout', 'AuthController::logout');

// UNIFIED DASHBOARD — single URL, role decides what you see
$routes->get('dashboard', 'DashboardController::index', [
    'filter' => 'role:super_admin,manager,vet,member',
]);

// ADMIN
$routes->group('admin', ['filter' => 'role:super_admin', 'namespace' => 'App\Modules\Admin\Controllers'], function ($routes) {
    $routes->get('/',                                  'DashboardController::index');
    $routes->get('dashboard',                          'DashboardController::index');
    $routes->get('applications',                       'ApplicationController::index');
    $routes->get('applications/export',                'ApplicationController::export');
    $routes->get('applications/(:num)',                'ApplicationController::show/$1');
    $routes->post('applications/(:num)/approve',       'ApplicationController::approve/$1');
    $routes->post('applications/(:num)/reject',        'ApplicationController::reject/$1');
    $routes->post('applications/(:num)/request-info',  'ApplicationController::requestInfo/$1');
    $routes->get('documents/(:num)/(:segment)',        'DocumentController::serve/$1/$2');
    $routes->get('members',                            'MemberController::index');
    $routes->get('members/export',                     'MemberController::export');
    $routes->get('members/(:num)',                     'MemberController::show/$1');
    $routes->post('members/(:num)/deactivate',         'MemberController::deactivate/$1');
    $routes->post('members/(:num)/reactivate',         'MemberController::reactivate/$1');
    $routes->get('staff',                              'StaffController::index');
    $routes->get('staff/export',                       'StaffController::export');
    $routes->get('staff/create',                       'StaffController::create');
    $routes->post('staff/create',                      'StaffController::store');
    $routes->get('staff/(:num)/edit',                  'StaffController::edit/$1');
    $routes->post('staff/(:num)/edit',                 'StaffController::update/$1');
    $routes->post('staff/(:num)/deactivate',           'StaffController::deactivate/$1');
    $routes->post('staff/(:num)/reset-password',       'StaffController::resetPassword/$1');
    $routes->get('herd',                               'HerdController::index');
    $routes->get('herd/export',                        'HerdController::export');
    $routes->get('herd/(:num)',                        'HerdController::show/$1');
    $routes->get('herd/create',                        'HerdController::create');
    $routes->post('herd/create',                       'HerdController::store');
    $routes->get('herd/(:num)/edit',                   'HerdController::edit/$1');
    $routes->post('herd/(:num)/edit',                  'HerdController::update/$1');
    $routes->get('payments',                           'PaymentController::index');
    $routes->get('payments/export',                    'PaymentController::export');
    $routes->get('settings',                           'SettingsController::index');
    $routes->post('settings',                          'SettingsController::update');
});

// MANAGER
$routes->group('manager', ['filter' => 'role:manager,super_admin', 'namespace' => 'App\Modules\Manager\Controllers'], function ($routes) {
    $routes->get('/',                          'DashboardController::index');
    $routes->get('dashboard',                  'DashboardController::index');
    $routes->get('herd',                       'HerdController::index');
    $routes->get('herd/export',                'HerdController::export');
    $routes->get('herd/(:num)',                'HerdController::show/$1');
    $routes->get('herd/create',                'HerdController::create');
    $routes->post('herd/create',               'HerdController::store');
    $routes->get('herd/(:num)/edit',           'HerdController::edit/$1');
    $routes->post('herd/(:num)/edit',          'HerdController::update/$1');
    $routes->get('health',                     'HealthController::index');
    $routes->get('health/export',              'HealthController::export');
    $routes->get('health/(:num)',              'HealthController::show/$1');
    $routes->post('health/(:num)/resolve',     'HealthController::resolve/$1');
    $routes->get('members',                    'MemberController::index');
    $routes->get('members/(:num)',             'MemberController::show/$1');
    $routes->get('schedule',                   'ScheduleController::index');
    $routes->get('schedule/create',            'ScheduleController::create');
    $routes->post('schedule/create',           'ScheduleController::store');
    $routes->post('schedule/(:num)/complete',  'ScheduleController::complete/$1');
    $routes->post('schedule/(:num)/delete',    'ScheduleController::delete/$1');
    $routes->get('reports',                    'ReportController::index');
    $routes->get('reports/herd',               'ReportController::herd');
    $routes->get('reports/health',             'ReportController::health');
    $routes->get('reports/members',            'ReportController::members');
    $routes->get('reports/export/(:alpha)',    'ReportController::export/$1');
    $routes->get('contact',                    'ContactController::index');
    $routes->post('contact/(:num)/respond',    'ContactController::respond/$1');
});

// VET
$routes->group('vet', ['filter' => 'role:vet,manager,super_admin', 'namespace' => 'App\Modules\Vet\Controllers'], function ($routes) {
    $routes->get('/',                          'DashboardController::index');
    $routes->get('dashboard',                  'DashboardController::index');
    $routes->get('tasks',                      'TaskController::index');
    $routes->post('tasks/(:num)/complete',     'TaskController::complete/$1');
    $routes->get('visits/log',                 'VisitController::create');
    $routes->post('visits/log',                'VisitController::store');
    $routes->get('visits/history',             'VisitController::history');
    $routes->get('visits/history/export',      'VisitController::export');
    $routes->get('visits/(:num)',              'VisitController::show/$1');
    $routes->get('animals',                    'AnimalController::index');
    $routes->get('animals/lookup',             'AnimalController::lookup');
    $routes->get('animals/(:num)',             'AnimalController::show/$1');
    $routes->get('flags',                      'FlagController::index');
    $routes->post('flags/(:num)/resolve',      'FlagController::resolve/$1');
});

// MEMBER
$routes->group('member', ['filter' => 'role:member', 'namespace' => 'App\Modules\Member\Controllers'], function ($routes) {
    $routes->get('/',                          'DashboardController::index');
    $routes->get('dashboard',                  'DashboardController::index');
    $routes->get('goats',                      'PortfolioController::index');
    $routes->get('goats/(:num)',               'PortfolioController::show/$1');
    $routes->get('statements',                 'StatementController::index');
    $routes->get('statements/download',        'StatementController::download');
    $routes->get('wallet/topup',               'WalletController::topup');
    $routes->post('wallet/topup',              'WalletController::initiateTopup');
    $routes->get('wallet/topup/(:segment)',    'WalletController::topupStatus/$1');
    $routes->get('account',                    'AccountController::index');
    $routes->post('account/update',            'AccountController::update');
    $routes->post('account/password',          'AccountController::changePassword');
    $routes->get('support',                    'SupportController::index');
    $routes->post('support',                   'SupportController::send');
});

// PESAPAL CALLBACKS — no auth, PesaPal calls these directly
$routes->group('payments', function ($routes) {
    $routes->get('callback',  'PaymentController::callback');
    $routes->get('ipn',       'PaymentController::ipn');
    $routes->post('ipn',      'PaymentController::ipn');
});

// REST API v1
$routes->group('api/v1', ['filter' => 'cors', 'namespace' => 'App\Modules\Api\Controllers\V1'], function ($routes) {
    $routes->post('auth/login',    'AuthApiController::login');
    $routes->post('auth/refresh',  'AuthApiController::refresh');
    $routes->post('auth/logout',   'AuthApiController::logout');
    $routes->post('auth/register', 'AuthApiController::register');
    $routes->post('auth/status',   'AuthApiController::checkStatus');
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        $routes->get('member/dashboard',             'MemberApiController::dashboard');
        $routes->get('member/goats',                 'MemberApiController::goats');
        $routes->get('member/goats/(:num)',          'MemberApiController::goat/$1');
        $routes->get('member/statements',            'MemberApiController::statements');
        $routes->get('member/notifications',         'MemberApiController::notifications');
        $routes->post('member/support',              'MemberApiController::support');
        $routes->get('goats',                        'GoatApiController::index');
        $routes->get('goats/(:num)',                 'GoatApiController::show/$1');
        $routes->get('goats/(:num)/health',          'GoatApiController::healthHistory/$1');
        $routes->get('goats/(:num)/weight',          'GoatApiController::weightHistory/$1');
        $routes->post('goats/(:num)/weight',         'GoatApiController::logWeight/$1');
        $routes->get('vet/tasks',                    'VetApiController::tasks');
        $routes->post('vet/tasks/(:num)/complete',   'VetApiController::completeTask/$1');
        $routes->get('vet/visits',                   'VetApiController::visits');
        $routes->post('vet/visits',                  'VetApiController::logVisit');
        $routes->get('vet/flags',                    'VetApiController::flags');
        $routes->post('vet/flags/(:num)/resolve',    'VetApiController::resolveFlag/$1');
        $routes->get('manager/herd',                 'ManagerApiController::herd');
        $routes->get('manager/herd/(:num)',          'ManagerApiController::goat/$1');
        $routes->get('manager/health-flags',         'ManagerApiController::healthFlags');
        $routes->get('manager/schedule',             'ManagerApiController::schedule');
        $routes->get('manager/reports/(:alpha)',     'ManagerApiController::report/$1');
        $routes->get('admin/applications',           'AdminApiController::applications');
        $routes->post('admin/applications/(:num)/approve', 'AdminApiController::approve/$1');
        $routes->post('admin/applications/(:num)/reject',  'AdminApiController::reject/$1');
        $routes->get('admin/members',                'AdminApiController::members');
        $routes->get('admin/staff',                  'AdminApiController::staff');
        $routes->post('admin/staff',                 'AdminApiController::createStaff');
        $routes->post('payments/topup',              'PaymentApiController::initiateTopup');
        $routes->get('payments/(:segment)',          'PaymentApiController::status/$1');
        $routes->get('notifications',                'NotificationApiController::index');
        $routes->post('notifications/(:num)/read',   'NotificationApiController::markRead/$1');
        $routes->post('notifications/read-all',      'NotificationApiController::markAllRead');
    });
});

$routes->set404Override('PublicController::notFound');
