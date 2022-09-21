<?php 
require_once MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Routes.php';

class Router {

    private $routes;
    private $request;
    private $endpoint = '';

    private function transformToEndpoint ($request_uri) {
        $end = explode(API_DOMAIN, $request_uri);
        return $end[1];
    }

    public function __construct(Request $request, User $userInstance) {
        $this->request = $request;
        $this->user = $userInstance;
        $this->endpoint = $this->transformToEndpoint($request->request_uri);
        $this->routes = Routes::getRoutes();
    }

    private function getCurrentRoute () {
        return $this->routes[$this->endpoint] ?? false;
    }

    public function validateRoute () {
        $currentRoute = $this->getCurrentRoute();
        
        if (!$currentRoute) return ResponseJSON::error(404, 'Route not found');
        
        if (!in_array(trim($_SERVER['REQUEST_METHOD']), $currentRoute['method_type'])) 
            return ResponseJSON::error(405, 'Method not allowed');

        $model = new $currentRoute['model']();
        $method = $currentRoute['method'];
        $middleware = $currentRoute['middleware'] ?? false;        

        if (!method_exists($model, $method)) 
            return ResponseJSON::error(500, 'Method not found');

        if ($middleware) {
            $middleware = new $middleware();
            $middlewareResponse = $middleware->execute($this->request, $this->user);

            if ($middleware->hasError()) return $middlewareResponse;
        }

        return $model->$method($this->request);
    }
}