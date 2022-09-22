<?php
// Every Dependency in Order
require_once MAIN_PATH . 'utils' . DIRECTORY_SEPARATOR . 'loader.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'parent' . DIRECTORY_SEPARATOR . 'ModelModule.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'User.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'Images.php';
require MAIN_PATH . 'model' . DIRECTORY_SEPARATOR . 'Reigns.php';
require MAIN_PATH . 'controllers' . DIRECTORY_SEPARATOR . 'UserController.php';
require MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Request.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'FatherMiddleware.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'ItMiddleware.php';
require MAIN_PATH . 'middlewares' . DIRECTORY_SEPARATOR . 'AuthMiddleware.php';
require MAIN_PATH . 'router' . DIRECTORY_SEPARATOR . 'Router.php';