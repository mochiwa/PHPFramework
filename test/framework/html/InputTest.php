<?php

namespace Test\Framework\html;

use Framework\Html\Input;
use PHPUnit\Framework\TestCase;

/**
 * Description of InputTest
 *
 * @author mochiwa
 */
class InputTest extends TestCase {

    
    function test_toHtml_shouldReturnTypeText_whenTypeIsNotSpecified()
    {
        $input=new Input();
        $this->assertEquals('<input type="text">',$input->toHtml());
    }
    
    function test_setName_shouldAppendAttribute_whenItIsNotPresent()
    {
        $input=new Input();
        $input->setName('myInput');
        $this->assertEquals('<input type="text" name="myInput">', $input->toHtml());
    }
    
    function test_setType_shouldAppendAttribute_whenItIsNotPresent()
    {
        $input=new Input();
        $input->setType('text');
        $this->assertEquals('<input type="text">', $input->toHtml());
    }
    
    function test_setValue_shouldAppendAttribute_whenItIsNotPresent()
    {
        $input=new Input();
        $input->setValue('my Value');
        $this->assertEquals('<input type="text" value="my Value">', $input->toHtml());
    }
    function test_setPlaceHolder_shouldAppendAttribute_whenItIsNotPresent()
    {
        $input=new Input();
        $input->setPlaceHolder('my Value');
        $this->assertEquals('<input type="text" placeholder="my Value">', $input->toHtml());
    }
    function test_setRequired_shouldAppendAttribute_whenItIsNotPresent()
    {
        $input=new Input();
        $input->setRequired(true);
        $this->assertEquals('<input type="text" required="true">', $input->toHtml());
    }
    
    
    
    
}
