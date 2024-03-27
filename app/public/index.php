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

// users endpoint
$router->post('/users/login', 'UserController@login');
$router->post('/users/signUp', 'UserController@createUser');
$router->get('/users', 'UserController@getUsers');
$router->put('/users/update', 'UserController@updateUser');
$router->put('/users/changePassword', 'UserController@changePassword');
$router->put('/users/uploadProfilePicture', 'UserController@uploadProfilePicture');
$router->delete('/users/delete/(\d+)', 'UserController@deleteUser');
$router->get('/users/(\d+)', 'UserController@getUserById');

// orders endpoint
$router->get('/orders', 'OrderController@getAllOrders');
$router->get('/orders/(\d+)', 'OrderController@getById');
$router->get('/orders/check/([\w\.]+)', 'OrderController@checkOrderById');
$router->post('/orders', 'OrderController@createOrder');
$router->put('/orders', 'OrderController@updateOrder');
$router->put('/orders/checkin', 'OrderController@setCheckin');
$router->delete('/orders', 'OrderController@deleteOrder');

// test endpoint
$router->get('/test', 'TestController@get');

// pages endpoint
$router->get('/pages/(\d+)', 'PageController@get');
$router->put('/pages/update', 'PageController@updatePage');
$router->get('/pages/names', 'PageController@getAllPageNames');
$router->get('/pages/links', 'PageController@getAllLinks');
$router->delete('/pages/delete/(\d+)', 'PageController@deletePage');
$router->get('/pages/parent', 'PageController@getAllParentPages');

// events endpoint
$router->get('/events/(\d+)', 'EventController@get');
$router->post('/events', 'EventController@addEvent');
$router->put('/events/update/(\d+)', 'EventController@updateEvent');
$router->delete('/events/delete/(\d+)', 'EventController@deleteEvent');
$router->get('/events/id/(\d+)', 'EventController@getEventById');

// Run it!
$router->run();
