<?php

namespace Framework\Controller;

use Framework\DependencyInjection\IContainer;
use Framework\Router\IRouter;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of AbstractController
 *
 * @author mochiwa
 */
abstract class AbstractController {
    const BAD_REQUEST = 400;
    const FORBIDDEN = 403;
    const OK=200;
    
    /**
     * This constant should be the default action of the controller
     */
    const INDEX="/index";
    
    /**
     * the dependency injector
     * @var IContainer 
     */
    protected $container;
    
    /**
     * @var IRouter 
     */
    protected $router;


    public function __construct(IContainer $container) {
        $this->container=$container;
        $this->router=$container->get(IRouter::class);
    }
    
    
    /**
     * This method dispatch the action from the URL , if a method name match
     * with the action then return its result ,
     * If any method find then redirect to the index constant
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public abstract function __invoke(RequestInterface $request) : ResponseInterface;
    
    
    
    /**
     * This method is the default route that every controller must implement.
     */
    protected abstract function index(RequestInterface $request) : ResponseInterface;
    
    /**
     * Return an Response interface built with the body
     * @param string $body
     * @param int $code
     * @return ResponseInterface
     */
    protected function buildResponse(string $body,int $code=200) : ResponseInterface
    {
        $response=new Response(200);
        $response->getBody()->write($body);
        return $response;
    }
    
    /**
     * Return a response with a redirection header
     * The target must be knew by the router
     * @param string $target
     * @param int $code
     * @return ResponseInterface
     */
    protected function redirectTo(string $target,int $code=200,string $cause='') : ResponseInterface
    {
        return (new Response($code))
            ->withHeader('Location', $this->router->generateURL($target))
            ->withStatus($code, $cause);
    }
    /**
     * Return a response with a redirection header to a specific action
     * The target must be knew by the router
     * @param string $target
     * @param int $code
     * @return ResponseInterface
     */
    protected function redirectToAction(string $target,array $argument=[]) : ResponseInterface
    {
        return  (new Response(200))->withHeader('Location', $this->router->generateURL($target,$argument));
    }
    
    /**
     * Return true if the request is a POST request
     * @param RequestInterface $request
     * @return bool
     */
    protected function isPostRequest(RequestInterface $request):bool
    {
        return $request->getMethod()==='POST';
    }
    
    protected function isAjaxRequest(RequestInterface $request):bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
}
