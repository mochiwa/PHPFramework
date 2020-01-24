<?php
namespace Test\Framework\Renderer;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RendererTest
 *
 * @author mochiwa
 */
class ViewBuilderTest extends \PHPUnit\Framework\TestCase{
    private $viewBuilder;
    
    public function setUp() {
        $this->viewBuilder=new \Framework\Renderer\ViewBuilder();
    }
    
    function test_addPath_shouldThrow_whenNamespaceIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->addPath('',__DIR__.'/views');
    }
    function test_addPath_shouldThrow_whenPathIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->addPath('',__DIR__.'/views');
    }
    
    function test_build_shouldThrow_whenViewDoesNotContainNameSpace(){
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->build('namespace/viewTest');
    }
    
    function test_build_shouldThrow_whenEndingCharacterIsASlash(){
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->build('@namespace/viewTest/');
    }
    function test_build_shouldThrow_whenNamespaceNotFound(){
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->build('@namespace/viewTest');
    }
    function test_build_shouldThrow_whenViewNotFound(){
        $this->viewBuilder->addPath('namespace',__DIR__.'/views');
        $this->expectException(\InvalidArgumentException::class);
        $this->viewBuilder->build('@namespace/NotExistingView');
    }
    
    function test_addPath_shouldAppendThePath()
    {
        $this->viewBuilder->addPath('namespace',__DIR__.'/views');
        $content=$this->viewBuilder->build('@namespace/viewTest');
        $this->assertSame('hello world', $content);
    }
    
    function test_build_shouldPassParametersToView()
    {
        $this->viewBuilder->addPath('namespace',__DIR__.'/views');
        $content=$this->viewBuilder->build('@namespace/viewParameters',['name'=>"john doe"]);
        $this->assertSame('hello john doe', $content);
    }
    
    function test_query_shouldAddTheViewBuilderIntoTheView()
    {
        $this->viewBuilder->addPath('namespace',__DIR__.'/views');
        $content=$this->viewBuilder->build('@namespace/viewWithInclude');
        $this->assertSame('hello world', $content);
    }
    
    
    function test_build_shouldInsertContentInContent_whenLayoutIsSetted()
    {
        $this->viewBuilder->addPath('namespace',__DIR__.'/views');
        $this->viewBuilder->setDefaultLayout('@namespace/layout');
        $content=$this->viewBuilder->build('@namespace/viewTest');
        $this->assertSame('BEFORE hello world AFTER', $content);
    }
}
