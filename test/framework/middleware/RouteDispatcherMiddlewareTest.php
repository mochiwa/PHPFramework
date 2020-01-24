<?php

namespace Test\Framework\Middleware;

use Framework\DependencyInjection\IContainer;
use Framework\Middleware\RouteDispatcherMiddleware;
use Framework\Router\Route;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of RouteDispatcherMiddlewareTest
 *
 * @author mochiwa
 */
class RouteDispatcherMiddlewareTest extends TestCase {

    private $di;

    public function setUp() {
        $this->di = $this->createMock(IContainer::class);
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
        $middleware=new RouteDispatcherMiddleware($this->di);
        $middleware->process($request,$handler); 
    }

}
