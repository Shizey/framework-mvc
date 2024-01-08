# Routing

To create a page in your application you need to link a `Route` to a `Controller`. A controller is a PHP function you create that reads information from the Request object and creates and returns a Response object. The response could be an HTML page, JSON, XML, a file download, a redirect, a 404 error or anything else.

## Create a controller

To create a controller you need to create a PHP file in `src/Controller`. The name of that file **must finish** by Controller (Ex : `HomeController.php`). The method who will be executed when the user request the route is named `index` and is interface is the following

```php
interface ControllerInterface
{
    public function index(Renderer $renderer, array $parameters): ResponseInterface;
}
```

### Example of a controller

```php
namespace Controller;

use Framework\Attributes\RouteInfo;
use Framework\Interfaces\ControllerInterface;
use Framework\Renderer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HomeController implements ControllerInterface
{
    #[RouteInfo("/", "GET")]
    public function index(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], "Hello, World!");
    }

    #[RouteInfo('/', 'POST')]
    public function store(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], 'Hello World from POST !');
    }
}
```

### Route paramaters (Slugs and Request)

In your controller you can set variable to URL using the following syntax

```php
class HomeController implements ControllerInterface
{
    #[RouteInfo("/test/{variable}/", "GET")]
    public function index(Renderer $renderer, array $parameters): ResponseInterface
    {
        return new Response(200, [], "Slug : " . $parameters["slugs"]["variable"]);
    }
}
```

You also have in the `$parameters` variable access to the `ServerRequestInterface` from [Guzzle PSR7 implementation](https://github.com/guzzle/psr7)

```php
class ModifyUserFormController implements ControllerInterface
{
    #[RouteInfo("/test/", "POST")]
    public function index(Renderer $renderer, array $paramters): ResponseInterface
    {
        $body = $paramters['request']->getParsedBody();
    }
}
```
