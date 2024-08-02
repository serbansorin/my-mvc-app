<?php

namespace Main\Engine;

use Main\Router;

class RouteProcessor
{
    private $routes = [];
    static $newRoutes = [];
    private $bindDirectly = true;

    /**
     * Process the routes from file or array and bind them to the Router::$routes
     * @param array|string|null $routesFileOrArray
     * @param bool|null $bindDirectly
     */
    public function __construct(array|string|null $routesFileOrArray = null, bool|null $bindDirectly = false)
    {
        if (empty($routesFileOrArray)) {
            return;
        }

        $routesFileOrArray = $this->parseRoutes($routesFileOrArray);
        
        $this->setRoutes($routesFileOrArray);
        $this->bindDirectly = $bindDirectly;
        $this->processRoutes();
    }

    public function bindDirectly(bool $bindDirectly = true): RouteProcessor
    {
        $this->bindDirectly = $bindDirectly;
        return $this;
    }

    static function parseAndProcess(array|string $routeArray, $bindDirectly = false)
    {
        $inst = new self();
        $routeArray = $inst->parseRoutes($routeArray);

        $inst->setRoutes($routeArray);
        $inst->processRoutes();
        $inst->bindDirectly ?: $inst->mergeAndClean();
    }

    private function parseRoutes(array|string $routeArray): array
    {
        if (is_array($routeArray)) {
            return $routeArray;
        } elseif (is_string($routeArray)) {

            if (file_exists($routeArray)) {
                return require $routeArray;
            } else {
                throw new \InvalidArgumentException("File $routeArray does not exist.");
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument passed.");
        }
    }

    private function setRoutes($routeArray)
    {
        $this->routes = $routeArray;
    }

    public static function bindNewRoutesDirectly($newRoutes)
    {
        $routes = new self($newRoutes, true);
        // $routes->mergeAndClean();
    }

    public static function processNewRoutes($routeArray, $addDirectly = false)
    {
        return new self($routeArray, $addDirectly);
    }

    public function processRoutes()
    {
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];

        foreach ($this->routes as $path => $route) {
            $middleware = $route['middleware'] ?? $route[2] ?? null;
            $method = strtolower($route['method'] ?? $route[0]) ?? 'get';
            $controllerAction = explode('@', $route['controller'] ?? $route[1]);
            $controller = $controllerAction[0];
            $action = $controllerAction[1] ?? 'index';

            if (in_array($method, $methods)) {
                $this->addRoute($method, $path, $controller, $action, $middleware);
            } else {
                throw new \BadMethodCallException("Method {$method} does not exist.");
            }
        }
    }

    public function processRouteFile(array|string $routeFile)
    {
        $this->setRoutes($this->parseRoutes($routeFile));
        $this->processRoutes();
    }

    private function addRoute($method, $path, $controller, $action, $middleware = null)
    {
        if ($this->bindDirectly) {
            Router::$routes[$path] = [
                'method' => strtoupper($method),
                'controller' => $controller,
                'action' => $action,
                'middleware' => $middleware
            ];
            return;
        }

        self::$newRoutes[$path] = [
            'method' => strtoupper($method),
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function mergeNewRoutes()
    {
        array_merge_recursive(Router::$routes, self::$newRoutes);
    }

    public function getRoutes()
    {
        return self::$newRoutes;
    }

    public function cleanRoutes()
    {
        $this->routes = [];
    }

    public function cleanNewRoutes()
    {
        self::$newRoutes = [];
    }

    public function cleanSelf()
    {
        self::$newRoutes = [];
        $this->routes = [];
    }

    public function cleanOldRoutes()
    {
        Router::$routes = [];
    }

    public function cleanAll()
    {
        $this->cleanSelf();
        $this->cleanOldRoutes();
        return $this;
    }

    public function mergeAndClean()
    {
        $this->mergeNewRoutes();
        $this->cleanSelf();
        return $this;
    }

    public function __destruct()
    {
        $this->mergeAndClean();
    }

    public function __toString()
    {
        return json_encode($this->getRoutes());
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(RouteProcessor::class, $name)) {
            $inist = new self();
            return $inist->$name(...$arguments);
        } else {
            throw new \BadMethodCallException("Method $name does not exist.");
        }
    }
}