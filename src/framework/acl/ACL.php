<?php

namespace Framework\Acl;

/**
 * Description of ACL
 *
 * @author mochiwa
 */
class ACL {
    /**
     * @var array of Role indexed by role name
     */
    private $roles;
    
    /**
     * @var array of Target array  indexed by role name 
     */
    private $rules;
    
    public function __construct() {
        $this->roles=[];
        $this->rules=[];
    }
    
    
    /**
     * Return the role that match by name,
     * if the role not found in ACL return empty role
     * @return Role
     */
    public function getRoleByName(string $roleName):Role{
        return $this->roles[$roleName] ?? Role::empty();
    }
    
    /**
     * Append a role if it's not already present,else throw exception
     * @param Role $role
     */
    public function addRole(Role $role) : self{
        if($this->hasRole($role)){
            throw new ACLException('the role<'.$role->name().'> already exist in ACL');
        }
        $this->roles[$role->name()]=$role;
        return $this;
    }
    
    /**
     * Return true when ACL has the role
     * @param Role $role
     * @return bool
     */
    public function hasRole(Role $role) :bool{
        return isset($this->roles[$role->name()]);
    }
    
    /**
     * <
     * @param AbstractTarget $target
     * @param Role $role
     * @throws ACLException
     */
    public function addRuleFor(AbstractTarget $target,Role $role){
        if(!$this->hasRole($role)){
            throw new ACLException('the role<'.$role->name().'> not found in ACL');
        }
        $this->rules[$role->name()][$target->name()]=$target;
    }
    
    /**
     * Return true when the role has the rule And rule must be equals
     * any else return false
     * @param Role $role
     * @param AbstractTarget $target
     * @return type
     */
    public function isRoleHasRule(Role $role, AbstractTarget $target){
        $rule=$this->findRuleForRole($role, $target);//$this->rules[$role->name()][$target->name()] ?? null;
        
        if($rule===null){
            $lowerRoles=$this->findLowerRolesThan($role);
            if(!empty($lowerRoles)){
                $valueForEachRules= array_map([$this,'isRoleHasRule'], $lowerRoles,[$target]);
                return in_array(true, $valueForEachRules);
            }
        }
        
        return ($rule != null) ;//&& $rule->isEquals($target);
    }
    
    /**
     * Return a rule equals to the target linked to the role
     * @param \Framework\Acl\Role $role
     * @param \Framework\Acl\AbstractTarget $target
     * @return type
     */
    private function findRuleForRole(Role $role, AbstractTarget $target) : ?AbstractTarget{
        $rules=$this->rules[$role->name()] ?? [];
        foreach ($rules as $rule) {
            if($rule->isEquals($target)){
                return $rule;
            }
        }
        return null;
    }
    
    /**
     * Find All lower rang at Rang-1 for example if a have a rang=7 then return all
     * role that have a rang=6
     * @param \Framework\Acl\Role $role
     * @return array
     */
    private function findLowerRolesThan(Role $role) : array{
        $currentLevel=$role->level();
        $lowerRoles=[];
        while($currentLevel>=0 && empty($lowerRoles)){
            $lowerRoles= $this->findRoleByLevel(--$currentLevel);
        }
        return $lowerRoles;
    }
    
   
    
    /**
     * Return list of rule that have the level passed in parameter
     * @param int $level
     * @return array
     */
    private function findRoleByLevel(int $level):array{
        return array_filter($this->roles,function(Role $role)use($level){return $role->level()===$level;});
    }
    
    /**
     * Load configuration from a definition
     * @param \Framework\Acl\ACLDefinition $definition
     * @throws ACLException
     */
    public function loadFromDefinition(ACLDefinition $definition):self{
        
        if(!$definition->isValid()){
            throw new ACLException('The definition is not valid');
        }
        
        $roles=$definition->getRoles();
        array_walk($roles, [$this,'addRole']);
        
        $rules=$definition->getRules();
        array_walk($rules, function(array $rules ,string $rolename){
            $role=$this->getRoleByName($rolename);
            if($role== Role::empty()){
                throw new ACLException('The role must be declared into the role index');
            }
            array_walk($rules[ACLDefinition::INDEX_ALLOW], function($target)use($role){$this->addRuleFor($target, $role);});
            array_walk($rules[ACLDefinition::INDEX_DENY], function($target)use($role){$this->addRuleFor($target, $role);});
        });
        return $this;
    }
    

    
}
