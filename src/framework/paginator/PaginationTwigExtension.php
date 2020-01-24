<?php

namespace Framework\Paginator;

use Framework\Router\IRouter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Description of PaginationTwigExtension
 *
 * @author mochiwa
 */
class PaginationTwigExtension extends AbstractExtension{

    
    /**
     * Instance of router from this framework
     * @var IRouter 
     */
    protected $router;
    
   
    public function __construct(IRouter $router)
    {
        $this->router=$router;
    }
    
    

    public function getTests() {
        return [new TwigTest('current',[$this,'isCurrentPage'])];
    }

    
    public function getFunctions() {
        return [
            new TwigFunction('pages',  [$this,'pages']),
            new TwigFunction('previousLink',  [$this,'previousLink']),
            new TwigFunction('pageLink',  [$this,'pageLink']),
            new TwigFunction('nextLink',  [$this,'nextLink']),
        ];
    }

    public function pages(Pagination $pagination){
        return $pagination->getPages();
    }
    
    public function previousLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getPrevious()]);
    }
    
    public function pageLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getCurrentPage()]);
    }
    
    public function nextLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getNext()]);
    }
    
    public function isCurrentPage(string $page){
        return ($_SERVER['REQUEST_URI']===$page) || ($_SERVER['REQUEST_URI'] === '/parking/index' && strpos($page, '1'));
    }
    
 
    
   

    
}
