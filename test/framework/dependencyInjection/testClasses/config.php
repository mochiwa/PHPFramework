<?php
use Test\Framework\DependencyInjection\Foo;
use Test\Framework\DependencyInjection\Faa;
use Test\Framework\DependencyInjection\FaaFoo;


return [
    Foo::class => function(){ return new Foo(); },
    Faa::class => function(){ return new Faa(); }   ,
    FaaFoo::class => function($di) { return new FaaFoo($di->get(Foo::class)); }   
];