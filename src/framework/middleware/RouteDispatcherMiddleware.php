<?php
namespace Framework\Middleware;

use Framework\DependencyInjection\IContainer;
use Framework\Router\Route;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * This middleware is responsible to make the
 * correct controller from the route in the request.
 *
 * @author mochiwa
 */
class RouteDispatcherMiddleware implements MiddlewareInterface {

    private $container;

    function __construct(IContainer $container) {
        $this->container = $container;
    }

    /**
     * When the request has not route then the handler pursues to the next,
     * if the target was a string the container resolve it to a callback , in other case
     * the callback in route will be called.
     * 
     * If the response is a string then string will put in a new response
     * else the response  will be returned.
     *  
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws RuntimeException when the callback doesn't return a string or ResponseInterface
     */
    public function process(
            ServerRequestInterface $request, 
            RequestHandlerInterface $handler): ResponseInterface {
        $route=$request->getAttribute(Route::class);
        
        if(!$route)
        {
            return $handler->handle($request);    
        }elseif (is_string($route->target()))
        {
            $callback = $this->container->get($route->target());
        } else
        {
            $callback = $route->target();
        }


        $response = call_user_func_array($callback, [$request]);

        if (is_string($response)) 
        {
            return new Response(200, [], $response);
        } 
        elseif ($response instanceof ResponseInterface)
        {
            return $response;
        } 
        else
        {
            throw new RuntimeException("The application cannot deal with this request.");
        }
    }

}
