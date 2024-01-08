<?php

namespace Framework\Attributes;

use Attribute;

/**
 * Class RouteInfo
 * The RouteInfo attribute is used to define the path and method of a route in a controller.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class RouteInfo
{
    public function __construct(public string $path, public string $method = 'GET')
    {
    }
}
