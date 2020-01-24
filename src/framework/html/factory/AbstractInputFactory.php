<?php
namespace Framework\Html\Factory;

/**
 * Description of AbstractInputFactory
 *
 * @author mochiwa
 */
abstract class AbstractInputFactory {
    
    abstract function build(string $type,string $name);
    
}
