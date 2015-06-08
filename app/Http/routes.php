<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


$router->resource('/word', 'WordController');
$router->resource('/asset', 'AssetController');


$router->resource('/api/package', 'Api\PackageController');
$router->resource('/api/user', 'Api\UserController');
$router->resource('/api/userPackage', 'Api\UserPackageController');
$router->resource('/api/set', 'Api\SetController');
$router->resource('/api/set.score', 'Api\SetScoreController');
$router->post('api/purchasePackage', 'Api\UserPackageController@purchasePackage');
$router->post('api/tryPackage', 'Api\UserPackageController@tryPackage');

//Authentication
$router->post('/api/login', 'Api\UserController@login');
$router->post('/api/register', 'Api\UserController@register');
