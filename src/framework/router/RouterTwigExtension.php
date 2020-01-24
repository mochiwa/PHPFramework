<?php

namespace Framework\Router;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Description of RouterTwigExtension
 *
 * @author mochiwa
 */
class RouterTwigExtension extends AbstractExtension{
    private $router;
    
    public function __construct(IRouter $router) {
        $this->router=$router;
    }


    public function getFunctions()
    {
        return [new TwigFunction('router',[$this,'generateURL'])];
    }

    public function getTests() {
        return [new \Twig\TwigTest('currentLink', [$this,'isCurrentLink'])];
    }

    public function isCurrentLink(string $route):bool{
        return ($_SERVER['REQUEST_URI'] === $route);
    }
    
    
    public function generateURL(string $routeName,array $params=[]):string {
        return $this->router->generateURL($routeName, $params);
    }
}
