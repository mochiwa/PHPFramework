<?php
namespace Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The last (in most case) middleware of the chain 
 * that return a 404 not found;
 *
 * @author mochiwa
 */
class ErrorMiddleware implements MiddlewareInterface{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $response=new \GuzzleHttp\Psr7\Response();
        $response->getBody()->write('Error 404 not found');
        return $response->withStatus(404);
    }

}
