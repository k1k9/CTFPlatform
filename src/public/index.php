<?php
namespace app;

require_once '../vendor/autoload.php';

use app\middlewares\AuthenticatorMiddleware;

use app\controllers\HomeController;
use app\controllers\TaskController;
use app\controllers\UserController;
use app\controllers\AdminController;
use app\controllers\ScoreboardController;

// Settings
define('ROOT', dirname(__DIR__));

$config = json_decode(file_get_contents(ROOT . '/config.json'), true);
define('DB_HOST', $config['dbHost']);
define('DB_USER', $config['dbUser']);
define('DB_PASS', $config['dbPass']);
define('DB_NAME', $config['dbName']);

define('DEV_MODE', $config['devmode']);
define('SITE_NAME', $config['siteName']);
define('RESTRICT', $config['restrict']);
define('FLAG_PREFIX', $config['flagPrefix']);

// Enabling developer mode
if (intval(DEV_MODE) === 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

// Define middlewares
$middlewares = [
    ['\/.+||^\/$', AuthenticatorMiddleware::class, 'handle'],
];

// Define routes
$routes = [
    ['^\/$', HomeController::class, 'index', 'GET'],
    ['^\/t$', TaskController::class, 'list', 'GET'],

    ['^\/t\/(\d+)$', TaskController::class, 'details', 'GET'],
    ['^\/t\/(\d+)$', TaskController::class, 'checkFlag', 'POST'],

    ['^\/t\/add$', TaskController::class, 'addIndex', 'GET'],
    ['^\/t\/add$', TaskController::class, 'add', 'POST'],
    ['^\/t\/delete$', TaskController::class, 'delete', 'GET'],

    ['^\/u\/login$', UserController::class, 'loginIndex', 'GET'],
    ['^\/u\/register$', UserController::class, 'registerIndex', 'GET'],
    ['^\/u\/register$', UserController::class, 'register', 'POST'],
    ['^\/u\/login$', UserController::class, 'login', 'POST'],
    ['^\/u\/logout$', UserController::class, 'logout', 'GET'],

    ['^\/scoreboard$', ScoreboardController::class, 'index', 'GET'],

    ['^\/a\/users$', AdminController::class, 'listUsers', 'GET'],
    ['^\/a\/settings$', AdminController::class, 'settingsIndex', 'GET'],
    ['^\/a\/settings$', AdminController::class, 'settingsChange', 'POST'],
    ['^\/a\/categories$', AdminController::class, 'categoriesIndex', 'GET'],
    ['^\/a\/addCategory$', AdminController::class, 'addCategory', 'POST'],
    ['^\/a\/tasks$', AdminController::class, 'tasksIndex', 'GET'],
];

// Add all middlewares
foreach ($middlewares as $middlewareData) {
    [$route, $class, $action] = $middlewareData;
    $router->addMiddleware($route, $class, $action);
}

// Add all routes
foreach ($routes as $routeData) {
    [$route, $controller, $action, $routeMethod] = $routeData;
    $router->addRoute($route, $controller, $action, $routeMethod);
}

$router->dispatch($uri, $method);
