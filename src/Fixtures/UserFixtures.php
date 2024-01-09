<?php

namespace Fixtures;

use Framework\Interfaces\FixturesInterface;
use Model\UserModel;

class UserFixtures implements FixturesInterface
{
    public const TABLE = 'user';

    public function load(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            (new UserModel())
                ->setUsername('user'.$i)
                ->save();
        }
    }
}
