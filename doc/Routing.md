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

#[RouteInfo("/", "GET")]
class HomeController implements ControllerInterface
{
    public function index(Renderer $renderer, array $slugs): ResponseInterface
    {
        return new Response(200, [], "Hello, World!");
    }
}
```
