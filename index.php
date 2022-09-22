<?php

define('VERSION', '0.1.0');
define('MAIN_PATH', (dirname(__FILE__).DIRECTORY_SEPARATOR));
define('MAIN_DIR', (dirname(__DIR__).DIRECTORY_SEPARATOR));

require_once MAIN_PATH . DIRECTORY_SEPARATOR . 'environment.php';
require_once MAIN_PATH . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once MAIN_PATH . DIRECTORY_SEPARATOR . 'general-functions.php';

API_headers();

require MAIN_PATH . DIRECTORY_SEPARATOR . 'loader.injector.php';

// Router
$request = new Request();
$router = new Router($request, User::getInstance());
$response = $router->validateRoute();

if ($response) {
   echo $response; 
}