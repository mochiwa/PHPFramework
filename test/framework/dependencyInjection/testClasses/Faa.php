<?php
namespace Test\Framework\DependencyInjection;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Foo
 *
 * @author mochiwa
 */
class Faa {
    private $id;
    
    public function __construct() {
        $this->id= rand(1,100);
    }
    
    public function id()
    {
        return $this->id;
    }
    
}
