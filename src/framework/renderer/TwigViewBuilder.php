<?php

namespace Framework\Renderer;

/**
 * Description of TwigViewBuilder
 *
 * @author mochiwa
 */
class TwigViewBuilder implements IViewBuilder {
    private $loader;
    private $twig;

    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        $this->loader=$loader;
        $this->twig=$twig;
    }

    public function addGlobal(string $key, $data): \Framework\Renderer\IViewBuilder {
        $this->twig->addGlobal($key, $data);
        return $this;
    }

    public function addPath(string $namepsace, string $path): \Framework\Renderer\IViewBuilder {
        $this->loader->addPath($path, $namepsace);
        return $this;
    }

    public function build(string $view, array $parameters = array()): string {
        return $this->twig->render($view.'.twig', $parameters);
    }

    public function query(string $view, array $parameters = array()): string {
        return $this->build($view, $parameters);
    }

    public function setDefaultLayout(string $view) {
        $this->loader->setPaths($view);
    }

}
