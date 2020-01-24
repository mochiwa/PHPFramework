<?php

namespace Framework\Html\Factory;

/**
 * Description of AbstractFlashBoxFactory
 *
 * @author mochiwa
 */
abstract class AbstractFlashBoxFactory {
    abstract function getBoxStyle(): string ; 
    abstract function getMessageStyle(): string ; 
    abstract function getMessage():string;
}
