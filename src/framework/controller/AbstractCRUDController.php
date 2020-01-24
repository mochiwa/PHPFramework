<?php

namespace Framework\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;



/**
 * Description of AbstractCRUDController
 *
 * @author mochiwa
 */
abstract class AbstractCRUDController extends AbstractController{
    
    //protected abstract function index(RequestInterface $request) : ResponseInterface;
    protected abstract function create(RequestInterface $request) : ResponseInterface;
    protected abstract function read(RequestInterface $request) :ResponseInterface ;
    protected abstract function update(RequestInterface $request): ResponseInterface;
    protected abstract function delete(RequestInterface $request) : ResponseInterface;
}
