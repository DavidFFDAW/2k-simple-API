<?php
class Routes {
    private static $routes = array(
        array(
            'route' => '/login',
            'model' => User::class,
            'method' => 'login',
            'method_type' => 'POST',
        ),
        array(
            'route' => '/register',
            'model' => User::class,
            'method' => 'register',
            'method_type' => 'POST',
        ),
        array(
            'route' => '/logout',
            'model' => User::class,
            'method' => 'logout',
            'method_type' => 'POST',
        ),
        array(
            'route' => '/wrestlers/get',
            'model' => wrestler::class,
            'method' => 'getNotReleasedWrestlers',
            'middleware' => AuthMiddleware::class,
            'method_type' => 'GET',
        ),
        array(
            'route' => '/champions/get/reigns',
            'model' => Reigns::class,
            'method' => 'getTotalCurrentReigns',
            'controller' => ReignsController::class,
            'middleware' => AuthMiddleware::class,
            'method_type' => 'GET',
        ),
        array(
            'route' => '/images',
            'model' => Images::class,
            'method' => 'getImages',
            'method_type' => 'GET',
        ),
        array(
            'route' => '/images/new',
            'model' => Images::class,
            'method' => 'createImage',
            'method_type' => 'POST',
        ),
        array(
            'route' => '/image/update',
            'model' => Images::class,
            'method' => 'updateImage',
            'method_type' => 'POST',
        ),
        array(
            'route' => '/images/delete',
            'model' => Images::class,
            'method' => 'deleteImageByGET',
            'method_type' => 'DELETE',
        ),
        array(
            'route' => '/test/controller',
            'model' => User::class,
            'method' => 'test',
            'controller' => UserController::class,
            'method_type' => 'GET',
        ),
        array(
            'route' => '/teams/all/members',
            'method' => 'getAllTeams',
            'controller' => TeamController::class,
            'method_type' => 'GET',
        ),
        array(
            'route' => '/teams/names',
            'method' => 'getTeamNames',
            'controller' => TeamController::class,
            'method_type' => 'GET',
        ),
    );

    public static function getRoutes() {
        return self::$routes;
    }

    public static function getPossibleRouteArray($endpoint) {

        return array_filter(self::$routes, function ($routeConfig) use ($endpoint) {
            return $routeConfig['route'] === $endpoint;
        });
    }

    public function __toString()
    {
        return json_encode(self::$routes);
    }
}