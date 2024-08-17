<?php
header('Accept: *');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Credentials', 'true');
// header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}

define('DEV', 0);
define('PROD', 1);
define('VERSION', '0.1.0');
define('SP', DIRECTORY_SEPARATOR);
define('MAIN_PATH', (dirname(__FILE__) . SP));
define('MAIN_DIR', (dirname(__DIR__) . SP));

$env = PROD;

require_once MAIN_PATH . SP . 'environment.php';
require_once MAIN_PATH . SP . 'bootstrap.php';
require_once MAIN_PATH . SP . 'general-functions.php';

// API_headers();
displayErrorsAndWarnings(!$env);

require MAIN_PATH . SP . 'loader.injector.php';

// Router

file_put_contents(
    MAIN_PATH . SP . 'logs' . SP . date('Y-m-d') . '.requests.json',
    json_encode(array(
        'post' => $_POST,
        'get' => $_GET,
        'server' => $_SERVER,
        'request' => $_REQUEST,
        'files' => $_FILES,
        'input' => file_get_contents('php://input')
    ), JSON_PRETTY_PRINT)
);

try {

    $request = new Request();
    $router = new Router($request, User::getInstance());
    $response = $router->validateRoute();

    if ($response) {
        echo $response;
    }
} catch (Exception $e) {
    echo ResponseJSON::error(500, $e->getMessage());
}
