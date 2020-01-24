<?php

use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\ACLDefinition;
use Framework\Acl\ACLException;
use Framework\Acl\Role;
use PHPUnit\Framework\TestCase;

/**
 * Description of ACLTest
 *
 * @author mochiwa
 */
class ACLTest extends TestCase{
    private $acl;
    
    
    protected function setUp() {
        $this->acl=new ACL();

    }
    
    private function mockRole(string $name='aRole',int $level=0){
        $role=$this->createMock(Role::class);
        $role->method('name')->willReturn($name);
        $role->method('level')->willReturn($level);
        return $role;
    }
    
    private function mockTarget(string $name='aTarget'){
        $target=$this->createMock(AbstractTarget::class);
        $target->method('name')->willReturn($name);
        return $target;
    }
    
    
    function test_getRoleByName_shouldReturnEmptyRole_whenACLHasNotTheRole(){
        $this->assertEquals(Role::empty(), $this->acl->getRoleByName('aRole'));
    }
    function test_addRole_shouldAppendRole_whenACLHasNotAlreadyTheRole(){
        $role=$this->mockRole('roleName');
        
        $this->acl->addRole($role);
        $this->assertEquals($role, $this->acl->getRoleByName('roleName'));
    }
    function test_addRole_shouldThrowACLException_whenRoleAlreadyExist(){
        $role=$this->mockRole();
        $this->acl->addRole($role);
        
        $this->expectException(ACLException::class);
        $this->acl->addRole($role);
    }
    
    
    function test_addRuleFor_shouldThrowACLException_whenRoleNotFound(){
        $target=$this->mockTarget();
        $role=$this->mockRole();
        
        $this->expectException(ACLException::class);
        $this->acl->addRuleFor($target,$role);
    }
    
    
    function test_isRoleHasRule_shouldReturnFalse_whenRoleNotFound(){
        $target=$this->mockTarget();
        $role=$this->mockRole();
        
        $this->assertFalse($this->acl->isRoleHasRule($role,$target));
    }
    function test_isRoleHasRule_shouldReturnFalse_whenRoleHasNotTheRule(){
        $target=$this->mockTarget();
        $role=$this->mockRole();
        
        $this->acl->addRole($role);
        
        $this->assertFalse($this->acl->isRoleHasRule($role,$target));
    }
    function test_isRoleHasRule_shouldReturnFalse_whenRoleHasTheRule_And_TargetAreNotEquals(){
        $target=$this->mockTarget();
        $role=$this->mockRole();
        
        $this->acl->addRole($role);
        $this->acl->addRuleFor($target, $role);
        $target->expects($this->once())->method('isEquals')->willReturn(false);
        
        $this->assertFalse($this->acl->isRoleHasRule($role,$target));
    }
    function test_isRoleHasRule_shouldReturnTrue_whenRoleHasTheRule_And_TargetAreEquals(){
        $target=$this->mockTarget();
        $role=$this->mockRole();
        
        $this->acl->addRole($role);
        $this->acl->addRuleFor($target, $role);
        $target->expects($this->once())->method('isEquals')->willReturn(true);
        
        $this->assertTrue($this->acl->isRoleHasRule($role,$target));
    }
    function test_isRoleHasRule_shouldReturnTrue_whenLowerRoleHasRule(){
        $target=$this->mockTarget();
        $lowerRole=$this->mockRole('aLowerRole',0);
        $role=$this->mockRole('aRole',1);
        
        $this->acl->addRole($lowerRole);
        $this->acl->addRole($role);
        $this->acl->addRuleFor($target, $lowerRole);
        $target->expects($this->once())->method('isEquals')->willReturn(true);
        
        $this->assertTrue($this->acl->isRoleHasRule($role,$target));
    }
    
    
    
    function test_loadFromDefinition_shouldThrowACLException_whenACLDefinitionIsNotValid(){
        $definition=$this->createMock(ACLDefinition::class);
        
        $definition->expects($this->once())->method('isValid')->willReturn(false);
        $this->expectException(ACLException::class);
        $this->acl->loadFromDefinition($definition);
        
    }
    function test_loadFromDefinition_shouldAddEachRoleToACL_whenACLDefinitionHasRoles(){
        $role=$this->mockRole('aName');
        $definition=$this->createMock(ACLDefinition::class);
        
        $definition->expects($this->once())->method('isValid')->willReturn(true);
        $definition->expects($this->once())->method('getRoles')->willReturn([$role]);
        $this->acl->loadFromDefinition($definition);
        
        
        $this->assertTrue($this->acl->hasRole($role));
    }
    function test_loadFromDefinition_shouldThrowException_whenARoleFromRulesNotExistInACL(){
        $role=$this->mockRole('aName');
        $definition=$this->createMock(ACLDefinition::class);
        
        $definition->expects($this->once())->method('isValid')->willReturn(true);
        $definition->expects($this->once())->method('getRoles')->willReturn([]);
        $definition->expects($this->once())->method('getRules')->willReturn([''
            . 'aRole'=>[ACLDefinition::INDEX_ALLOW=>[]]]);
        
        $this->expectException(ACLException::class);
        $this->acl->loadFromDefinition($definition);
    }
    function test_loadFromDefinition_shouldAppendAllowedRuleForTheRole_whenRoleHasTarget(){
        $role=$this->mockRole('aName');
        $target=$this->mockTarget('targetName');
        $target->expects($this->once())->method('isEquals')->willReturn(true);
        $definition=$this->createMock(ACLDefinition::class);
        
        $definition->expects($this->once())->method('isValid')->willReturn(true);
        $definition->expects($this->once())->method('getRoles')->willReturn([$role]);
        $definition->expects($this->once())->method('getRules')->willReturn([''
             .$role->name()=>[
                 ACLDefinition::INDEX_ALLOW=>[$target],
                 ACLDefinition::INDEX_DENY=>[]
                ]]);
        
       
        $this->acl->loadFromDefinition($definition);
        $this->assertTrue($this->acl->isRoleHasRule($role, $target));
    }
    function test_loadFromDefinition_shouldAppendDeniddRuleForTheRole_whenRoleHasTarget(){
        $role=$this->mockRole('aName');
        $target=$this->mockTarget('targetName');
        $target->expects($this->once())->method('isEquals')->willReturn(true);
        $definition=$this->createMock(ACLDefinition::class);
        
        $definition->expects($this->once())->method('isValid')->willReturn(true);
        $definition->expects($this->once())->method('getRoles')->willReturn([$role]);
        $definition->expects($this->once())->method('getRules')->willReturn([''
             .$role->name()=>[
                 ACLDefinition::INDEX_ALLOW=>[],
                 ACLDefinition::INDEX_DENY=>[$target]
                ]]);
        
       
        $this->acl->loadFromDefinition($definition);
        $this->assertTrue($this->acl->isRoleHasRule($role, $target));
    }
    
}