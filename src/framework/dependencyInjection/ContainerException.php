<?php
namespace Framework\DependencyInjection;

/**
 * Description of ContainerException
 *
 * @author mochiwa
 */
class ContainerException extends \Exception implements \Psr\Container\ContainerExceptionInterface{
    
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
