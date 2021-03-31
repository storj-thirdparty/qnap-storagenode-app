<?php

/** @var \Laravel\Lumen\Routing\Router $router */
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

/**
 * Wizard page
 */
$router->get('wizard', 'WizardController@index');
$router->post('getdirectorylisting', 'WizardController@getDirectoryListing');
$router->post('getidentity', 'IdentityController@index');
$router->post('saveconfig', 'WizardController@saveConfig');
$router->get('config', 'ConfigController@index');
$router->post('config', 'ConfigController@config');
$router->post('checkRunningnode', 'ConfigController@checkRunningnode');
$router->post('isstartajax', 'ConfigController@isstartajax');
$router->post('stopNode', 'ConfigController@stopNode');
$router->post('startNode', 'ConfigController@startNode');
$router->post('updateNode', 'ConfigController@updateNode');
$router->post('setauthswitch', 'ConfigController@setAuthswitch');
$router->get('login', 'LoginController@index');
$router->post('authenticate', 'LoginController@authenticate');
