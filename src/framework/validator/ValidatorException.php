<?php

namespace Framework\Validator;

/**
 * This exception will throw in a validation  context
 *
 * @author mochiwa
 */
class ValidatorException extends \Exception{
    private $errors;
    
    public function __construct(array $errors=[]) {
        $this->errors=$errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }


    
}
