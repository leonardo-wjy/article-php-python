<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/add', 'Home::add');
$routes->get('/id/(:segment)', 'Home::getById/$1');
$routes->post('/save', 'Home::save');
$routes->post('/update', 'Home::update');
$routes->post('/trash', 'Home::trash');