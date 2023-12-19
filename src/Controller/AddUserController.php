<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;
use Psr\Http\Message\ResponseInterface;

#[RouteInfo("/", "POST")]
class AddUserController implements ControllerInterface
{
    public function index(Renderer $renderer, array $paramters): ResponseInterface
    {
        $body = $paramters['request']->getParsedBody();
        $user = UserModel::fetchBy(['username' => $body['username']]);

        if ($user === null || count($user) === 0) {
            (new UserModel())->setUsername($body['username'])->save();
            return new Response(302, ['Location' => '/']);
        } else {
            return new Response(200, [], 'User already exist');
        }
    }
}