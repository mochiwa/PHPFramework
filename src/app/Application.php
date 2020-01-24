<?php

namespace App;

use Framework\DependencyInjection\IContainer;
use Framework\Middleware\IMiddlewareDispatcher;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The main application
 *
 * @author mochiwa
 */
class Application {
    /**
     * @var array contain all modules 
     */
    private $modules;
   
    /**
     * @var middlewareDispatcher 
     */
    private $middlewareDispatcher;
    
    /**
     * @var IContainer the dependency container
     */
    private $container;
    
    
    public function __construct(IContainer $container) {
        $this->container=$container;
        $this->middlewareDispatcher=$this->container->get(IMiddlewareDispatcher::class);
    }
    
    /**
     * Append a module to the application,
     * If module contain a definition then container append it.
     * @param string $module
     * @return \self
     */
    public function addModule(string $module): self{
        if($module::DEFINITION)
        {
            $this->container->appendDefinition (require_once $module::DEFINITION);
        }
        $this->modules[]=$this->container->get($module);
        return $this;
    }
    
    /**
     * Pipe a middleware
     * @param string $middleware
     * @return \self
     */
    public function pipe(string $middleware):self{
        $this->middlewareDispatcher->addMiddleware($this->container->get($middleware));
        return $this;
    }
    
    /**
     * Launch the application
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function run(RequestInterface $request) : ResponseInterface{
        $response=$this->middlewareDispatcher->handle($request);
        return $response;
    }
    
   
}
