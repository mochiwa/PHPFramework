<?php

namespace Framework\Acl;

/**
 * Description of ACLDefinition
 *
 * @author mochiwa
 */
class ACLDefinition {
    const INDEX_ROLES='INDEX_FOR_ROLES';
    const INDEX_RULES='INDEX_FOR_RULES';
    const INDEX_ALLOW='INDEX_FOR_ALLOW_RULE';
    const INDEX_DENY='INDEX_FOR_DENY_RULE';
    
    /**
     * @var array 
     */
    private $definition;
  
    
    private function __construct(array $definition) {
        $this->definition = $definition;
    }
    
    
    /**
     * Load an definition from an array , an array must be like :
     * 
     * [INDEX_ROLES=>[ RoleA,RoleB,RoleC],
            INDEX_RULES=>[
                'admin'=>[
                    INDEX_ALLOW => [AbstractTarget::URL('/'),AbstractTarget::URL('/admin')],
                    INDEX_DENY=>[]
                ],
                'user'=>[
                    INDEX_ALLOW => [AbstractTarget::Controller('user')],
                    INDEX_DENY=>[]
                ],
            ]
        ]
     * @param array $definition
     * @return \self
     */
    public static function fromArray(array $definition):self
    {
        return new self($definition);
    }
    
    public function isValid():bool{
        return $this->assertRoleDefinitionIsValid() && $this->assertRuleDefinitionIsValid();
    }
    
    /**
     * First check if roles has been well formed then return roles;
     * @return array
     */
    public function getRoles() : array{
        $this->assertRoleDefinitionIsValid();
        return $this->definition[self::INDEX_ROLES];
    }
    /**
     * First check if rules has been well formed then return rules;
     * @return array
     */
    public function getRules():array{
        $this->assertRuleDefinitionIsValid();
        return $this->definition[self::INDEX_RULES];
    }
    
    
    /**
     * Assert That definition given respected the formatting for roles,
     * If a definition has not a INDEX_ROLES that point an array with only roles inner,
     * then throw an ACL Exception .
     * 
     * If no exception return the array of roles
     * @return bool
     * @throws ACLDefinitionException
     */
    public function assertRoleDefinitionIsValid() : bool{
        
        if(!isset($this->definition[self::INDEX_ROLES])){
            throw new ACLDefinitionException('The index role from ACLDefinition is Required in definition');
        }
        
        $roles=$this->definition[self::INDEX_ROLES];
        if(!is_array($roles)){
            throw new ACLDefinitionException('The index role Must contain an array with each Role to append');
        }
        
        $strangerOjects=array_filter($roles, function($role){return !($role instanceof Role);});
        if(!empty($strangerOjects)){
            throw new ACLDefinitionException('The index role Must contain an array with only Role inner');
        }
        return true;
    }
    
    /**
     * Assert That definition given respected the formatting for rules,
     * If a definition has not a INDEX_RuLES that point an array then throw an ACL Exception .
     * 
     * @return bool
     * @throws ACLDefinitionException
     */
    public function assertRuleDefinitionIsValid():bool{
        if(!isset($this->definition[self::INDEX_RULES])){
            throw new ACLDefinitionException('The index rules from ACLDefinition is Required in definition');
        }
        
        $setOfRules=$this->definition[self::INDEX_RULES];
        if(!is_array($setOfRules)){
            throw new ACLDefinitionException('The index rule Must contain an array with each Rule to append like rolename=>[]');
        }
        
        $strangerOjects=array_filter($setOfRules, function($contentOfRole){return !(is_array($contentOfRole));});
        if(!empty($strangerOjects)){
            throw new ACLDefinitionException('The role <aRole> must point to an array like rolename=>[allow=>[] , deny=>[]]');
        }
        
        foreach ($setOfRules as $roleName => $rules) {
            $this->assertRulesForRoleIsWellFormated($rules,$roleName,self::INDEX_ALLOW);
            $this->assertRulesForRoleIsWellFormated($rules,$roleName, self::INDEX_DENY);
            
        }
        
        return true;
    }
    
    /**
     * A rule for a role should be like : 
     *  'myRole' => [
     *      INDEX_ALLOW=>[],
     *      INDEX_DENY=>[]
     * ]
     * Throw exception when a rule for a role is not exactly like that (in semantic way)
     * 
     * @param array $rulesForRole
     * @param string $roleName
     * @throws ACLDefinitionException
     */
    private function assertRulesForRoleIsWellFormated(array $rulesForRole,string $roleName,string $key){
        if (!isset($rulesForRole[$key])) {
            throw new ACLDefinitionException('The role <' . $roleName . '> has not the '.$key.' key');
        }elseif (!is_array($rulesForRole[$key])) {
            throw new ACLDefinitionException('The '.$key.' key of role <' . $roleName . '> must point to an array');
        }
        
        $strangerOjects=array_filter($rulesForRole[$key], function($target){return !($target instanceof AbstractTarget );});
        if(!empty($strangerOjects)){
            throw new ACLDefinitionException('The '.ACLDefinition::INDEX_ALLOW.' key of role <aRole> must contain only AbstractTarget ');
        }
    }
}