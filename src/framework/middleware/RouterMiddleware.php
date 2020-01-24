<?php
namespace Framework\Middleware;

use Framework\Router\IRouter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * management of router
 *
 * @author mochiwa
 */
class RouterMiddleware implements MiddlewareInterface{
    private $router;
    function __construct(IRouter $router) {
        $this->router=$router;
    }
    
    /**
     * If route (aka request) contain parameters ,then these will be appended 
     * through the attribute with same key.
     * if any route match then the handler is pursue , it same if 
     * route was found but parameters will be appended and the route obj 
     * will be inserted through the request at key route::class
     * 
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $route= $this->router->match($request);
        
        if(!$route)
        {
           return $handler->handle($request);
        }
        
        foreach ($route->params() as $key=>$param)
        {
           $request=$request->withAttribute($key,$param);
        }
        
        $request=$request->withAttribute(get_class($route), $route);
        return $handler->handle($request);
    }
}