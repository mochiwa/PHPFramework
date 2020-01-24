<?php

namespace Framework\Html;

/**
 * Description of TextArea
 *
 * @author mochiwa
 */
class TextArea extends HtmlTag{
    /**
     * The name of the textarea in the attribute 'name'
     * @var type 
     */
    private $inputName;
    public function __construct(string $name) {
        parent::__construct('textArea');
        $this->setName($name);
    }
    
    /**
     * Set the value of input
     * @param string $value
     * @return \self
     */
    public function setValue(string $value):self
    {
        $this->addText($value);
        return $this;
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
     * Return the name of the input
     * @return string
     */
    public function getName():string
    {
        return $this->inputName;
    }
}
