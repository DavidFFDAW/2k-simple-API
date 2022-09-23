<?php 
require_once MAIN_PATH . 'router' . SP . 'excl' . SP . 'Routes.php';

class Router {

    private $routes;
    private $request;
    private $endpoint = '';

    private function transformToEndpoint ($request_uri) {
        $wholeEndpoint = explode(API_DOMAIN, $request_uri);
        $explodedByParameters = explode('?', $wholeEndpoint[1]);
        return $explodedByParameters[0];
    }

    public function __construct(Request $request, User $userInstance) {
        $this->request = $request;
        $this->user = $userInstance;
        $this->endpoint = $this->transformToEndpoint($request->request_uri);
        $this->routes = Routes::getRoutes();
    }

    private function getCurrentRoute () {
        $routesByName = Routes::getPossibleRouteArray($this->endpoint);

        if (count($routesByName) === 0) return false;
        if (count($routesByName) === 1) return array_values($routesByName)[0];

        $filtered = array_filter($routesByName, function ($routeConfig) {
            return $routeConfig['method_type'] === $_SERVER['REQUEST_METHOD'];
        });

        return array_values($filtered)[0];
    }

    public function validateRoute () {
        $currentRoute = $this->getCurrentRoute();

        if (!$currentRoute) return ResponseJSON::error(404, 'Route not found');
        
        if ($currentRoute['method_type'] !== $_SERVER['REQUEST_METHOD']) 
            return ResponseJSON::error(405, 'Method not allowed');

        $model = new $currentRoute['model']();
        $method = $currentRoute['method'];
        $Middleware = $currentRoute['middleware'] ?? false;     
        $Controller = $currentRoute['controller'] ?? false;     

        if ($Middleware) {
            $middleware = new $Middleware();
            $middlewareResponse = $middleware->execute($this->request, $this->user);

            if ($middleware->hasError()) return $middlewareResponse;
        }

        if (!$Controller) {
            if (!method_exists($model, $method)) 
                return ResponseJSON::error(500, 'Model Method not found');
            
            return $model->$method($this->request);
        }

        $controller = new $Controller();
        if (!method_exists($controller, $method)) 
            return ResponseJSON::error(500, 'Controller Method not found');

        return $controller->$method($this->request, $model);
    }
}