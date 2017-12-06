<?php

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

$router->get('/', 'StatusController@checkStatus');

$router->get('/home', 'HomeController@home');

$router->post('/register', 'UsersController@register');
$router->post('/login', 'UsersController@login');

$router->get('/users/{id}', 'UsersController@profile');

$router->post('/videos', ['middleware' => 'auth', 'uses' => 'VideosController@create']);
$router->post('/videos/{id}', ['middleware' => 'auth', 'uses' => 'VideosController@upload']);
$router->get('/videos/{id}', 'VideosController@watch');