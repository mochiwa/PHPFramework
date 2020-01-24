<?php

namespace Framework\Session;

/**
 * Description of FlashMessage
 *
 * @author mochiwa
 */
class FlashMessage {
    private $isError;
    private $message;
    
    
    public function __construct($message,$isError) {
        $this->isError = $isError;
        $this->message = $message;
    }
    
    public static function error(string $message) :self
    {
        return new self($message,true);
    }
    public static function success(string $message) :self
    {
        return new self($message,false);
    }
    public static function null() :self
    {
        return new self('',false);
    }
    
    
    public function getIsError() {
        return $this->isError;
    }

    public function getMessage() {
        return $this->message;
    }



}
