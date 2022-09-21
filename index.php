<?php
    // localhost\testeos\truco-trato

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 

// ? ----- CABECERAS CORS ----- ?
header('Accept: *');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

function dd (...$vars) {
    foreach ($vars as $var) {
        print_r('<pre>'.print_r($var, true).'</pre>');
    }
    die();
    exit;
}

function generateTokenAPI ($passphrase) {
    $p1 = '$_'.bin2hex(random_bytes(16));
    $p2 = bin2hex(random_bytes(16));

    return $p1 . $passphrase . $p2;
}

function cut255($string) {
    return strlen($string) > 255 ? substr($string, 0, 255) : $string;
}

// ? ----- FIN CABECERAS CORS ----- ?

// Se cargan las clases necesarias globales
define('IMAGES_URL', 'http://vps-f87b433e.vps.ovh.net/2k/images/');
define('MAIN_PATH', (dirname(__FILE__).DIRECTORY_SEPARATOR));
define('MAIN_DIR', (dirname(__DIR__).DIRECTORY_SEPARATOR));
define('API_DOMAIN', '/2k/api/v2');


// ? ----- LOADER ----- ?
require MAIN_PATH . 'utils' . DIRECTORY_SEPARATOR . 'ResponseJSON.php';
require MAIN_PATH . 'utils' . DIRECTORY_SEPARATOR . 'EnvReader.php';
require MAIN_PATH . 'utils' . DIRECTORY_SEPARATOR . 'FileUploader.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'parent' . DIRECTORY_SEPARATOR . 'ModelModule.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'User.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'Images.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'Reigns.php';
require MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Request.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'FatherMiddleware.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'ItMiddleware.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'AuthMiddleware.php';
require MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Router.php';
// ? ----- FIN LOADER ----- ?

// Router
$request = new Request();
$router = new Router($request, User::getInstance());
$response = $router->validateRoute();

if ($response) {
   echo $response; 
}