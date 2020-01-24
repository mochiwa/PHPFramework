<?php

namespace Framework\Acl;

/**
 * Description of ControllerTarget
 *
 * @author mochiwa
 */
class ControllerTarget extends AbstractTarget{
    const WILDCARD='*';
    
    /**
     * The action for the controller
     * @var type 
     */
    private $action;
    
    /**
     * The name of the controller
     * @var string
     */
    private $controller;
    
    private function __construct(string $controllerName,string $action,bool $allowed) {
        $this->name= $action === self::WILDCARD ?  $controllerName : $controllerName.'-'.$action ;
        $this->controller=$controllerName;
        $this->action=$action;
        $this->isAllow=$allowed;
    }
    /**
     * Allow the whole controller
     * @param string $controllerName
     * @return \self
     */
    public static function allow(string $controllerName):self{
        return new self($controllerName,self::WILDCARD,true);
    }
    /**
     * Deny the whole controller
     * @param string $controllerName
     * @return \self
     */
    public static function deny(string $controllerName):self{
        return new self($controllerName,self::WILDCARD,false);
    }
    /**
     * Allow only an action from a controller
     * @param string $controllerName
     * @param string $actionName
     * @return \self
     */
    public static function allowAction(string $controllerName,string $actionName):self{
        return new self($controllerName,$actionName,true);
    }
    /**
     * Deny only an action from a controller
     * @param string $controllerName
     * @param string $actionName
     * @return \self
     */
    public static function denyAction(string $controllerName,string $actionName):self{
        return new self($controllerName,$actionName,false);
    }

    /**
     * Return true if the name,action and allowed are same,
     * or if the action of the target is wildcard.
     * the utilitie is like :
     *   ctrl::action === ctrl ? yes
     *   ctrl === ctrl::action ? no
     * An action controller 'inherites' from controller so it is
     * the invert is false.
     * Case sensitive : false
     * 
     * 
     * @param \Framework\Acl\AbstractTarget $target
     * @return bool
     */
    public function isEquals(AbstractTarget $target): bool {
        return $this->controller()===$target->controller() &&
                $this->isAllowed() === $target->isAllowed() && 
                ($this->action()===$target->action() || $target->action()===self::WILDCARD);
        
    }
    
    public function controller():string{
        return strtoupper($this->controller);
    }
    
    public function action():string{
        return strtolower($this->action);
    }

}
