<?php
namespace Test\Framework\Router;

use Framework\Router\Router;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RouterTest
 *
 * @author mochiwa
 */
class RouterTest extends TestCase{
    private $router;
    
    public function setUp(): void {
        $this->router=new Router();
    }
 
    
    function test_match_shouldReturnNull_whenNoRouteMatch()
    {
        $request=new Request('GET', '/notExisting');
        
        $match=$this->router->match($request);
        $this->assertNull($match);
    }
    
    function test_match_shouldReturnRoute_whenRequestAndRouteMatch()
    {
        $request=new Request('GET', '/aRoute');
        $this->router->map('GET','/aRoute',function(){return "hello world";},'myRoute');
        
        $route=$this->router->match($request);
        $this->assertSame("hello world", call_user_func($route->target()));
    }
    
    function test_match_shouldReturnNull_whenTargetMatchButNotSameMethod()
    {
        $request=new Request('POST', '/aRoute');
        $this->router->map('GET','/aRoute',function(){return "hello world";},'myRoute');
        
        $route=$this->router->match($request);
        $this->assertNull($route);
    }
    
    function test_match_shouldReturnRoute_whenTargetMatchWithComplexURL()
    {
        $request=new Request('GET', '/aRoute/mypost-45');
        $this->router->map('GET','/aRoute/[a]-[i]',function(){return "hello world";},'myRoute');
        
        $route=$this->router->match($request);
        $this->assertNotNull($route);
    }
   
    function test_map_shouldThrowException_whenMethodIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->router->map('','/aRoute',function(){return "hello";},'routename');
    }
    
    function test_map_shouldThrowException_whenURIIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->router->map('GET','',function(){return "hello";},'routename');
    }
    
    function test_generateURL_shouldReturnHashTag_whenRouteNameNoExist()
    {
        $this->assertSame('#',$this->router->generateURL('routeName'));
    }
    
    function test_generateURL_shouldReturnASimpleURL_whenItExist()
    {
        $this->router->map('GET','/test',function(){},'myRoute');
        $this->assertSame('/test', $this->router->generateURL('myRoute'));
    }
    
    function test_generateURL_shouldReturnAComplexURL_whenItExist()
    {
        $this->router->map('GET','/test/[a:slug]-[i:id]',function(){},'myRoute');
        $this->assertSame('/test/page-01', $this->router->generateURL('myRoute',['slug'=>'page','id'=>'01']));
    }
    
   
}
