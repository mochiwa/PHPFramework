<?php
namespace Test\Framework\DependencyInjection;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of FaaFoo
 *
 * @author mochiwa
 */
class FaaFoo {
    private $foo;
    public function __construct(Foo $foo) {
        $this->foo=$foo;
    }
    
    function getFoo()
    {
        return $this->foo;
    }
}
