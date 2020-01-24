<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Framework\Middleware;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of ErrorMiddlewareTest
 *
 * @author mochiwa
 */
class ErrorMiddlewareTest extends \PHPUnit\Framework\TestCase{
    protected function mockHandler() {
       return $this->getMockBuilder(RequestHandlerInterface::class)
                ->setMethods(['handle'])
                ->getMock();
    }
    
    public function test_proccess_shouldReturnResponseWith404StatusCode()
    {
        $request=new \GuzzleHttp\Psr7\ServerRequest('POST', 'foo.com');
        $middleware=new \Framework\Middleware\ErrorMiddleware();
        $response=$middleware->process($request, $this->mockHandler());
        $this->assertSame(404, $response->getStatusCode());
    }
}
