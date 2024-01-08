<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HomeController implements ControllerInterface
{
    #[RouteInfo('/', 'GET')]
    public function index(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], 'Hello World !');
    }

    #[RouteInfo('/', 'POST')]
    public function store(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], 'Hello World from POST !');
    }
}
