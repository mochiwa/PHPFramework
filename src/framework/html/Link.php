<?php

use Framework\Html\Attribute;
use Framework\Html\HtmlTag;

namespace Framework\Html;

/**
 * Description of Link
 *
 * @author mochiwa
 */
class Link extends HtmlTag{
    
    
    public function __construct(string $href,string $text) {
        parent::__construct('a');
        $this->setAttribute(Attribute::oneContent('href', $href));
        $this->addText($text);
    }
}
