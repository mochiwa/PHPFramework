<?php

namespace Framework\Acl;

/**
 * Description of Role
 *
 * @author mochiwa
 */
class Role {
    /**
     *
     * @var string the role name 
     */
    private $name;
    /**
     *
     * @var int the level of the role 
     */
    private $level;
    
    
    private function __construct($name, $level) {
        $this->name = $name;
        $this->level = $level;
    }
    
    public static function empty():self{
        return new self('',-1);
    } 
    
    public static function of(string $name,int $level=0):self{
        return new self($name,$level);
    }

    public function name() {
        return $this->name;
    }
    
    public function level() {
        return $this->level;
    }


}
