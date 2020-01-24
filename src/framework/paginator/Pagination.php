<?php
declare(strict_types=1);
namespace Framework\Paginator;
/**
 * Responsible to manage the page number for a pagination process
 * The pagination will look like :
 *  # 1
 *      pageCount=10;
 *      limitToGenerate=5;
 *      current = 2;
 *      Result : link = [1,2,3,4,5]   previous = 1  next =3
 *  # 2
 *      pageCount=10;
 *      limitToGenerate=6;
 *      current = 8;
 *      Result : link = [7,8,9,10]   previous = 7  next = 9
 *  # 3
 *      pageCount=0
 *      limitToGenerate=6;
 *      current = 0;
 *      Result : link = [1]   previous = 1 next = 1
 *  # 4
 *      pageCount=10
 *      limitToGenerate=3;
 *      current = 10;
 *      Result : link = [8,9,10]   previous = 9 next = 10
 * 
 * @author mochiwa
 */
class Pagination {
    /**
     * The current number
     * @var int
     */
    private $current;
    
    /**
     * The total page count
     * @var int 
     */
    private $pageCount;
    
    /**
     * The count of page to generate
     * do not confuse with the number of article per page !
     * @var int
     */
    private $limitToGenerate;


    public function __construct(int $pageCount=1) {
        $this->setTotalPageCount($pageCount);
        $this->setLimitToGenerate(1);
    }
    
    /**
     * Return the previous page about the current,
     * If the current is less or equals 1 return 1
     * @return int
     */
    public function getPrevious():int
    {
        return $this->current<=1 ?  1 :  $this->current-1;
    }
    /**
     * Return the next page about the current,
     * If the current is equals 1 then return 1,
     * else if the page count and current are equals return current
     * @return int
     */
    public function getNext():int
    {
        return $this->isLastPageReach() ? $this->current : $this->current+1;
    }
    /**
     * Return true if the current page equals the total page count
     * Than means current page is the last page.
     * @return bool
     */
    public function isLastPageReach() : bool
    {
       return $this->pageCount<=$this->current ;
    }
    /**
     * Return true if the current page is the last of the current set
     * @return bool
     */
    private function isLastPageOfSetReach():bool{
        return $this->current===$this->limitToGenerate;
    }
    
    /**
     * Generate a list of page links,
     * if the current position reach the page limit to generate @see $limitToGenerate
     * Then create a new set of page where the beginning page is the current.
     * Else if no new set a required the return a set of link beginning with the beginning set
     * and finish with the ending set , the current page will be always in the set
     * @return array
     */
    public function  getPages():array
    {
        return $this->generateNewSetOfLinks($this->getBeginningPage());
    }
    
    /**
     * Return per which page to beginning the page links generation.
     * @return int
     */
    private function getBeginningPage() : int
    {
        $current=$this->getCurrentPage();
        
        if($this->isLastPageOfSetReach()){
            return $current;
        }
        return $this->getSetOfPage($current)['min'];
        
    }
    /**
     * Return the current page , if the current pas is not set or sup
     * than the page count return 1 .
     * @return int
     */
    public function getCurrentPage() : int
    {
        if(!isset($this->current) || $this->current > $this->pageCount){
            return 1;
        }
        return $this->current;
    }
    /**
     * Return an array that represent a set of page with:
     *   min => the minimal value of the current set
     *   max => the maximal value of the current set
     * The current page is alway between min and max 
     * and the size of the set is the page limit to generate
     * @param int $current
     * @return array
     */
    private function getSetOfPage(int $current) : array
    {
        $setSize=$this->getLimitToGenerate();
        $maxValue=$this->findMaxValueOfCurrentSet($current,$setSize);
        $minValue=$this->findMinValueOfCurrentSet($maxValue, $setSize);
        return ['min'=>$minValue , 'max'=>$maxValue];    
    }
    /**
     * Find the maximum value contained in the current set of page
     * @param type $current the current page
     * @param type $setSize the maximal size of the set
     * @return int
     */
    private function findMaxValueOfCurrentSet(int $current,int $setSize):int
    {
        $maxValue=0;
        for ($i = 0; $maxValue <= $current; $i++) {
            $maxValue = $setSize * $i;
        }
        return $maxValue;
    }
    /**
     * Find the minimal value for the current set of page.
     * If the minimal value is equals to 0 then return 1 (cause page 0 does not exist)
     * @param type $maxValue the max value contained in the set
     * @param type $setSize the size of the set
     * @return int
     */
    private function findMinValueOfCurrentSet($maxValue,$setSize) : int
    {
        if($maxValue>=$this->pageCount)
        {
            $minValue=($maxValue+1) - ($setSize*2);
           
            $minValue= $minValue<=1 ? $maxValue-$setSize-1 : $minValue; 
        }
        else
        {
            $minValue=$maxValue - $setSize;
        }
        return $minValue <= 0 ? 1 : $minValue;
    }
    
    /**
     * Generate a new set of pages
     * @param int $actual
     * @return array
     */
    private function generateNewSetOfLinks(int $actual) :array {
        $maxPageAvailable= $this->pageCount;
        $setSize=$this->limitToGenerate;
        $links=[];
        for ($page = 0; $page < $maxPageAvailable && $page < $setSize ;  ++$page) {
            $links[$page] = $actual + $page;
        }
        return $links;
    }

    public function setCurrent(int $current):self
    {
        $this->current=$current;
        return $this;
    }
    
    public function setTotalPageCount(int $pageCount):self
    {
        $this->pageCount = $pageCount <= 0 ? 1 : $pageCount;
        return $this;
    }
    public function setLimitToGenerate(int $limit):self
    {
        $this->limitToGenerate= $limit <= 0 ? 1 : $limit;
        return $this;
    }
    public function getLimitToGenerate():int
    {
        return $this->limitToGenerate;
    }
    
    public function getPageCount():int
    {
        return $this->pageCount;
    }
}
