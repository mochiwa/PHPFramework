<?php
namespace Framework\Router;

use Psr\Http\Message\RequestInterface;

/**
 * Description of IRouter
 *
 * @author mochiwa
 */
interface IRouter {
    /**
     * @param RequestInterface $request
     * @return \Framework\Router\Route|null
     */
    function match(RequestInterface $request): ?Route ;
    /**
     * 
     * @param string $method
     * @param string $uri
     * @param string|callable $callback
     * @param string $routename
     * @return \self
     */
    function map(string $method,string $uri, $callback,string $routename=''):self;
    
    /**
     * 
     * @param string $routeName
     * @param array $params
     * @return string
     */
    function generateURL(string $routeName,array $params=[]) : string;
}
