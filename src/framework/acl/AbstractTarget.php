<?php

namespace Framework\Acl;

/**
 * Description of AbstractTarget
 *
 * @author mochiwa
 */
abstract class AbstractTarget {
    /**
     * @var string name of the target
     */
    protected $name;
    /**
     * @var bool allowed or denied target
     */
    protected $isAllow;
    
    
    public function name():string{
        return $this->name;
    }
    public function isAllowed():bool{
        return $this->isAllow;
    }
    
    public abstract function isEquals(AbstractTarget $target) :bool;
    
}
