<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;
use Psr\Http\Message\ResponseInterface;

#[RouteInfo("/modify/{id}/", "POST")]
class ModifyUserFormController implements ControllerInterface
{
    public function index(Renderer $renderer, array $paramters): ResponseInterface
    {
        $body = $paramters['request']->getParsedBody();
        $user = UserModel::fetchById($paramters['slugs']['id']);

        if ($user) {
            $user->setUsername($body['username'])->save();
            return new Response(302, ['Location' => '/']);
        } else {
            return new Response(404, [], 'User not found');
        }
    }
}