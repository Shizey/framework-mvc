<?php

namespace Framework;

use Framework\Attributes\RouteInfo;

/**
 * Class Route
 * The Route class represents a route in the application
 * The Route class is used by the Router to get the path, method and controller of a route
 * By using the Route class, the controller must be using index as the method name.
 */
class Route
{
    /**
     * Controller is a string representing the controller class.
     *
     * @var class-string
     */
    private string $controller;

    /**
     * @param class-string $controller
     */
    public function __construct(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return \ReflectionAttribute<RouteInfo>
     */
    private function getAttributes(): \ReflectionAttribute
    {
        $controller = new \ReflectionClass($this->controller);

        return $controller->getAttributes(RouteInfo::class)[0];
    }

    public function getPath(): string
    {
        return $this->getAttributes()->newInstance()->path;
    }

    public function getMethod(): string
    {
        return $this->getAttributes()->newInstance()->method;
    }

    public function getController(): object
    {
        return new $this->controller();
    }
}
