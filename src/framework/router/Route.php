<?php

namespace Framework\Router;
/**
 * This class define a route used in a router
 *
 * @author mochiwa
 */
class Route {

    /**
     * @var string The route name as the router know
     */
    private $name;

    /**
     * @var string|callable The target 
     */
    private $target;

    /**
     *
     * @var array list of parameters to pass through the target
     */
    private $params;

    /**
     * 
     * @param string $name
     * @param string|callable $target
     * @param array $params
     */
    public function __construct(string $name,  $target, array $params = []) {
        $this->name = $name;
        $this->target = $target;
        $this->params = $params;
    }

    /**
     * Return the target
     * @return string|callable the target
     */
    public function target()  {
        return $this->target;
    }

    /**
     * Return list of parameters in the URI
     * @return array
     */
    public function params(): array {
        return $this->params;
    }
    
    /**
     * Return the parameter if exist , else return empty string
     * @param \Framework\Router\String $param
     * @return string
     */
    public function param(String $param) :string{
        return $this->params[$param] ?? '';
    }

    public function name():string{
        return $this->name;
    }
}
