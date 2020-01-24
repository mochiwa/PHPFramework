<?php

namespace Framework\Html;

/**
 * Represent an input like <input type=text ...>
 *
 * @author mochiwa
 */
class Input extends HtmlTag{
    /**
     * The name of the input in the attribute 'name'
     * @var type 
     */
    private $inputName;
    
    public function __construct(string $name='',string $type='text',bool $isRequired=false) {
        parent::__construct('input',[],true);
        $this->setName($name);
        $this->setType($type);
        $this->setRequired($isRequired);
    }
    

    /**
     * Set the name of the input
     * @param string $name
     * @return \self
     */
    public function setName(string $name):self
    {
        if(!empty(trim($name))){
            $this->setAttribute(Attribute::oneContent('name',$name));
        }
        $this->inputName=$name;
        return $this;
    }
    /**
     * Set the type of input
     * @param string $type
     * @return \self
     */
    public function setType(string $type):self
    {
        if(!empty(trim($type))){
            $this->setAttribute(Attribute::oneContent('type',$type));
        }
        return $this;
    }
    /**
     * Set the value of input
     * @param string $value
     * @return \self
     */
    public function setValue(string $value):self
    {
        if(!empty(trim($value))){
            $this->setAttribute(Attribute::oneContent('value',$value));
        }
        return $this;
    }
    
    /**
     * set the placeholder
     * @param string $placeholder
     * @return \self
     */
    public function setPlaceHolder(string $placeholder):self
    {
        if(!empty(trim($placeholder))){
            $this->setAttribute(Attribute::oneContent('placeholder',$placeholder));
        }
        return $this;
    }
    /**
     * Set if this input is required
     * @param bool $required
     * @return \self
     */
    public function setRequired(bool $required):self
    {
        if($required){
            $this->setAttribute(Attribute::oneContent('required',$required ? 'true':'false'));
        }
        return $this;
    }
    
    /**
     * Return the name of the input
     * @return string
     */
    public function getName():string
    {
        return $this->inputName;
    }
 
}
