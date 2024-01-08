<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    #[RouteInfo('/', 'GET')]
    public function index(): ResponseInterface
    {
        return new Response(200, [], 'Hello World !');
    }

    #[RouteInfo('/', 'POST')]
    public function store(): ResponseInterface
    {
        return new Response(200, [], 'Hello World from POST !');
    }
}
