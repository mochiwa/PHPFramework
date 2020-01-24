<?php
namespace Test\Framework\html;

use Framework\html\Attribute;
use PHPUnit\Framework\TestCase;
/**
 * Description of AttributeTest
 *
 * @author mochiwa
 */
class AttributeTest extends TestCase{
    
    function test_format_shouldReturnTheNameOfTheAttributeWithoutContent_whenTheAttributeHasNoContent()
    {
        $attribute=new Attribute('class');
        $this->assertEquals('class=""',$attribute->format());
    }
    
    function test_format_shouldReturnTheAttributeContentsBetweenQuote_whenAttributeHasContent()
    {
        $attribute=new Attribute('class',['c1']);
        $this->assertEquals('class="c1"',$attribute->format());
    }
    function test_format_shouldSepareEachContentBySpace()
    {
        $attribute=new Attribute('class',['c1','c2']);
        $this->assertEquals('class="c1 c2"',$attribute->format());
    }
    
    function test_format_shouldRemoveAllNoAlphaCharacters_whenTheAttributeNameContentSpecialChars()
    {
        $attribute=new Attribute('clas&é"123s');
        $this->assertEquals('class=""',$attribute->format());
    }
    function test_format_shouldTransformToLowerCase_whenTheAttributeNameContainUpperCase()
    {
        $attribute=new Attribute('CLASS');
        $this->assertEquals('class=""',$attribute->format());
    }
    function test_format_shouldHtmlEntities_WhenContentContainsHtmlCharacter()
    {
        $attribute=new Attribute('class',['<\'>"']);
        $this->assertNotEquals('class="<\'>"',$attribute->format());
    }
    
    function test_addContent_shouldHtmlEntitiesTheContent()
    {
        $attribute=new Attribute('class');
        $attribute->addContent('c1<>"');
        $this->assertNotEquals('class="c1<>""',$attribute->format());
    }
    
    function test_addContent_shouldNotAppendAContent_whenItIsAlreadyPresent()
    {
        $attribute=new Attribute('class');
        $attribute->addContent('c1');  
        $attribute->addContent('c1');  
        $this->assertEquals('class="c1"', $attribute->format());
    }
    
    function test_getContents_shouldReturnEmptyString_whenAttributeHasNoContents()
    {
        $attribute=new Attribute('class');
        $this->assertEquals('',$attribute->getContents() );
    }
    function test_getContents_shouldReturnTheContentSeparedBySpace_whenAttributeHasContent()
    {
        $attribute=new Attribute('class');
        $attribute->addContent('c1');  
        $attribute->addContent('c2');    
        $this->assertEquals('c1 c2',$attribute->getContents() );
    }
}
