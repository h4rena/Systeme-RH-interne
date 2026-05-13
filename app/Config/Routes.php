<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('employee', ['filter' => 'auth:employee'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'EmployeeController::dashboard');
	$routes->get('conges', 'EmployeeController::index');
	$routes->get('conges/create', 'EmployeeController::create');
	$routes->post('conges', 'EmployeeController::store');
});

$routes->group('rh', ['filter' => 'auth:rh'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'RhController::dashboard');
	$routes->get('conges', 'RhController::index');
	$routes->post('conges/(:num)/approve', 'RhController::approve/$1');
	$routes->post('conges/(:num)/refuse', 'RhController::refuse/$1');
});

$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'AdminController::dashboard');
	$routes->get('employes', 'AdminController::employees');
	$routes->post('employes', 'AdminController::storeEmployee');
	$routes->get('departements', 'AdminController::departements');
	$routes->post('departements', 'AdminController::storeDepartment');
	$routes->get('types-conge', 'AdminController::typesConge');
	$routes->post('types-conge', 'AdminController::storeType');
});