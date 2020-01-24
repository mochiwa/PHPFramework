<?php
namespace Framework\DependencyInjection;

/**
 * Interface extended on the PSR container interface
 * with 2 methods more to manage the container more
 * easily.
 *
 * @author mochiwa
 */
interface IContainer extends \Psr\Container\ContainerInterface{
    const ADD='APPEND_ITEM_TO_CONTAINER';
    
    /**
     * Append a set of rule , a 'definition'
     * @param array $definitions
     */
    function appendDefinition(array $definitions);
    /**
     * Create a new instance 
     * @param type $key
     */
    function make($key);
}
