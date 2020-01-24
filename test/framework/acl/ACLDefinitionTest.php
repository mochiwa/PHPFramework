<?php

use Framework\Acl\ACLDefinition;
use Framework\Acl\ACLDefinitionException;
use PHPUnit\Framework\TestCase;

/**
 * Description of ACLDefinitionTest
 *
 * @author mochiwa
 */
class ACLDefinitionTest extends TestCase{
  
    
    
    
    
    function test_assertRoleDefinitionIsValid_shouldThrowException_whenIndexForRolesIsNotDefine(){
        $definition=ACLDefinition::fromArray([]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The index role from ACLDefinition is Required in definition');
        $definition->assertRoleDefinitionIsValid();
    }
    function test_assertRoleDefinitionIsValidshouldTHrowException_whenIndexForRoleHasNotArrayOfRoles(){
        $definition=ACLDefinition::fromArray([ACLDefinition::INDEX_ROLES=>'hello world']);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The index role Must contain an array with each Role to append');
        $definition->assertRoleDefinitionIsValid();
    }
    function test_assertRoleDefinitionIsValid_shouldTHrowException_whenIndexForRoleContainOtherThingThanRoleObject(){
        $definition=ACLDefinition::fromArray([ACLDefinition::INDEX_ROLES=>['hello world']]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The index role Must contain an array with only Role inner');
        $definition->assertRoleDefinitionIsValid();
    }
    
    
    
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenIndexForRulesIsNotDefine(){
        $definition=ACLDefinition::fromArray([]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The index rules from ACLDefinition is Required in definition');
        $definition->assertRuleDefinitionIsValid();
    }
    
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenTheRoleInnerNotPoindToAnArray(){
        $definition=ACLDefinition::fromArray([ACLDefinition::INDEX_RULES=>"test"]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The index rule Must contain an array with each Rule to append like rolename=>[]');
        $definition->assertRuleDefinitionIsValid();
    }
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenRoleKeyNotPoindToAnArray(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>'helloworld'
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The role <aRole> must point to an array like rolename=>[allow=>[] , deny=>[]]');
        $definition->assertRuleDefinitionIsValid();
    }
    
    
   /* $array=[Acl::ROLES_INDEX=>[Role::of('admin',3),Role::of('user',2),Role::of('visitor',1)],
            Acl::RULES_INDEX=>[
                'admin'=>[
                    'allow' => [AbstractTarget::URL('/'),AbstractTarget::URL('/admin')],
                    'deny'=>[]
                ],*/
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenRoleKeyHasNotAllowKey(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>[]
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The role <aRole> has not the '.ACLDefinition::INDEX_ALLOW.' key');
        $definition->assertRuleDefinitionIsValid();
    }
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenRoleKeyHasNotDenyKey(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>[ACLDefinition::INDEX_ALLOW=>[]]
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The role <aRole> has not the '.ACLDefinition::INDEX_DENY.' key');
        $definition->assertRuleDefinitionIsValid();
    }
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenRoleKeyAllowNotPoindAnArray(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>[ACLDefinition::INDEX_ALLOW =>'',ACLDefinition::INDEX_DENY=>'']
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The '.ACLDefinition::INDEX_ALLOW.' key of role <aRole> must point to an array');
        $definition->assertRuleDefinitionIsValid();
    }
    function test_assertRuleDefinitionIsValid_shouldThrowException_whenRoleKeyDenyNotPoindAnArray(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>[ACLDefinition::INDEX_ALLOW=>[],ACLDefinition::INDEX_DENY=>'']
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The '.ACLDefinition::INDEX_DENY.' key of role <aRole> must point to an array');
        $definition->assertRuleDefinitionIsValid();
    }
    
    function test_assertRuleDefinitionIsValid_shouldTHrowException_whenRulesForRoleContainAnyThingOtherThanAbstractTarget(){
        $definition=ACLDefinition::fromArray([
            ACLDefinition::INDEX_RULES=>[
                'aRole'=>[ACLDefinition::INDEX_ALLOW=>['something'],ACLDefinition::INDEX_DENY=>[]]
            ],
        ]);
        
        $this->expectException(ACLDefinitionException::class);
        $this->expectExceptionMessage('The '.ACLDefinition::INDEX_ALLOW.' key of role <aRole> must contain only AbstractTarget ');
        $definition->assertRuleDefinitionIsValid();
    }
    
    
}
