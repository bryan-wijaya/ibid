<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use app\Http\Controller\UsersController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/test', function(){
    return "test";
});

//mongodb
$router->post('/users/create', "UsersController@create");
$router->get('/users', "UsersController@read");
$router->delete('/users/delete/{id}', "UsersController@delete");
$router->put('/users/update/{id}', "UsersController@update");

//firebase 
$router->post('/firebase/create', "FirebaseController@create");
$router->get('/firebase', "FirebaseController@read");
$router->delete('/firebase/delete/{id}', "FirebaseController@delete");
$router->put('/firebase/update/{id}', "FirebaseController@update");

