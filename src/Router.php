<?php
namespace app;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function addRoute(string $route, $controller, string $action, string $method)
    {
        $this->routes[$route][$method] = ['controller' => $controller, 'action' => $action];
    }

    public function addMiddleware(string $route, $middleware, string $action)
    {
        $this->middlewares[$route][] = ['controller' => $middleware, 'action' => $action];
    }

    public function dispatch($uri, $reqMethod)
    {
        // Split the URI into the path and the query string
        list($path, $queryString) = array_pad(explode('?', $uri, 2), 2, '');

        // Parse the query string into the global $_GET array
        parse_str($queryString, $_GET);

        // Execute middlewares
        foreach ($this->middlewares as $pattern => $middlewares) {
            if (preg_match("~^$pattern$~", $path, $matches)) {
                array_shift($matches); // remove the entire matched text
                foreach ($middlewares as $middleware) {
                    $controller = new $middleware['controller'];
                    $action = $middleware['action'];
                    $controller->$action(...$matches);
                }
            }
        }

        $controllerToDispatch = 'app\controllers\HomeController';
        $actionToDispatch = 'error';
        $params = [];

        foreach ($this->routes as $pattern => $methods) {
            $pattern = preg_replace('~\{[a-zA-Z0-9_]+\}~', '([^/]+)', $pattern);
            if (preg_match("~^$pattern$~", $path, $matches)) {
                array_shift($matches); // remove the entire matched text
                if (array_key_exists($reqMethod, $methods)) {
                    $controllerToDispatch = $methods[$reqMethod]['controller'];
                    $actionToDispatch = $methods[$reqMethod]['action'];
                    $params = $matches;
                    break;
                }
            }
        }

        $controller = new $controllerToDispatch;
        $controller->$actionToDispatch(...$params);
    }
}
