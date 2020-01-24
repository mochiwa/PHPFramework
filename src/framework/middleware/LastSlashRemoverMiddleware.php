<?php
namespace Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This middleware remove , if present, the last / on the url,
 * if the url is just '/' then doesn't remove it
 *
 * @author mochiwa
 */
class LastSlashRemoverMiddleware implements MiddlewareInterface{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $uri=$request->getUri()->getPath();
        if(!empty($uri) && $uri[-1]==='/' && $uri!=='/')
        {
            $response =new \GuzzleHttp\Psr7\Response();
            return $response->withStatus(301)->withHeader('Location', substr($uri, 0,-1));
        }
        return $handler->handle($request);
    }
}
