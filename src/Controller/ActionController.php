<?php

namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Model\UserModel;

class ActionController
{
    /**
     * DeleteUser
     * The DeleteUser method is used to delete a user.
     *
     * @param array<string, mixed> $parameters
     */
    #[RouteInfo('/delete/{userID}/', 'GET')]
    public function DeleteUser(Renderer $renderer, array $parameters): void
    {
        $userID = $parameters['slugs']['userID'];

        $user = UserModel::fetchById($userID);

        if ($user instanceof UserModel) {
            $user->delete();
        }

        header('Location: /');
    }

    /**
     * EditUser
     * The EditUser method is used to display the edit user form.
     *
     * @param array<string, mixed> $parameters
     */
    #[RouteInfo('/edit/{userID}/', 'GET')]
    public function EditUser(Renderer $renderer, array $parameters): Response
    {
        $userID = $parameters['slugs']['userID'];

        $user = UserModel::fetchById($userID);

        if ($user instanceof UserModel) {
            return new Response(200, [], $renderer->renderTwig('Home/edit', ['user' => $user]));
        } else {
            return new Response(404, [], $renderer->render('Error : User not found'));
        }
    }

    /**
     * EditUserPost
     * The EditUserPost method is used to edit a user.
     *
     * @param array<string, mixed> $parameters
     */
    #[RouteInfo('/edit/', 'POST')]
    public function EditUserPost(Renderer $renderer, array $parameters): Response
    {
        $body = $parameters['request']->getParsedBody();

        $user = UserModel::fetchById($body['userid']);

        if ($user instanceof UserModel) {
            $user->setUsername($body['newUsername']);
            $user->save();

            return new Response(302, ['Location' => '/']);
        } else {
            return new Response(404, [], $renderer->render('Error : User not found'));
        }
    }
}
