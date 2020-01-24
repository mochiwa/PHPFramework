<?php

namespace Framework\Html\Factory;

use Framework\Html\HtmlTag;
use Framework\Html\Link;
use Framework\Router\IRouter;

/**
 * Description of DefaultPaginationFactory
 *
 * @author mochiwa
 */
class DefaultPaginationFactory extends AbstractPaginationFactory {
    /**
     * Instance of router from this framework
     * @var IRouter 
     */
    protected $router;
    
   
    public function __construct(IRouter $router)
    {
        $this->router=$router;
    }
    
    /**
     * The previous element here is <<
     * @param string $link
     * @return \Framework\Html\Factory\link
     */
    public function toPrevious(string $link): Link {
        return new Link($this->generateLink($link),'<<');
    }
    
    /**
     * The next element here is >>
     * @param string $link
     * @return \Framework\Html\Factory\link
     */
    public function toNext(string $link): Link {
        return new Link($this->generateLink($link),'>>');
    }
    /**
     * The page element
     * @param int $pageNumber
     * @return \Framework\Html\Factory\link
     */
    public function page(int $pageNumber): Link {
        return new Link($this->generateLink($pageNumber),$pageNumber);
    }
    
    /**
     * All link used generated for pagination should call this method
     * @param type $link
     * @return string
     */
    protected function generateLink($link): string {
        return $this->router->generateURL($link);
    }
    
    /**
     * The current page element
     * @param int $number
     * @return HtmlTag
     */
    public function currentPage(int $number): HtmlTag {
        return HtmlTag::make('p')->addText($number);
    }

    public function mainStyle(): string {
        return "";
    }

}
