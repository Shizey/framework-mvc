<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;

class ActionController
{
    #[RouteInfo('/delete/{userID}/', 'GET')]
    public function DeleteUser(Renderer $renderer, array $parameters): void
    {
        $userID = $parameters['slugs']['userID'];

        $user = UserModel::fetchById($userID);

        if ($user !== null) {
            $user->delete();
        }

        header('Location: /');
    }

    #[RouteInfo('/edit/{userID}/', 'GET')]
    public function EditUser(Renderer $renderer, array $parameters): Response
    {
        $userID = $parameters['slugs']['userID'];

        $user = UserModel::fetchById($userID);

        if ($user !== null) {
            return new Response(200, [], $renderer->renderTwig('Home/edit', ['user' => $user]));
        } else {
            return new Response(404, [], $renderer->render('Error : User not found'));
        }
    }

    #[RouteInfo('/edit/', 'POST')]
    public function EditUserPost(Renderer $renderer, array $parameters): Response
    {
        $body = $parameters['request']->getParsedBody();

        $user = UserModel::fetchById($body['userid']);

        if ($user !== null) {
            $user->setUsername($body['newUsername']);
            $user->save();
            return new Response(302, ['Location' => '/']);
        } else {
            return new Response(404, [], $renderer->render('Error : User not found'));
        }
    }
}
