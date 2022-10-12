<?php

function dd (...$vars) {
    foreach ($vars as $var) {
        print_r('<pre>'.print_r($var, true).'</pre>');
    }
    die();
    exit;
}

function dd_json ($var) {
    die(json_encode($var));
}

function generateTokenAPI ($passphrase) {
    $p1 = '$_'.bin2hex(random_bytes(16));
    $p2 = bin2hex(random_bytes(16));

    return $p1 . $passphrase . $p2;
}

function cut255($string) {
    return strlen($string) > 255 ? substr($string, 0, 255) : $string;
}

function displayErrorsAndWarnings (bool $shouldDisplay) {
    ini_set('display_errors', $shouldDisplay);
    ini_set('display_startup_errors', $shouldDisplay);
    if ($shouldDisplay) error_reporting(E_ALL);
}

function load($dir) {
    foreach (scandir($dir) as $file) {
        if ($file === 'loader.php') {
            continue;
        }
        
        if (strpos($file, '.php') !== false) {
            require_once $dir . SP . $file;
        }
    }
}

function API_headers() {
    header('Accept: *');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
        die();
    }
}