<?php
namespace Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;


/**
 * This class is responsible to manage and dispatch
 * middleware
 *
 * @author mochiwa
 */
class MiddlewareDispatcher implements IMiddlewareDispatcher {
    
    /**
     *
     * @var int contain the current index 
     */
    private $currentIndex;
    
    /**
     *
     * @var array contains all middleware 
     */
    private $middlewares;
    
   
    
    
    public function __construct() {
        $this->currentIndex=0;
        $this->middlewares=[];
    }
    
    /**
     * Append a middleware to the manager
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):IMiddlewareDispatcher
    {
        $this->middlewares[]=$middleware;
        return $this;
    }
    
    /**
     * Return the count of middlewares
     * @return int count of middleware
     */
    public function middlewareCount() : int
    {
        return sizeof($this->middlewares);
    }
    
    /**
     * Execute each middleware contained in middlewares
     * if the currentMiddleware is null return an empty response
     * else call the next process.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface {
        $middleware=$this->getCurrentMiddleware();
        if($middleware===null){
            return new Response();
        }
        $this->currentIndex++;
        return $middleware->process($request, $this);
    }

    
    public function getCurrentMiddleware() : ?MiddlewareInterface
    {
        if(array_key_exists($this->currentIndex,$this->middlewares))
        {
            return $this->middlewares[$this->currentIndex];
        }
        return null;
    }
}
