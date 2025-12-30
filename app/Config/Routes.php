<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'RaffleController::index');

// Rotas de Autenticação (apenas para visitantes)
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

// Rotas de Rifas (CRUD) - protegidas por autenticação
$routes->group('raffles', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RaffleController::index');           // Lista todas as rifas
    $routes->get('new', 'RaffleController::new');           // Formulário de nova rifa
    $routes->post('/', 'RaffleController::create');         // Cria uma nova rifa
    $routes->get('(:num)', 'RaffleController::show/$1');    // Exibe detalhes da rifa
    $routes->get('(:num)/edit', 'RaffleController::edit/$1'); // Formulário de edição
    $routes->put('(:num)', 'RaffleController::update/$1');  // Atualiza a rifa
    $routes->delete('(:num)', 'RaffleController::delete/$1'); // Exclui a rifa
});
