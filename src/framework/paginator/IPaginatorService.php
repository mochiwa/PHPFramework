<?php

namespace Framework\Paginator;

/**
 * Description of IPaginatorService
 *
 * @author mochiwa
 */
interface IPaginatorService {
    
    function getDataForPage(int $currentPage,int $articlePerPage): array ;
    
    /**
     * Should return the number of page function of dataPerPage
     * @param int $dataPerPage
     * @return int
     */
    function pageCount(int $dataPerPage):int;
}
