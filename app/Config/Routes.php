<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// ROTAS PÚBLICAS
// ============================================

// Página inicial
$routes->get('/', 'HomeController::index');

// Rifas públicas
$routes->get('rifas', 'HomeController::index');
$routes->get('rifa/(:segment)', 'HomeController::show/$1');

// Ganhadores
$routes->get('ganhadores', 'HomeController::winners');

// Contato
$routes->get('contato', 'HomeController::contact');
$routes->post('contato', 'HomeController::sendContact');

// Checkout e Pagamento (não precisa estar logado)
$routes->get('checkout/(:segment)', 'HomeController::checkout/$1');
$routes->post('checkout/(:segment)', 'HomeController::processCheckout/$1');
$routes->get('confirmacao/(:num)', 'TicketController::confirm/$1');
$routes->post('confirmacao/(:num)/pagamento', 'TicketController::processPayment/$1');
$routes->get('pagamento/(:any)', 'HomeController::payment/$1');
$routes->get('pedido/(:any)', 'HomeController::orderDetails/$1');

// Meus Pedidos (precisa estar logado ou acessar por email)
$routes->get('meus-pedidos', 'HomeController::myOrders');
$routes->post('meus-pedidos', 'HomeController::myOrders');

// ============================================
// ROTAS DE AUTENTICAÇÃO
// ============================================

// Apenas para visitantes (não logados)
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::attemptForgotPassword');
    $routes->get('reset-password/(:any)', 'AuthController::resetPassword/$1');
    $routes->post('reset-password', 'AuthController::attemptResetPassword');
});

// Logout (apenas para usuários logados)
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

// Atalho para o dashboard (evita 404 em /dashboard)
$routes->get('dashboard', 'Admin\\DashboardController::index', ['filter' => 'auth']);

// ============================================
// ROTAS DO PAINEL ADMINISTRATIVO
// ============================================

$routes->group('admin', ['filter' => 'auth', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
    
    // Dashboard
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    
    // Rifas
    $routes->get('raffles', 'RaffleController::index');
    $routes->get('raffles/create', 'RaffleController::create');
    $routes->get('raffles/search', 'RaffleController::search');
    $routes->post('raffles', 'RaffleController::store');
    $routes->get('raffles/(:num)', 'RaffleController::show/$1');
    $routes->get('raffles/(:num)/edit', 'RaffleController::edit/$1');
    $routes->put('raffles/(:num)', 'RaffleController::update/$1');
    $routes->delete('raffles/(:num)', 'RaffleController::delete/$1');
    $routes->post('raffles/(:num)/generate-numbers', 'RaffleController::generateNumbers/$1');
    $routes->post('raffles/(:num)/draw', 'RaffleController::draw/$1');

    // Prmios
    $routes->get('raffles/(:num)/prizes', 'PrizeController::index/$1');
    $routes->get('raffles/(:num)/prizes/new', 'PrizeController::new/$1');
    $routes->post('raffles/(:num)/prizes', 'PrizeController::create/$1');
    $routes->get('raffles/(:num)/prizes/(:num)/edit', 'PrizeController::edit/$1/$2');
    $routes->put('raffles/(:num)/prizes/(:num)', 'PrizeController::update/$1/$2');
    $routes->delete('raffles/(:num)/prizes/(:num)', 'PrizeController::delete/$1/$2');
    
    // Pedidos
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders/(:num)/confirm', 'OrderController::confirmPayment/$1');
    $routes->post('orders/(:num)/cancel', 'OrderController::cancel/$1');
    $routes->post('orders/process-expired', 'OrderController::processExpired');
    
    // Usuários
    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users', 'UserController::store');
    $routes->get('users/(:num)', 'UserController::show/$1');
    $routes->get('users/(:num)/edit', 'UserController::edit/$1');
    $routes->put('users/(:num)', 'UserController::update/$1');
    $routes->delete('users/(:num)', 'UserController::delete/$1');
    $routes->post('users/(:num)/toggle', 'UserController::toggleStatus/$1');
    
    // Configurações
    $routes->get('settings', 'SettingController::index');
    $routes->post('settings', 'SettingController::update');
    $routes->get('settings/payment', 'SettingController::payment');
    $routes->post('settings/payment', 'SettingController::updatePayment');
});

// ============================================
// WEBHOOK DE PAGAMENTO
// ============================================
$routes->post('webhook/payment', 'WebhookController::payment');
$routes->post('webhook/mercadopago', 'WebhookController::mercadopago');
$routes->post('webhook/pagseguro', 'WebhookController::pagseguro');
