<?php
namespace Framework\Html\Factory;

use Framework\Html\HtmlTag;
use Framework\Html\Input;
use Framework\Html\TextArea;

/**
 * The basic input factory without special behavior
 * @author mochiwa
 */
class DefaultInputFactory extends AbstractInputFactory{
    
    public function email($name) :Input {
        return new Input($name, 'email');
    }

    public function file($name) :Input {
        return new Input($name, 'file');
    }

    public function password($name) :Input {
        return new Input($name, 'password');
    }

    public function text($name) :Input {
        return new Input($name, 'text');
    }
    
    public function textArea($name): TextArea {
        return new TextArea($name);
    }
    
    
    public function build(string $type, string $name) : HtmlTag {
        return  call_user_func_array([$this,$type], [$name]);
    }

}
