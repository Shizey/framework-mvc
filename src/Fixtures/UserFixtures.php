<?php

namespace Fixtures;

use Framework\Interfaces\FixturesInterface;
use Model\UserModel;

class UserFixtures implements FixturesInterface
{
    public const TABLE = 'user';
    public function load(): void
    {
        $user = new UserModel();

        $user->setUsername('admin');
        $user->save();
    }
}