<?php

namespace Framework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 * The Router class is responsible for dispatching the routes to the controllers
 */
class Router
{
    private array $routes = [];

    /**
     * add
     * The add method is used to add a route to the router
     */
    public function add(Route $route): void
    {
        $routesPath = $this->getRoutesPath();

        $findRoute = array_search($route->getPath(), $routesPath);

        if ($findRoute !== false && $this->routes[$findRoute]->getMethod() === $route->getMethod()) {
            throw new \Exception('This route already exist with the path: ' . $route->getPath());
        }

        $this->routes[] = $route;
    }

    /**
     * dispatch
     * The dispatch method is used to dispatch the route to the controller
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $renderer = new Renderer(__DIR__ . '/../View');
        $routesPath = $this->getRoutesPath();
        $routeInfo = $this->getMatchedRoute($request->getUri()->getPath(), $routesPath);
        $routePath = $routeInfo['path'];

        if ($routePath === '') {
            throw new \Exception('This route does not exist with the path: ' . $request->getUri()->getPath());
        }

        $methodRoute = array_filter($this->routes, function ($route) use ($routePath, $request) {
            return $route->getPath() === $routePath && $route->getMethod() === $request->getMethod();
        });

        if (count($methodRoute) === 0) {
            throw new \Exception('The route ' . $routePath . ' does not exist with the method: ' . $request->getMethod());
        }

        if (count($methodRoute) > 1) {
            throw new \Exception('The route ' . $routePath . ' exist more than one time with the method: ' . $request->getMethod());
        }

        $route = array_shift($methodRoute);

        $controller = $route->getController();

        return $controller->index($renderer, [
            'slugs' => $routeInfo['slugs'],
            'request' => $request,
        ]);
    }

    private function getRoutesPath(): array
    {
        return array_map(function ($route) {
            return $route->getPath();
        }, $this->routes);
    }

    private function ensureUrlEndsWithSlash(string $url): string
    {
        if (!str_ends_with($url, '/')) {
            $url .= '/';
        }
        return $url;
    }

    /**
     * getSlugs
     * The getSlugs method is used to get the slugs of the route
     * The slugs are the parameters of the route
     * They are defined by the curly braces
     * For example: /user/{id}
     * 
     * Example of input:
     * $explodedUrl = ['user', '1']
     * $explodedValue = ['user', '{id}']
     * 
     * Example of output:
     * $slugs = ['id' => '1']
     * $explodedValue = ['user', '1']
     */
    private function getSlugs(array $explodedUrl, array $explodedValue): array
    {
        $slugs = [];
        foreach ($explodedValue as $key => $value) {
            if (preg_match('/{.*}/', $value)) {
                $cleanSlug = str_replace(['{', '}'], '', $value);
                $slugs[$cleanSlug] = $explodedUrl[$key] ?? null;
                $explodedValue[$key] = $explodedUrl[$key] ?? null;
            }
        }
        return [$slugs, $explodedValue];
    }

    /**
     * getMatchedRoute
     * The getMatchedRoute method take the url and the routes and return the route that match the url
     */
    private function getMatchedRoute(string $url, array $urls): array
    {
        $url = $this->ensureUrlEndsWithSlash($url);

        foreach ($urls as $currentUrl) {
            $explodedUrl = explode('/', $url);
            $explodedValue = explode('/', $currentUrl);

            list($slugs, $explodedValue) = $this->getSlugs($explodedUrl, $explodedValue);

            if ($url === implode('/', $explodedValue)) {
                return [
                    'path' => $currentUrl,
                    'slugs' => $slugs
                ];
            }
        }

        return [
            'path' => '',
            'slugs' => []
        ];
    }

}