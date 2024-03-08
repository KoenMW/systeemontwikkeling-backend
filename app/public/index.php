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

// test endpoint
$router->get('/test', 'TestController@get');

// pages endpoint
$router->get('/pages', 'PageController@get');

// events endpoint
$router->get('/events', 'EventController@get');

// Run it!
$router->run();
