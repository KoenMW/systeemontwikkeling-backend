<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . './../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// products endpoint
$router->get('/products', 'ProductController@getAll');
$router->get('/products/(\d+)', 'ProductController@getOne');
$router->post('/products', 'ProductController@create');
$router->put('/products/(\d+)', 'ProductController@update');
$router->delete('/products/(\d+)', 'ProductController@delete');

// categories endpoint
$router->get('/categories', 'CategoryController@getAll');
$router->get('/categories/(\d+)', 'CategoryController@getOne');
$router->post('/categories', 'CategoryController@create');
$router->put('/categories/(\d+)', 'CategoryController@update');
$router->delete('/categories/(\d+)', 'CategoryController@delete');

// users endpoint
$router->post('/users/login', 'UserController@login');
$router->post('/users/signUp', 'UserController@createUser');
$router->get('/users', 'UserController@getUsers');

// orders endpoint
$router->get('/orders', 'OrderController@getAllOrders');
$router->get('/orders/(\d+)', 'OrderController@getById');
$router->get('/orders/check/(\d+)', 'OrderController@checkOrderById');
$router->post('/orders', 'OrderController@createOrder');
$router->put('/orders', 'OrderController@updateOrder');
$router->put('/orders/checkin', 'OrderController@setCheckin');
$router->delete('/orders', 'OrderController@deleteOrder');

// test endpoint
$router->get('/test', 'TestController@get');

// pages endpoint
$router->get('/pages/(\d+)', 'PageController@get');

// events endpoint
$router->get('/events/(\d+)', 'EventController@get');

// Run it!
$router->run();
