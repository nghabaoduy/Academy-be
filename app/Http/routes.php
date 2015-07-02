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

$router->resource('/api/sharedData', 'Api\SharedDataController');
$router->post('/api/updateDataUpdateDate', 'Api\SharedDataController@updateDataUpdateDate');



$router->resource('/word', 'WordController@create');
$router->resource('/asset', 'AssetController');
$router->get('/wordDuplicationCheck', 'WordController@checkForDuplication');
$router->post('/registerWord', 'WordController@registerWords');

$router->resource('/setWord', 'Api\SetWordController');

$router->resource('/api/package', 'Api\PackageController');
$router->resource('/api/user', 'Api\UserController');
$router->resource('/api/userPackage', 'Api\UserPackageController');
$router->resource('/api/set', 'Api\SetController');
$router->resource('/api/setWord', 'Api\SetWordController');
$router->resource('/api/set.score', 'Api\SetScoreController');
$router->resource('/api/word', 'Api\WordController');
$router->resource('/api/wordLearned', 'Api\WordLearnedController');
$router->post('api/purchasePackage', 'Api\UserPackageController@purchasePackage');
$router->post('api/renewPurchase', 'Api\UserPackageController@renewPurchase');
$router->post('api/tryPackage', 'Api\UserPackageController@tryPackage');
$router->post('/api/changeProfileName', 'Api\UserController@changeProfileName');
$router->post('/api/uploadAvatar', 'Api\UserController@uploadUserAvatar');
$router->post('/api/getUser', 'Api\UserController@getUserWithUsername');
$router->post('/api/changeScore', 'Api\UserPackageController@setPackageScore');
$router->post('/api/uploadWordLearnedList', 'Api\WordLearnedController@uploadWordLearnedList');


//Authentication
$router->post('/api/login', 'Api\UserController@login');
$router->post('/api/loginWithFBId', 'Api\UserController@loginWithFBId');
$router->post('/api/loginWithGGPId', 'Api\UserController@loginWithGGPId');
$router->post('/api/register', 'Api\UserController@register');
$router->post('/api/changePassword', 'Api\UserController@changePassword');
$router->post('/api/forgotPassword', 'Api\UserController@forgotPassword');
