<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');

/*
|--------------------------------------------------------------------------
| Protected routes
|--------------------------------------------------------------------------
*/

$routes->group(
    '',
    ['filter' => 'auth'],
    static function (RouteCollection $routes): void {
        $routes->get(
            'dashboard',
            'DashboardController::index'
        );

        $routes->post(
            'logout',
            'AuthController::logout'
        );

        /*
        |--------------------------------------------------------------------------
        | Product routes
        |--------------------------------------------------------------------------
        */

        $routes->get(
            'products',
            'ProductsController::index'
        );

        $routes->get(
            'products/create',
            'ProductsController::create'
        );

        $routes->post(
            'products',
            'ProductsController::store'
        );

        $routes->get(
            'products/(:num)/edit',
            'ProductsController::edit/$1'
        );

        $routes->post(
            'products/(:num)',
            'ProductsController::update/$1'
        );

        $routes->post(
            'products/(:num)/archive',
            'ProductsController::archive/$1'
        );

        /*
        |--------------------------------------------------------------------------
        | Customer routes
        |--------------------------------------------------------------------------
        */

        $routes->get(
            'customers',
            'CustomersController::index'
        );

        $routes->get(
            'customers/create',
            'CustomersController::create'
        );

        $routes->post(
            'customers',
            'CustomersController::store'
        );

        $routes->get(
            'customers/(:num)/edit',
            'CustomersController::edit/$1'
        );

        $routes->post(
            'customers/(:num)',
            'CustomersController::update/$1'
        );

        $routes->post(
            'customers/(:num)/archive',
            'CustomersController::archive/$1'
        );

        /*
        |--------------------------------------------------------------------------
        | Staff routes
        |--------------------------------------------------------------------------
        */

        $routes->get(
            'users',
            'UsersController::index'
        );

        $routes->get(
            'users/create',
            'UsersController::create'
        );

        $routes->post(
            'users',
            'UsersController::store'
        );

        $routes->get(
            'users/(:num)/edit',
            'UsersController::edit/$1'
        );

        $routes->post(
            'users/(:num)',
            'UsersController::update/$1'
        );

        $routes->post(
            'users/(:num)/archive',
            'UsersController::archive/$1'
        );

        /*
        |--------------------------------------------------------------------------
        | Sales routes
        |--------------------------------------------------------------------------
        */

        $routes->get(
            'sales',
            'SalesController::index'
        );

        $routes->get(
            'sales/create',
            'SalesController::create'
        );

        $routes->post(
            'sales',
            'SalesController::store'
        );
    }
);