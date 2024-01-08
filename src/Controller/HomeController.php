<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

#[RouteInfo('/', 'GET')]
class HomeController implements ControllerInterface
{
    public function index(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], 'Hello World !');
    }
}
