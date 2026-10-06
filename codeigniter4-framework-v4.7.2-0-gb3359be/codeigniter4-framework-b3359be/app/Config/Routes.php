<?php

use CodeIgniter\Router\RouteCollection;
use CodeIngiter\Controllers\FormationController;
/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
$routes->get('/','FormationController::index');



$routes->get('/api/formations', 'FormationController::apiGetAll');
$routes->get('/api/formations/(:num)', 'FormationController::apiGetOne/$1');
$routes->get('/api/formations', 'FormationController::apiCreate');
