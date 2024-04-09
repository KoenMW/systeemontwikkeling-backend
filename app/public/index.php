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
$router->put('/users', 'UserController@updateUser');
$router->put('/users/changePassword', 'UserController@changePassword');
$router->put('/users/uploadProfilePicture', 'UserController@uploadProfilePicture');
$router->delete('/users/delete/(\d+)', 'UserController@deleteUser');
$router->get('/users/(\d+)', 'UserController@getUserById');

// orders endpoint
$router->get('/orders', 'OrderController@getAllOrders');
$router->get('/orders/([\w\.]+)', 'OrderController@getById');
$router->get('/orders/check/([\w\.]+)', 'OrderController@checkOrderById');
$router->post('/orders', 'OrderController@createOrder');
$router->put('/orders', 'OrderController@updateOrder');
$router->put('/orders/checkin', 'OrderController@setCheckin');

$router->delete('/orders/([\w\.]+)', 'OrderController@deleteOrder');
//payment endpoint
$router->post('/payment', 'OrderController@createPayment');
// test endpoint
$router->get('/test', 'TestController@get');

// pages endpoint
$router->get('/pages/(\d+)', 'PageController@get');
$router->get('/pages/names', 'PageController@getAllPageNames');
$router->get('/pages/links', 'PageController@getAllLinks');
$router->delete('/pages/(\d+)', 'PageController@deletePage');
$router->get('/pages/parent', 'PageController@getAllParentPages');
$router->post('/pages', 'PageController@createPage');
$router->put('/pages/(\d+)', 'PageController@updatePage');
$router->get('/pages/ids', 'PageController@getAllParentIdsAndNames');
$router->get('/pages/detail/ids', 'PageController@getAllChildIdsAndNames');

// events endpoint
$router->get('/events', 'EventController@getAll');
$router->get('/events/(\d+)', 'EventController@get');
$router->post('/events', 'EventController@addEvent');
$router->put('/events/(\d+)', 'EventController@updateEvent');
$router->delete('/events/(\d+)', 'EventController@deleteEvent');
$router->get('/event/id/(\d+)', 'EventController@getEventById');

// access control endpoint
$router->get('/accessControl/(\d+)', 'AccessController@get');

//password reset endpoint
$router->post('/users/resetlink', 'UserController@reset');
$router->put('/users/resetpassword', 'UserController@resetPassword');

// Run it!
$router->run();
