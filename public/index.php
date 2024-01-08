<?php

use Dotenv\Dotenv;
use Framework\Database;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$router = new Router();

$controllers = scandir(__DIR__.'/../src/Controller');

foreach ($controllers as $controller) {
    if (str_ends_with($controller, 'Controller.php')) {
        $controller = 'Controller\\'.str_replace('.php', '', $controller);
        $router->setRoutesFromController($controller);
    }
}

Database::getInstance();

try {
    $response = $router->dispatch(ServerRequest::fromGlobals());
} catch (Exception $e) {
    $response = new Response(404, [], 'An error occured : '.$e->getMessage().' at line '.$e->getLine().' in file '.$e->getFile().'');
}

send($response);
