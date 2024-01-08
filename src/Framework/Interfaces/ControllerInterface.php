<?php

namespace Framework\Interfaces;

use Framework\Renderer;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface ControllerInterface
 * The ControllerInterface is used to define the methods that a controller must implement to be used by the Router.
 */
interface ControllerInterface
{
    /**
     * @param string[] $parameters
     */
    public function index(Renderer $renderer, array $parameters): ResponseInterface;
}
