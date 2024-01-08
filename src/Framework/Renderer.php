<?php

namespace Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class Renderer
 * The Renderer class is responsible for rendering the views using diffrerent template engines
 */
class Renderer
{
    private string $viewPath;
    private string $phpExtension = '.php';
    private string $twigExtension = '.twig';
    private Environment $twig;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $loader = new FilesystemLoader($this->viewPath);
        $this->twig = new Environment($loader);
    }

    /**
     * render
     * The render method is used to render a view using basic PHP
     */
    public function render(string $view): string
    {
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . $this->phpExtension;
        $obj_clean = ob_get_clean();

        if ($obj_clean === false) {
            throw new \Exception("Impossible to render the view $view");
        }

        return $obj_clean;
    }

    /**
     * renderTwig
     * The renderTwig method is used to render a view using Twig
     * @param string $view
     * @param array<string, mixed> $params
     */
    public function renderTwig(string $view, array $params = []): string
    {
        return $this->twig->render($view . $this->twigExtension, $params);
    }
}
