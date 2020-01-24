<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Framework\Middleware;

use Framework\Middleware\RouterMiddleware;
use Framework\Router\IRouter;
use Framework\Router\Route;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of RouterMiddleware
 *
 * @author mochiwa
 */
class RouterMiddlewareTest extends TestCase{
    private $router;
    
    public function setUp()
    {
        $this->router=$this->createMock(IRouter::class);
    }
    
    protected function mockHandler() {
       return $this->getMockBuilder(RequestHandlerInterface::class)
                ->setMethods(['handle'])
                ->getMock();
    }
    
    public function test_process_shouldHandleRequest_whenNoRouteMatch()
    {
        $request=new ServerRequest('GET', 'mySite/notExistingRoute');
        $handler=$this->mockHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->callback(function(ServerRequest $request){return $request->getAttribute(Route::class)===null;}));
        $middleware=new RouterMiddleware($this->router);
        $middleware->process($request,$handler); 
    }
    
    public function test_process_shouldAppendAttributeRoute_whenRouteMatch()
    {
        $request=new ServerRequest('GET', 'mySite/existing');
        $handler=$this->mockHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->callback(function(ServerRequest $request){return $request->getAttribute(Route::class)!==null;}));
        $this->router->expects($this->once())
                ->method('match')
                ->willReturn(new Route('existing','callable'));
                
        $middleware=new RouterMiddleware($this->router);
        $middleware->process($request,$handler); 
    }
    
    public function test_process_shouldAppendParameters_whenRouteMatch()
    {
        $request=new ServerRequest('GET', 'mySite/existing');
        $handler=$this->mockHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->callback(function(ServerRequest $request){return $request->getAttribute('att1')=='MyAttribute';}));
        $this->router->expects($this->once())
                ->method('match')
                ->willReturn(new Route('existing','callable',['att1'=>"MyAttribute"]));
                
        $middleware=new RouterMiddleware($this->router);
        $middleware->process($request,$handler); 
    }
    
    public function test_process_shouldHandleToNextHandler_whenRouteMatch()
    {
        $request=new ServerRequest('GET', 'mySite/existing');
        $handler=$this->mockHandler();
        $handler->expects($this->once())
                ->method('handle');
        $this->router->expects($this->once())
                ->method('match')
                ->willReturn(new Route('existing','callable',['att1'=>"MyAttribute"]));
                
        $middleware=new RouterMiddleware($this->router);
        $middleware->process($request,$handler); 
    }
}
