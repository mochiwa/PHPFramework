<?php

namespace Framework\Html;

use Framework\Html\Factory\AbstractFormFactory;
use Framework\Html\Factory\DefaultFormFactory;

/**
 * Description of Form
 *
 * @author mochiwa
 */
class Form extends HtmlTag{
    
    private $sectionFields;
    private $sectionButtons;
    private $formElementFactory;
    
    public function __construct(AbstractFormFactory $factory=null) {
        parent::__construct('form');
        $this->formElementFactory = $factory === null ? new DefaultFormFactory() : $factory;
        
        $this->initId();
        $this->initAction();
        $this->initMethod();
        
        $this->sectionFields= $this->formElementFactory->sectionFields();
        $this->sectionButtons= $this->formElementFactory->sectionButtons();
        $this->addChild($this->sectionFields);
        $this->addChild($this->sectionButtons);
    }
    /**
     * Init the form method from the formElementFactory
     */
    private function initMethod()
    {
        $this->setAttribute( $this->formElementFactory->method());
    }
    /**
     * Init the form action from the formElementFactory
     */
    private function initAction()
    {
        $this->setAttribute( $this->formElementFactory->action());
    }
    /**
     * Init the form id from the formElementFactory
     */
    private function initId()
    {
        $this->setId($this->formElementFactory->id());
    }
    
    /**
     * Append an input into the form
     * @param Input $input
     * @return \self
     */
    public function addInput(HtmlTag $input) : self
    {
        $uniqueInput=$this->generateUniqueInput($input);
        $this->sectionFields->addChild($uniqueInput);
        return $this;
    }
    
    /**
     * Generate an input with an unique Id
     * @param self $input
     * @return Input
     */
    private function generateUniqueInput(HtmlTag $input) : HtmlTag
    {
        if(!$input->hasAttribute('id'))
        {
            $input->setId($this->getId().'-'.$input->getName());
        }
        $input->setId($this->generateUniqueId($input->getId()));
        return $input;
    }
    
    /**
     * Generate an unique id for the form, if the Id already exist
     * then append an hypen and increment a number like
     * myId-1  , myId-2 ,...
     * @param string $baseId
     * @return string
     */
    private function generateUniqueId(string $baseId):string
    {
        if($this->getInputById($baseId)!==null)
        {
            $count=2;
            while($this->getInputById($baseId.'-'.$count)!==null)
            {
                $count++;
            }
            return $baseId.'-'.$count;
        }
        return $baseId;
    }
    
    
    /**
     * Return the input with the id,if input not found return null
     * @param string $id
     * @return Input|null
     */
    public function getInputById(string $id): ?HtmlTag
    {
        return $this->sectionFields->searchChildById($id);
    }
    
    /**
     * Append an input with its label
     * @param Input $input
     * @param string $labelValue
     */
    public function addInputWithLabel(HtmlTag $input,string $labelValue):self
    {
        $uniqueInput=$this->generateUniqueInput($input);
        $this->sectionFields->addChild($this->formElementFactory->label($labelValue,$uniqueInput->getId()));
        $this->addInput($uniqueInput);
        
        return $this;
    }
    
    /**
     * Create a div positioned on top that
     * will contain all error on from form
     * @param array $errors
     * @return \self
     */
    public function setErrors(array $errors=[]):self
    {
        $sectionError=$this->formElementFactory->sectionErrors($errors);
        if($sectionError->childrenCount()){
            $this->addChildOnTop($sectionError);
        }
        return $this;
    }
   
    /**
     * Set a value for each field
     * @param array $values
     */
    public function fillForm(array $values=[])
    {
        foreach ($values as $fieldId=>$value) {
            if($this->getInputById($fieldId)!==null)
            {
                $this->getInputById($fieldId)->setValue($value);
            }
        }
    }
    
    /**
     * Append a submit button at the bottom of the form
     * @param string $text
     */
    public function addSubmit(string $text) : self
    {
        $button = $this->formElementFactory->submit($text);
        $this->sectionButtons->addChild($button);
        return $this;
    }
    public function addCancel(string $text,string $target) :self
    {
        $button = $this->formElementFactory->cancel($text,$target);
        $this->sectionButtons->addChild($button);
        return $this;
    }
  

}
