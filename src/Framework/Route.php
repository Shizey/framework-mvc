<?php

namespace Framework;

use Framework\Attributes\RouteInfo;
use ReflectionMethod;

/**
 * Class Route
 * The Route class represents a route in the application
 * The Route class is used by the Router to get the path, method and controller of a route
 * By using the Route class, the controller must be using index as the method name.
 */
class Route
{
    private ReflectionMethod $reflectionMethod;
    private string $path;
    private string $method;

    public function __construct(ReflectionMethod $reflectionMethod, string $path, string $method)
    {
        $this->reflectionMethod = $reflectionMethod;
        $this->path = $path;
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getReflectionMethod(): ReflectionMethod
    {
        return $this->reflectionMethod;
    }

    public function getController(): object
    {
        return $this->reflectionMethod->getDeclaringClass()->newInstance();
    }
}
