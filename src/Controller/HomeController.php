<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    #[RouteInfo('/', 'GET')]
    public function index(Renderer $renderer): ResponseInterface
    {
        $users = UserModel::fetchAll();
        return new Response(200, [], $renderer->renderTwig('Home/index', ['users' => $users]));
    }

    /**
     * AddUser
     * The AddUser method is used to add a user.
     * @param Renderer $renderer
     * @param array<string, mixed> $parameters
     */
    #[RouteInfo('/', 'POST')]
    public function AddUser(Renderer $renderer, array $parameters): ResponseInterface
    {
        (new UserModel())
            ->setUsername($parameters['request']->getParsedBody()['username'])
            ->save();
        return new Response(302, ['Location' => '/']);
    }
}
