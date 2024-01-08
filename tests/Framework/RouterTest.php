<?php

use Framework\Interfaces\ControllerInterface;
use Framework\Route;
use Framework\Router;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// ...

final class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testAddRoute(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/test');
        $route->method('getMethod')->willReturn('GET');

        $this->router->add($route);
        // Assert that the route there is no exception
        $this->assertTrue(true);
    }

    public function testAddExistingRouteThrowsException(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/test');
        $route->method('getMethod')->willReturn('GET');

        $this->router->add($route);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This route already exist with the path: /test');

        $this->router->add($route);
    }

    public function testDispatchReturnsResponse(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/test/');
        $route->method('getMethod')->willReturn('GET');

        $controller = $this->createMock(ControllerInterface::class);
        $controller->method('index')->willReturn($this->createMock(ResponseInterface::class));

        $route->method('getController')->willReturn($controller);

        $this->router->add($route);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn(new class() {
            public function getPath()
            {
                return '/test';
            }
        });
        $request->method('getMethod')->willReturn('GET');

        $response = $this->router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDispatchNonExistingRouteThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This route does not exist with the path: /test2');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn(new class() {
            public function getPath()
            {
                return '/test2';
            }
        });

        $this->router->dispatch($request);
    }

    public function testDispatchNonExistingRouteWithMethodThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The route /test/ does not exist with the method: POST');

        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/test/');
        $route->method('getMethod')->willReturn('GET');

        $controller = $this->createMock(ControllerInterface::class);

        $route->method('getController')->willReturn($controller);

        $this->router->add($route);

        $request = $this->createMock(ServerRequestInterface::class);

        $request->method('getUri')->willReturn(new class() {
            public function getPath()
            {
                return '/test';
            }
        });

        $request->method('getMethod')->willReturn('POST');

        $this->router->dispatch($request);
    }
}
