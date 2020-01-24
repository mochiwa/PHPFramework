<?php

use Framework\Middleware\LastSlashRemoverMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LastSlashRemoverMiddlewareTest
 *
 * @author mochiwa
 */
class LastSlashRemoverMiddlewareTest extends TestCase {

    protected function mockHandler() {
       return $this->getMockBuilder(RequestHandlerInterface::class)
                ->setMethods(['handle'])
                ->getMock();
    }


    public function test_process_shouldRemoveLastSlash_whenURIFinishingBySlash() {
        $uri = 'mysite.com/page/';
        $uri_expected = 'mysite.com/page';
        $request = new ServerRequest('GET', $uri);

        $middleware = new LastSlashRemoverMiddleware();
        $response = $middleware->process($request, $this->mockHandler());
        $this->assertSame($uri_expected, $response->getHeader('location')[0]);
    }

    public function test_process_shouldCallTheNextHandle_whenNoSlashToRemove() {
        $handler=$this->mockHandler();
        $request = new ServerRequest('GET', 'mysite.com/page');

        $handler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function(ServerRequest $request) {
                return $request->getUri()->getPath() === 'mysite.com/page';
                }));

        $middleware = new LastSlashRemoverMiddleware();
        $middleware->process($request, $handler);
    }

}
