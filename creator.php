<?php

$end = PHP_EOL;
$d = DIRECTORY_SEPARATOR;
$currentDir = dirname(__FILE__);
$validTypes = array(
    "resource" => "createResource",
    "route" => "createRoute",
    "model" => "createModel",
    "controller" => "createController",
    "middleware" => "createMiddleware",
);
$types = array_keys($validTypes);


if ($argc < 2) {
    die("Usage: php creator.php <type> <name>$end");
}

$type = isset($argv[1]) ? trim($argv[1]) : false;
$name = isset($argv[2]) ? trim($argv[2]) : false;

if (!$type) {
    die("Type is missing!$end Usage: php creator.php <type> <name>$end");
}

if (!in_array($type, $types)) {
    die("Invalid type. Valid types are: (" . implode(', ', $types) . ")$end");
}

if (!$name) {
    die("Name is missing!$end Usage: php creator.php <type> <name>$end");
}

function createModel ($name) {
    global $currentDir, $d, $end;
    $modelDir = $currentDir . $d . 'model' . $d;
    $modelFile = $modelDir . $name . '.php';

    if (!file_exists($modelFile)) {
        $modelContent = '<?php
        
class '.$name.' extends DatabaseModel implements ModelInterface {

    private static $tableN = "' . strtolower($name) . 's";
    private static $neededField = [
        "name",
        // ...,
    ];

    public function __construct($'. strtolower($name). ') {
        $this->id = $'. strtolower($name). '["id"];

    }

    public static function getRequiredFields() {
        return self::$neededField;
    }

    public static function findAll() { }
    public static function find(int $id) { }
    public static function create($data): bool {return true; }
    public function update($data): bool { return true; }
    public function delete(): bool { return true; }  
}              
        ';
        file_put_contents($modelFile, $modelContent);
        echo "Model created successfully!$end";
    } else {
        echo "Model already exists!$end";
    }
}

function createRoute($modelName, $controllerName = '') {
    global $currentDir, $d, $end;
    $routeDir = $currentDir . $d . 'router' . $d . 'excl' . $d;
    $routeFile = $routeDir . 'routes.json';

    if (!file_exists($routeFile)) {
        die("Routes file does not exist!$end");
    }

    $route = array(
        "route" => "/add/your/route/here",
        "model" => "$modelName",
        "method" => "index",
        "controller" => "$controllerName",
        "middleware" => "AuthMiddleware",
        "method_type" => "GET"
    );
    $previousRoutes = json_decode(file_get_contents($routeFile), true);
    $previousRoutes[] = $route;
    $newRoutes = str_replace('\/', '/', json_encode($previousRoutes, JSON_PRETTY_PRINT));
    $hasAdded = file_put_contents($routeFile, $newRoutes);
    if (!$hasAdded) die ("Error adding route $end");
    echo "Route added successfully!$end";
    return $hasAdded;
}

function createController($name) {
    global $currentDir, $d, $end;
    $controllerDir = $currentDir . $d . 'controllers' . $d;
    $finalName = $name . 'Controller';
    $controllerFile = $controllerDir . $finalName . '.php';

    if (file_exists($controllerFile)) {
        die("Controller already exists!$end");
    }

    $controllerContent = "<?php
class $finalName {
    private function index($name \$item) {
        // your code here
    }

    
}";
    file_put_contents($controllerFile, $controllerContent);
    echo "Controller created successfully!$end";
}

function createMiddleware($name) {
    global $currentDir, $d, $end;
    $middlewareDir = $currentDir . $d . 'middlewares' . $d;
    $finalName = $name . 'Middleware';
    $middlewareFile = $middlewareDir . $finalName . '.php';

    if (file_exists($middlewareFile)) {
        die("Middleware already exists!$end");
    }

    $middlewareContent = "<?php
class AuthMiddleware extends FatherMiddleware implements ItMiddleware {

    public function __construct() {
        parent::__construct();
    }
}";
    file_put_contents($middlewareFile, $middlewareContent);
    echo "Middleware created successfully!$end";
}

function createResource($name) {
    createModel($name);
    createRoute($name, $name . 'Controller');
    createController($name);
}

function createGeneratorElement($type, $name) {
    global $validTypes;
    $selectedF = $validTypes[$type];

    if (!isset($selectedF) && !function_exists($selectedF)) {
        die('Error during creation of element');
    }
    
    return $selectedF($name);
}

createGeneratorElement($type, $name);