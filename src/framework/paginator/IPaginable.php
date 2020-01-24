<?php
namespace Framework\Paginator;
/**
 * Description of IPaginable
 *
 * @author mochiwa
 */
interface IPaginable {
    /**
     * Return set of data for pagination
     * @param int $current
     * @param int $max
     * @return array
     */
    function getForPagination(int $current,int $max) : array;
    /**
     * Should return the count of data;
     */
    function dataCount(): int;
}
