<?php
class Routes {
    private static $routes = array(
        '/login' => array(
            'model' => User::class,
            'method' => 'login',
            'method_type' => ['POST'],
        ),
        '/register' => array(
            'model' => User::class,
            'method' => 'register',
            'method_type' => ['POST'],
        ),
        '/logout' => array(
            'model' => User::class,
            'method' => 'logout',
            'method_type' => ['POST'],
        ),
        '/champions/get/reigns' => array(
            'model' => Reigns::class,
            'method' => 'getReigns',
            'middleware' => AuthMiddleware::class,
            'method_type' => ['GET'],
        ),
        '/images' => array(
            'model' => Images::class,
            'method' => 'getImages',
            'method_type' => ['GET'],
        ),
        '/images/new' => array(
            'model' => Images::class,
            'method' => 'createImage',
            'method_type' => ['POST'],
        ),
        '/images/delete' => array(
            'model' => Images::class,
            'method' => 'deleteImageByGET',
            'method_type' => ['DELETE'],
        ),
    );

    public static function getRoutes() {
        return self::$routes;
    }

    public function __toString()
    {
        return json_encode(self::$routes);
    }
}