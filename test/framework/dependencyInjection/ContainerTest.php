<?php
namespace Test\Framework\DependencyInjection;

use Exception;
use Framework\DependencyInjection\Container;
use Framework\DependencyInjection\ContainerException;
use Framework\DependencyInjection\IContainer;
use PHPUnit\Framework\TestCase;
require_once('testClasses/Foo.php');
require_once('testClasses/Faa.php');
require_once('testClasses/FaaFoo.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DependencyInjectionTest
 *
 * @author mochiwa
 */
class ContainerTest extends TestCase {
    
    private $container;
    public function setUp() {
        $this->container=new Container();
    }
    
    public function test_get_ShoulResolveClass_whenKeyNotFoundInContainer()
    {
        $this->assertNotNull($this->container->get(Foo::class));
    }
    
    public function test_get_shouldReturnAInstanceOfClass_whenClassHasBeenSetted()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $this->assertTrue($this->container->get(Foo::class) instanceof Foo);
    }
    public function test_get_shouldReturnTheSameInstance_whenItAlreadyCreated()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $idExpected=$this->container->get(Foo::class)->id();
        $idActual=$this->container->get(Foo::class)->id();
        
        $this->assertSame($idExpected,$idActual);
    }
    
    public function test_make_shouldReturnANewInstanceEachTime()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $idExpected=$this->container->make(Foo::class)->id();
        $idActual=$this->container->make(Foo::class)->id();
        
        $this->assertNotSame($idExpected,$idActual);
    }
    public function test_make_shouldResolve_whenKeyNotFoundInContainer()
    {
        $this->assertNotNull($this->container->make(Foo::class)->id());
    }
    
    public function test_appendDefinition_shouldSetEachArgument()
    {
        $this->container->appendDefinition(require('testClasses/config.php'));
        $this->assertNotNull($this->container->get(Foo::class));
        $this->assertNotNull($this->container->get(Faa::class));
        $this->assertNotNull($this->container->get(FaaFoo::class));
    }
    
    public function test_get_ShouldThrow_whenClassNotFound()
    {
        $this->expectException(Exception::class);
        $this->container->get('notExisting');
    }
   
    public function test_hasConstants_shouldReturnTrue_whenContainerHasAValueNotCallable(){
        $this->container->set('aKey', 'a word');
        $this->assertTrue($this->container->hasConstants('aKey'));
    }
    
    public function test_get_shouldReturnAStringValue_whenStringWasSetted(){
        $this->container->set('aKey', 'a word');
        $this->assertEquals('a word', $this->container->get('aKey'));
    }
    
    public function test_set_shouldAddAConstanstInContainer_whenContentAreArray()
    {
        $this->container->set('aKey', ['a word','another word']);
        $this->assertTrue($this->container->hasConstants('aKey'));
        $this->assertEquals(['a word','another word'], $this->container->get('aKey'));
    }
    
    public function test_set_shouldReplaceContent_whenKeyAreSame()
    {
        $this->container->set('aKey', ['a word','another word']);
        $this->assertTrue($this->container->hasConstants('aKey'));
        $this->container->set('aKey', [1,2,3]);
        $this->assertTrue($this->container->hasConstants('aKey'));
        $this->assertEquals([1,2,3], $this->container->get('aKey'));
    }
    
    public function test_set_shouldReplaceTheInstance_whenContainerWasOverride()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $this->assertInstanceOf(Foo::class, $this->container->get(Foo::class));
        $this->container->set(Foo::class, function(){return new Faa();});
        $this->assertInstanceOf(Faa::class, $this->container->get(Foo::class));
    }

    public function test_add_shouldAppendContent_whenContainerHasArrayOnKey()
    {
        $this->container->set('aKey', [1,2,3]);
        $this->container->add('aKey', [4,5,6]);
        $this->assertEquals([1,2,3,4,5,6], $this->container->get('aKey'));
    }
    
    public function test_add_shouldAppendANewContent_whenContainerHasNotKey()
    {
        $this->container->add('aKey', [4,5,6]);
        $this->assertEquals([4,5,6], $this->container->get('aKey'));
    }
    
    public function test_add_shouldThrowException_whenKeyExistAndIsNotAnArray()
    {
        $this->container->set('aKey', 'hello world');
        $this->expectException(ContainerException::class);
        $this->container->add('aKey', [4,5,6]);
    }
    
    public function test_appendDefinition_shouldAddContent_whenKeyIsAnArrayThatContainADD()
    {
        $this->container->set('aKey', [1,2,3]);
        $definition=['aKey'=>[IContainer::ADD=>[4,5,6]]];
        $this->container->appendDefinition($definition);
        $this->assertEquals([1,2,3,4,5,6], $this->container->get('aKey'));
    }
}
