<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of IMiddlewareDispatcher
 *
 * @author mochiwa
 */
interface IMiddlewareDispatcher extends RequestHandlerInterface{
    /**
     * Append a middleware
     * @param \Framework\Middleware\MiddlewareInterface $middleware
     * @return \self
     */
    function addMiddleware(MiddlewareInterface $middleware):self;
}
