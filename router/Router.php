<?php 
require_once MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Routes.php';

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
        $Controller = $currentRoute['controller'] ?? false;     

        if ($middleware) {
            $middleware = new $middleware();
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