<?php

namespace Framework\Session;

/**
 * A session with Php $_SESSION
 *
 * @author mochiwa
 */
class PhpSession implements ISession{
    /**
     * @var bool 
     */
    private $isStarted;
    
    function __construct() {
        $this->isStarted=session_status()===PHP_SESSION_ACTIVE;
    }
   
    public function add(string $key, $value): void {
        if($this->isStarted){
            $_SESSION[$key]=$value;
        }
    }

    public function get(string $key) {
        if($this->isStarted && isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return null;
    }
    
    public function remove(string $key): void {
        if($this->isStarted){
            unset($_SESSION[$key]);
        }
    }

    public function isStarted(): bool {
        return $this->isStarted;
    }

    

    public function start() {
        if(session_status()!==PHP_SESSION_ACTIVE){
            $this->isStarted=session_start();
        }
    }

    public function stop() {
        if(session_status()===PHP_SESSION_ACTIVE){
            $this->isStarted=!session_destroy();
        }
    }

}
