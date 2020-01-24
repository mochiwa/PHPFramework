<?php

namespace Framework\Html\Factory;

use Framework\Html\HtmlTag;
use Framework\Html\Link;

/**
 * Description of AbstractPaginationFactory
 *
 * @author mochiwa
 */
abstract class AbstractPaginationFactory {
    abstract function toPrevious(string $link):Link;
    abstract function toNext(string $link):Link;
    public abstract function page(int $pageNumber):Link;
    public abstract function currentPage(int $number) : HtmlTag;
    protected abstract function generateLink($link):string;
    abstract function mainStyle():string;
    
}
