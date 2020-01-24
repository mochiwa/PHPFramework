<?php

/**
 * Description of ControllerTargetTest
 *
 * @author mochiwa
 */
class ControllerTargetTest extends PHPUnit\Framework\TestCase{
    
    function test_isEquals_shouldReturnTrue_whenControllerAreSame(){
        $a= Framework\Acl\ControllerTarget::allow('ctlA');
        $b= Framework\Acl\ControllerTarget::allow('ctlA');
        
        $this->assertTrue($a->isEquals($b));
    }
    
    function test_isEquals_shouldReturnFalse_whenControllerAreNotSame(){
        $a= Framework\Acl\ControllerTarget::allow('ctlA');
        $b= Framework\Acl\ControllerTarget::allow('ctlB');
        
        $this->assertFalse($a->isEquals($b));
    }
    function test_isEquals_shouldReturnTrue_whenControllerAndAllowedAreSame(){
        $a= Framework\Acl\ControllerTarget::allow('ctlA');
        $b= Framework\Acl\ControllerTarget::allow('ctlA');
        
        $this->assertTrue($a->isEquals($b));
    }
    
    function test_isEquals_shouldReturnFalse_whenControllerAreNotSameButAllowedNot(){
        $a= Framework\Acl\ControllerTarget::allow('ctlA');
        $b= Framework\Acl\ControllerTarget::deny('ctlA');
        
        $this->assertFalse($a->isEquals($b));
    }
    
    function test_isEquals_shouldReturnTrue_whenCurrentTargetIsController(){
        $a= Framework\Acl\ControllerTarget::allow('ctlA');
        $b= Framework\Acl\ControllerTarget::allowAction('ctlA','anAction');
        
        $this->assertTrue($b->isEquals($a));
    }
    
    
    function test_name_shouldReturnControllerNameWithAction_whenTargetIsAnActionTarget(){
        $target= Framework\Acl\ControllerTarget::allowAction('aController', 'anAction');
        $this->assertEquals('aController-anAction', $target->name());
    }
    
    function test_isEquals_shouldNotBeCaseSensitive_whenControllerAndActionAreEquals(){
        $a= Framework\Acl\ControllerTarget::allowAction('CTRLA','ANACTION');
        $b= Framework\Acl\ControllerTarget::allowAction('ctrla','anaction');
        
        $this->assertTrue($a->isEquals($b));
    }
}
