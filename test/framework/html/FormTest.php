<?php

namespace Test\Framework\html;

use Framework\Html\Factory\DefaultFormFactory;
use Framework\Html\Form;
use Framework\Html\Input;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormTest
 *
 * @author mochiwa
 */
class FormTest extends TestCase{
    
    function test_toHtml_shouldReturnAFormGeneratedWithTheDefaultFormFactory_whenFormFactoryIsNotSpecified()
    {
        $form=new Form();
        $factory=new DefaultFormFactory();
        $this->assertEquals(
                 '<form id="'.$factory->id().'" action="'.$factory->action()->getContents().'" method="'.$factory->method()->getContents().'">'
                    . $factory->sectionFields()->toHtml()
                    . $factory->sectionButtons()->toHtml()
                . '</form>', $form->toHtml());
    }
    
    function test_addInput_shouldAppendIdToTheInput_whenTheInputHasNotId()
    {
        $form=new Form();
        $input= new Input('myInput');
        $form->addInput($input);
        $this->assertEquals('<input name="myInput" type="text" id="form-myInput">', $form->getInputById('form-myInput')->toHtml());
    }
    function test_addInput_shouldNotEditTheInputId_whenTheInputHasAlreadyAnId()
    {
        $form=new Form();
        $input= new Input('myInput');
        $input->setId('teste');
        $form->addInput($input);
        $this->assertEquals('<input name="myInput" type="text" id="teste">', $form->getInputById('teste')->toHtml());
    }
    
    function test_addInput_shouldAppendIdhyphen2_whenIdAlreadyExistOnceInForm()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'))->addInput(new Input('myInput'));
        $this->assertNotNull($form->getInputById('form-myInput-2'));
    }
    function test_addInput_shouldAppendIdhyphen3_whenIdAlreadyExistTwiceInForm()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'))->addInput(new Input('myInput'))->addInput(new Input('myInput'));
        $this->assertNotNull($form->getInputById('form-myInput-3'));
    }
    function test_addInput_shouldAppendInputBetweenSectionField()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'));
        $this->assertEquals(
                  '<form id="form" action="#" method="POST">'
                    . '<div id="form-section-fields">'
                        . '<input name="myInput" type="text" id="form-myInput">'
                    . '</div>'
                    . '<div id="form-section-buttons"></div>'
                . '</form>', 
                $form->toHtml());
    }
    
    function test_addInputWithLabel_shouldAppendALabelBeforeTheInput()
    {
        $form=new Form();
        $form->addInputWithLabel(new Input('myInput'),'labelValue');
        $this->assertContains('<label for="form-myInput">labelValue</label>'
                    . '<input name="myInput" type="text" id="form-myInput">', $form->toHtml());
    }
    function test_addInputWithLabel_shouldSetTheForAttributeToTheIdOfTheInput()
    {
        $form=new Form();
        $form->addInputWithLabel(new Input('myInput'),'labelValue');
        $this->assertContains('for="form-myInput"', $form->toHtml());
    }
    
    function test_setErrors_shouldAppendAnErrorDivOnTopOfTheForm_whenTheyAreErrors()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'));
        $form->setErrors(['myInput'=>['an error occurs']]);
        $this->assertContains('<div id="formsection-errors">'
                . '<ul>'
                        . '<li>an error occurs</li>'
                    . '</ul>'
                . '</div>', $form->toHtml());
    }
    function test_fillForm_shouldAppendValueOnEachInput_whenFormContainsTheId()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'));
        $form->fillForm(['form-myInput'=>'a value']);
        $this->assertContains('<input name="myInput" type="text" id="form-myInput" value="a value">', $form->toHtml());
    }
    
    function test_addSubmit_shouldAppendASubmitButtonFromFactoryToTheSectionButton()
    {
        $form=new Form();
        $form->addSubmit('send');
        $this->assertContains('<div id="form-section-buttons">'
                . '<input name="submit" type="submit" value="send" id="form-submit">'
                . '</div>', $form->toHtml());
    }
    function test_addCancel_shouldAppendCancelButtonFromFactoryToTheSectionButton()
    {
        $form=new Form();
        $form->addCancel('cancel','#');
        $this->assertContains('<div id="form-section-buttons">'
                . '<a href="#" id="form-cancel">cancel</a>'
                . '</div>', $form->toHtml());
    }
}
