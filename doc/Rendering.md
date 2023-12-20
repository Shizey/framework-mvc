# Rendering

When you deal with a request, you likely want to render something on the page using HTML, CSS... You can do that using the the `Renderer` class. With him you can use PHP or Twig to render content. When you use the index function from a [Controller](./Routing.md#create-a-controller) you have access to the renderer class. You can use it to render either PHP or Twig Template

## PHP Rendering

First you need to create a PHP file in `View/<NameOfYourView>/index.php` _(you can use any name of folder or PHP file)_

Example with `View/Home/index.php`

```php
<h1>Hello, World !</h1>
```

The controller will look like this

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
        return new Response(200, [], $renderer->render('Home/index'));
    }
}
```

## Twig Rendering

The twig rendering its the same as the PHP rendering

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
        return new Response(200, [], $renderer->renderTwig('Home/index')); // Instead you will use renderTwig function
    }
}
```
