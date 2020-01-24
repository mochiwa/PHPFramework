<?php
namespace Framework\Html\Factory;

use Framework\Html\Attribute;
use Framework\Html\HtmlTag;
use Framework\Html\Input;




/**
 * Description of FormFactory
 *
 * @author mochiwa
 */
abstract class AbstractFormFactory {
    abstract function id() : string ;
    abstract function action() : Attribute;
    abstract function method() : Attribute;
    abstract function sectionFields():?HtmlTag;
    abstract function sectionButtons():?HtmlTag;
    abstract function label(string $text,string $for):HtmlTag;
    abstract function sectionErrors(): ?HtmlTag;
    abstract function submit(string $text): Input;
    abstract function cancel(string $text,string $target): HtmlTag;
}
