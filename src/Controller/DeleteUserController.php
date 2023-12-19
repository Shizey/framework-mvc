<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;
use Psr\Http\Message\ResponseInterface;

#[RouteInfo("/delete/{id}/", "GET")]
class DeleteUserController implements ControllerInterface
{
    public function index(Renderer $renderer, array $paramters): ResponseInterface
    {
        $user = UserModel::fetchById($paramters['slugs']['id']);

        if ($user) {
            $user->delete();
            return new Response(302, ['Location' => '/']);
        } else {
            return new Response(404, [], 'User not found');
        }
    }
}