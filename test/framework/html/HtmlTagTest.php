<?php

namespace Test\Framework\html;

use Framework\Html\Attribute;
use Framework\Html\HtmlTag;
use PHPUnit\Framework\TestCase;

/**
 * Description of HtmlTagTest
 *
 * @author mochiwa
 */
class HtmlTagTest extends TestCase{
    
    function test_toHtml_shouldReturnEmptyBodyTag_whenTagNameIsBodyWithoutContent()
    {
        $tag= HtmlTag::make('body');
        $this->assertEquals('<body></body>',$tag->toHtml());
    }
    
    function test_toHtml_shouldAppendAttributeToOpenningTag_whenTagHasAttribute()
    {
        $styleAttribute=new Attribute('class',['c1']);
        $tag=HtmlTag::make('body',[$styleAttribute]);
        $this->assertEquals('<body class="c1"></body>',$tag->toHtml());
    }
    function test_toHtml_shouldSepareEachAttributeBySpace_whenThereAreManyAttribute()
    {
        $styleAttribute=new Attribute('class',['c1']);
        $refAttribute=new Attribute('href',['MyHref.com']);
        $tag=HtmlTag::make('a',[$styleAttribute,$refAttribute]);
        $this->assertEquals('<a class="c1" href="MyHref.com"></a>',$tag->toHtml());
    }
    function test_toHtml_shouldNotAppendEndingTag_whenTagIsAnEmptyTag()
    {
        $styleAttribute=new Attribute('class',['c1']);
        $tag=HtmlTag::empty('input',[$styleAttribute]);
        $this->assertEquals('<input class="c1">',$tag->toHtml());
    }
    
    function test_addChild_shouldHappendChildHtmlContentBetweenTag()
    {
        $tag=HtmlTag::make('body');
        $tag->addChild(HtmlTag::make('div'));
        
        $this->assertEquals('<body><div></div></body>', $tag->toHtml());
    }
    
    function test_addText_shouldHappendTextBetweenTag()
    {
        $tag=HtmlTag::make('body');
        $tag->addText('Hello world');
        
        $this->assertEquals('<body>Hello world</body>', $tag->toHtml());
    }
    
    function test_toHtml_shouldReturn_body_p_text_br_text_whenHtmlTagAndTextAreMixed()
    {
        $tag=HtmlTag::make('body');
        $tag->addChild(HtmlTag::make('p')->addText('text')->addChild(HtmlTag::empty('br'))->addText('text'));
        
        $this->assertEquals('<body><p>text<br>text</p></body>', $tag->toHtml());
    }
    
    function test_addStyle_shouldCreateAttributeClass_whenTagHasNotTheClassAttribute()
    {
        $tag=HtmlTag::make('body');
        $tag->addStyle('');
        $this->assertSame('<body class=""></body>', $tag->toHtml());
    }
    
    function test_addStyle_shouldAppendContent_whenClassAttributeAlreadyExist()
    {
        $styleAttribute=new Attribute('class',['c1']);
        $tag=HtmlTag::make('body',[$styleAttribute]);
        $tag->addStyle('c2');
        $this->assertSame('<body class="c1 c2"></body>', $tag->toHtml());
    }
    
    function test_setId_shouldAppendAttributeIfNotPresent()
    {
        $tag=HtmlTag::make('body');
        $tag->setId('');
        $this->assertSame('<body id=""></body>', $tag->toHtml());
    }
    function test_setId_shouldReplaceTheId_whenItAlreadyPresent()
    {
        $tag=HtmlTag::make('body',[new Attribute('id', ['test'])]);
        $tag->setId('Override');
        $this->assertSame('<body id="Override"></body>', $tag->toHtml());
    }
    function test_getId_shouldReturnEmptyString_whenTagHasNotId()
    {
        $tag=HtmlTag::make('body');
        $this->assertEquals('', $tag->getId());
    }
    function test_getId_shouldReturnTheId_whenTagNotId()
    {
        $tag=HtmlTag::make('body',[new Attribute('id', ['test'])]);
        $this->assertEquals('test', $tag->getId());
    }
    
    function test_toHtml_ShouldHtmlEntitiesTextContent()
    {
        $tag=HtmlTag::make('body');
        $tag->addText('Hello wor<ld');
        
        $this->assertEquals('<body>Hello wor&lt;ld</body>', $tag->toHtml());   
    }
    
   
    function test_hasAttributeShouldReturnFalse_whenTagHasNotTheAttribute()
    {
        $tag=HtmlTag::make('body');
        $this->assertFalse($tag->hasAttribute('id'));       
    }
    function test_hasAttributeShouldReturnTrue_whenTagHasTheAttribute()
    {
        $tag=HtmlTag::make('body');
        $tag->setId('myId');
        $this->assertTrue($tag->hasAttribute('id'));       
    }
    
    function test_searchChildById_shoulreturnTheChild_whenParentContainTheChild()
    {
        $parent=HtmlTag::make('body');
        $child=HtmlTag::make('p')->setId('test');
        $parent->addChild($child);
        $this->assertEquals($child, $parent->searchChildById('test'));
    }
    function test_searchChildById_shoulreturnTheChild_whenChildContainTheChild()
    {
        $parent=HtmlTag::make('body');
        $son=HtmlTag::make('p')->setId('son');
        $grandSon=HtmlTag::make('p')->setId('grandSon');
        $parent->addChild($son->addChild($grandSon));
        $this->assertEquals($grandSon, $parent->searchChildById('grandSon'));
    }
    
}
