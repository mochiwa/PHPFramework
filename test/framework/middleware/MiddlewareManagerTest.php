<?php
namespace Test\Framework\Middleware;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MiddlewareManagerTest
 *
 * @author mochiwa
 */
class MiddlewareManagerTest extends \PHPUnit\Framework\TestCase{
    private $manager;
    
    public function setUp() {
        $this->manager=new \Framework\Middleware\MiddlewareDispatcher();
    }
    
    
    public function test_addMiddleware_shouldAppendMiddleware()
    {
        $middleware=$this->createMock(\Psr\Http\Server\MiddlewareInterface::class);
        $this->manager->addMiddleware($middleware);
        $this->assertSame(1, $this->manager->middlewareCount());
    }
    
    public function test_handle_shouldReturnResponse_whenNoMiddlewareInStack()
    {
        $request= new \GuzzleHttp\Psr7\ServerRequest('GET', '/myPage');
        $response=new \GuzzleHttp\Psr7\Response();
        $this->assertEquals($response, $this->manager->handle($request));
    }
    
    public function test_handle_shouldReturnTheResponseFromFirstMiddleware()
    {
        $middleware=$this->createMock(\Psr\Http\Server\MiddlewareInterface::class);
        $middleware->method('process')->willReturn(new \GuzzleHttp\Psr7\Response(200,[], 'hello world'));
        $this->manager->addMiddleware($middleware);
        
        $request= new \GuzzleHttp\Psr7\ServerRequest('GET', '/myPage');
        $response=new \GuzzleHttp\Psr7\Response();
        
        $this->assertSame('hello world', $this->manager->handle($request)->getBody()->getContents());
    }
    
        public function test_handle_shouldReturnTheResponseFromNextMiddleware()
    {
        $middleware_one=$this->createMock(\Psr\Http\Server\MiddlewareInterface::class);
        $middleware_one->method('process')->willReturn(new \GuzzleHttp\Psr7\Response(200,[], 'hello world'));
        $middleware_second=$this->createMock(\Psr\Http\Server\MiddlewareInterface::class);
        $middleware_second->method('process')->willReturn(new \GuzzleHttp\Psr7\Response(200,[], 'bye world'));
        
        $this->manager->addMiddleware($middleware_one);
        $this->manager->addMiddleware($middleware_second);
        
        $request= new \GuzzleHttp\Psr7\ServerRequest('GET', '/myPage');
        $response=new \GuzzleHttp\Psr7\Response();
        $this->assertSame(2, $this->manager->middlewareCount());
    }
}
