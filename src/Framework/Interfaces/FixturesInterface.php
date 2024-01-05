<?php

namespace Framework\Interfaces;

interface FixturesInterface
{
    public const TABLE = '';
    public function load(): void;
}