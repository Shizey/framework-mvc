<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;
use Psr\Http\Message\ResponseInterface;

#[RouteInfo("/modify/{id}/", "GET")]
class ModifyUserController implements ControllerInterface
{
    public function index(Renderer $renderer, array $parameters): ResponseInterface
    {
        $user = UserModel::fetchById($parameters['slugs']['id']);

        if ($user) {
            return new Response(200, [], $renderer->renderTwig('Actions/modify', ['user' => $user]));
        } else {
            return new Response(404, [], 'User not found');
        }
    }
}