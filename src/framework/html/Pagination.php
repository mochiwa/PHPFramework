<?php

namespace Framework\Html;

use Framework\Html\Factory\AbstractPaginationFactory;
use Framework\Paginator\Pagination AS Paginator;

/**
 * Description of Pagination
 *
 * @author mochiwa
 */
class Pagination extends HtmlTag{
    private $factory;
    private $pagination;
   
    public function __construct(AbstractPaginationFactory $factory , Paginator $pagination) {
        parent::__construct('div');
        $this->pagination=$pagination;
        $this->factory=$factory;
        $this->addStyle($this->factory->mainStyle());
    }
    
    public function toHtml(): string {
        $this->addChild($this->factory->toPrevious($this->pagination->getPrevious()));
        
        foreach ($this->pagination->getPages() as $page) {
            $this->addChild($this->factory->page($page));
        }
        
        
        $this->setTheCurrentPage();
        $this->addChild($this->factory->toNext($this->pagination->getNext()));
        return parent::toHtml();
    }
    
    /**
     * If the current page is present then
     * edit list of link to set it
     */
    private function setTheCurrentPage()
    {
        $current=$this->pagination->getCurrentPage();
        if(isset($this->children[$current])){
            $this->children[$current]=$this->factory->currentPage($current);
        }
    }

    
    
}
