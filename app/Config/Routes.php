<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'RaffleController::index');

// Rotas de Rifas (CRUD)
$routes->group('raffles', function ($routes) {
    $routes->get('/', 'RaffleController::index');           // Lista todas as rifas
    $routes->get('new', 'RaffleController::new');           // Formulário de nova rifa
    $routes->post('/', 'RaffleController::create');         // Cria uma nova rifa
    $routes->get('(:num)', 'RaffleController::show/$1');    // Exibe detalhes da rifa
    $routes->get('(:num)/edit', 'RaffleController::edit/$1'); // Formulário de edição
    $routes->put('(:num)', 'RaffleController::update/$1');  // Atualiza a rifa
    $routes->delete('(:num)', 'RaffleController::delete/$1'); // Exclui a rifa
});
