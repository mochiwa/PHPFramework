<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Validator;

use Valitron\Validator;

/**
 * The AbstractFormValidator use Valitron
 * to validate form
 *
 * @author mochiwa
 */
abstract class AbstractFormValidator implements IValidator{
    /**
     * The concrete validator
     * @var Validator
     */
    protected $validator;
    /**
     *
     * @var array list of error like field=>[error,..]
     */
    protected $errors;
    
    /**
     * Form data like field => value
     * @param type $data
     */
    function __construct(array $data=[])
    {
        $this->validator=new Validator($data);
        $this->errors=[];
        $this->initRules();
    }

    /**
     * Return the list of errors like field => [error,...]
     * @return array 
     */
    public function getErrors(): array {
        $errors=$this->validator->errors();
        foreach ($errors as $field => $error){
            $this->errors[$field]=$error;
        }
        return $this->errors;
    }
    
    /**
     * Return true if validator return true
     * @return bool
     */
    public function isValid(): bool {
        return $this->validator->validate();
    }
    
    /**
     * Validate the form and return the validation status
     * @param array $data
     * @return bool
     */
    public function validate(array $data):bool
    {
        $this->validator=new Validator($data);
        $this->initRules();
        return $this->isValid();
    }
    
    /**
     * When the data is not validate then throw a ValidatorException
     * that contains errors
     * @param array $data
     * @return bool
     * @throws ValidatorException
     */
    public function validateOrThrow(array $data):bool
    {
        if(!$this->validate($data))
        {
            throw new ValidatorException($this->getErrors());
        }
        return true;
    }
    
    /**
     * Where child will put rules
     */
    public abstract function initRules();

}
